<?php

class ControllerModuleSmsc extends Controller
{
    /**
     * @var array
     */
    private $settings;

    /**
     * ControllerModuleSmsc constructor.
     *
     * @param $registry
     */
    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model('checkout/order');
        $this->load->model('setting/setting');

        $this->settings = $this->model_setting_setting->getSetting('smsc');
    }

    public function notification($orderId)
    {
        if (isset($this->settings['smsc_status'])) {
            $phones = $this->fetchPhones($this->settings['smsc_phones']);

            $template = $this->settings['smsc_template'];

            $order = $this->model_checkout_order->getOrder($orderId);

            $order = $this->filterAttributes($order);

            $message = $this->makeMessage($order, $template);

            if ($this->settings['smsc_status'] === "active") {
                $credentials = array(
                    'login' => $this->settings['smsc_login'],
                    'password' => $this->settings['smsc_password'],
                );

                $options = array(
                    'charset' => $this->settings['smsc_charset'],
                    'debug' => true,
                );

                return $this->sendSms($phones, $message, $credentials, $options);
            }

            if ($this->settings['smsc_status'] === 'demo') {
                return $this->sendFakeSms($phones, $message);
            }
        }
    }

    /**
     * @param $order
     * @return array
     */
    private function filterAttributes($order)
    {
        $attributes = [
            'order_id', 'store_name', 'customer_id', 'firstname', 'lastname',
            'email', 'telephone', 'payment_method', 'total', 'currency_code',
        ];

        $order = array_filter($order, function ($attribute) use ($attributes) {
            return in_array($attribute, $attributes);
        }, ARRAY_FILTER_USE_KEY);

        return $order;
    }

    /**
     * @param $order
     * @param $template
     * @return mixed
     */
    private function makeMessage($order, $template)
    {
        $tokens = array_map(function ($attribute) {
            return '{' . $attribute . '}';
        }, array_keys($order));

        $values = array_values($order);

        return str_replace($tokens, $values, $template);
    }

    /**
     * @param $to
     * @param $subject
     * @param $message
     */
    private function sendEmail($to, $subject, $message)
    {
        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo($to);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject($subject);
        $mail->setText($message);
        return $mail->send();
    }

    /**
     * Fetch phones from a string
     *
     * @param $phonesInput
     * @return array
     */
    private function fetchPhones($phonesInput)
    {
        $phones = explode(PHP_EOL, $phonesInput);

        if (count($phones) === 0) {
            return [];
        }

        return array_filter(
            array_map(function($phone) {
                return preg_replace('/[^\d\+]/', '', $phone);
            }, $phones)
        );
    }

    /**
     * Send fake sms
     *
     * @param $phones
     * @param $message
     */
    private function sendFakeSms($phones, $message)
    {
        $text = 'Текст сообщения: ' . $message;
        $text .= "\n\rНомера получателей: " . implode(', ', $phones);

        $this->sendEmail(
            $this->config->get('config_email'),
            'Смс уведомление о заказе с сайта',
            $text
        );
    }

    /**
     * Send sms
     *
     * @param $phones
     * @param $message
     * @param $credentials
     * @param $options
     */
    private function sendSms($phones, $message, $credentials, $options = array())
    {
        $this->load->library('smsc_api');
        $this->smsc_api->setCredentials($credentials);
        $this->smsc_api->setOptions($options);
        $this->smsc_api->send($phones, $message, 0, 0, 0, 0, $this->settings['smsc_sender_name']);
    }
}
