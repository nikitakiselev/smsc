<?php

/**
 * Class smsc_api
 *
 * @author Nikita Kiselev <mail@nikitakiselev.ri>
 * @site https://nikitakiselev.ru
 */
class smsc_api
{
    /**
     * @var string client login
     */
    private $login;

    /**
     * @var string client password
     */
    private $password;

    /**
     * @var array smsc api options
     */
    private $options;

    /**
     * @var Log
     */
    private $logger;

    /**
     * smsc_api constructor.
     *
     * @param $registry
     */
    public function __construct($registry)
    {
        // set defaults
        $this->options = [
            'login' => '',
            'password' => '',
            'post' => 0,
            'https' => false,
            'charset' => 'windows-1251',
            'debug' => false,
            'from' => 'api@smsc.ru',
        ];

        $this->logger = new Log('smsc.log');
    }

    /**
     * Set smsc api options
     *
     * @param array $options
     */
    public function setOptions($options = array())
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Set user credentials
     *
     * @param string|array $login
     * @param string|null $password
     */
    public function setCredentials($login, $password = null)
    {
        if (! is_array($login)) {
            $this->login = $login;
            $this->password = $password;
        } else {
            $this->login = $login['login'];
            $this->password = $login['password'];
        }
    }

    /**
     * Check for debug mode
     *
     * @return bool
     */
    public function isDebugMode()
    {
        return (bool) $this->options['debug'];
    }

    /**
     * Функция отправки SMS
     *
     * @param string|array $phones массив телефонов
     * @param string $message отправляемое сообщение
     * @param int $translit переводить или нет в транслит (1,2 или 0)
     * @param int $time
     * @param int $id
     * @param int $format формат сообщения (0 - обычное sms, 1 - flash-sms, 2 - wap-push, 3 - hlr, 4 - bin, 5 - bin-hex, 6 - ping-sms, 7 - mms, 8 - mail, 9 - call)
     * @param bool|string $sender имя отправителя (Sender ID)
     * @param string $query
     * @param array $files
     * @return array
     */
    public function send($phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = false, $query = "", $files = array())
    {
        if (is_array($phones)) {
            $phones = implode(';', $phones);
        }

        static $formats = array(1 => "flash=1", "push=1", "hlr=1", "bin=1", "bin=2", "ping=1", "mms=1", "mail=1", "call=1");

        $m = $this->_smsc_send_cmd("send", "cost=3&phones=".urlencode($phones)."&mes=".urlencode($message).
            "&translit=$translit&id=$id".($format > 0 ? "&".$formats[$format] : "").
            ($sender === false ? "" : "&sender=".urlencode($sender)).
            ($time ? "&time=".urlencode($time) : "").($query ? "&$query" : ""), $files);

        if ($this->isDebugMode()) {
            if ($m[1] > 0)
                $this->logger->write("Сообщение отправлено успешно. ID: " . $m[0] . ", всего SMS: " . $m[1] . ", стоимость: " . $m[2] . ", баланс: " . $m[3] . ".");
            else
                $this->logger->write("Ошибка №" . -$m[1] . ($m[0] ? ", ID: ".$m[0] : ""));
        }

        return $m;
    }

    /**
     * Функция вызова запроса. Формирует URL и делает 3 попытки чтения
     *
     * @param $cmd
     * @param string $arg
     * @param array $files
     * @return array
     */
    protected function _smsc_send_cmd($cmd, $arg = "", $files = array())
    {
        $url = ($this->options['https'] ? "https" : "http")."://smsc.ru/sys/$cmd.php?login=".urlencode($this->login)."&psw=".urlencode($this->password)."&fmt=1&charset=".$this->options['charset']."&".$arg;

        $i = 0;
        do {
            if ($i) {
                sleep(2 + $i);

                if ($i == 2)
                    $url = str_replace('://smsc.ru/', '://www2.smsc.ru/', $url);
            }

            $ret = $this->_smsc_read_url($url, $files);
        }
        while ($ret == "" && ++$i < 4);

        if ($ret == "") {
            if ($this->isDebugMode())
                $this->logger->write("Ошибка чтения адреса: $url");

            $ret = ","; // фиктивный ответ
        }

        $delim = ",";

        if ($cmd == "status") {
            parse_str($arg);

            if (strpos($id, ","))
                $delim = "\n";
        }

        return explode($delim, $ret);
    }

    /**
     * Функция чтения URL. Для работы должно быть доступно:
     * curl или fsockopen (только http) или включена опция allow_url_fopen для file_get_contents
     *
     * @param $url
     * @param $files
     * @return mixed|string
     */
    protected function _smsc_read_url($url, $files)
    {
        $ret = "";
        $post = $this->options['post'] || strlen($url) > 2000 || $files;

        if (function_exists("curl_init"))
        {
            static $c = 0; // keepalive

            if (!$c) {
                $c = curl_init();
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($c, CURLOPT_TIMEOUT, 60);
                curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
            }

            curl_setopt($c, CURLOPT_POST, $post);

            if ($post)
            {
                list($url, $post) = explode("?", $url, 2);

                if ($files) {
                    parse_str($post, $m);

                    foreach ($m as $k => $v)
                        $m[$k] = isset($v[0]) && $v[0] == "@" ? sprintf("\0%s", $v) : $v;

                    $post = $m;
                    foreach ($files as $i => $path)
                        if (file_exists($path))
                            $post["file".$i] = function_exists("curl_file_create") ? curl_file_create($path) : "@".$path;
                }

                curl_setopt($c, CURLOPT_POSTFIELDS, $post);
            }

            curl_setopt($c, CURLOPT_URL, $url);

            $ret = curl_exec($c);
        }
        elseif ($files) {
            if ($this->isDebugMode())
                $this->logger->write("Не установлен модуль curl для передачи файлов");
        }
        else {
            if (!$this->options['https'] && function_exists("fsockopen"))
            {
                $m = parse_url($url);

                if (!$fp = fsockopen($m["host"], 80, $errno, $errstr, 10))
                    $fp = fsockopen("212.24.33.196", 80, $errno, $errstr, 10);

                if ($fp) {
                    fwrite($fp, ($post ? "POST $m[path]" : "GET $m[path]?$m[query]")." HTTP/1.1\r\nHost: smsc.ru\r\nUser-Agent: PHP".($post ? "\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: ".strlen($m['query']) : "")."\r\nConnection: Close\r\n\r\n".($post ? $m['query'] : ""));

                    while (!feof($fp))
                        $ret .= fgets($fp, 1024);
                    list(, $ret) = explode("\r\n\r\n", $ret, 2);

                    fclose($fp);
                }
            }
            else
                $ret = file_get_contents($url);
        }

        return $ret;
    }
}
