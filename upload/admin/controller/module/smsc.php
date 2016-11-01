<?php

class ControllerModuleSmsc extends Controller
{
    /**
     * Версия модуля
     */
    const VERSION = "1.0";

    /**
     * @var array
     */
    private $error = array();

    public function index()
    {
        $this->language->load('module/smsc');
        $this->load->model('setting/setting');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $password = $this->request->post['smsc_password'];

            if (! $this->isMd5($password)) {
                $password = md5($password);
            }

            $this->request->post['smsc_password'] = $password;
            $this->request->post['smsc_phones'] = $this->clearPhones($this->request->post['smsc_phones']);

            $this->model_setting_setting->editSetting('smsc', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $attributes = array('smsc_status', 'smsc_phones', 'smsc_template', 'smsc_login', 'smsc_password', 'smsc_charset', 'smsc_sender_name');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_demo'] = $this->language->get('text_demo');
        $data['config_mail'] = $this->config->get('config_email');

        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_phones'] = $this->language->get('entry_phones');
        $data['entry_template'] = $this->language->get('entry_template');
        $data['entry_login'] = $this->language->get('entry_login');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_charset'] = $this->language->get('entry_charset');
        $data['entry_sender_name'] = $this->language->get('entry_sender_name');

        $data['help_phones'] = $this->language->get('help_phones');
        $data['placeholder_phones'] = $this->language->get('placeholder_phones');

        $data['help_template'] = $this->language->get('help_template');
        $data['placeholder_template'] = $this->language->get('placeholder_template');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['show_tokens_link_text'] = $this->language->get('show_tokens_link_text');
        $data['token_modal_title'] = $this->language->get('token_modal_title');
        $data['close_btn_text'] = $this->language->get('close_btn_text');
        $data['module_disabled_notification'] = $this->language->get('module_disabled_notification');
        $data['demo_mode_description'] = $this->language->get('demo_mode_description');
        $data['author_label'] = $this->language->get('author_label');
        $data['site_label'] = $this->language->get('site_label');

        $data['tokens'] = array(
            '{order_id}' => $this->language->get('token_order_id_descrtiption'),
            '{store_name}' => $this->language->get('token_store_name_descrtiption'),
            '{customer_id}' => $this->language->get('token_customer_id_descrtiption'),
            '{firstname}' => $this->language->get('token_firstname_descrtiption'),
            '{lastname}' => $this->language->get('token_lastname_descrtiption'),
            '{email}' => $this->language->get('token_email_descrtiption'),
            '{telephone}' => $this->language->get('token_telephone_descrtiption'),
            '{payment_method}' => $this->language->get('token_payment_method_descrtiption'),
            '{total}' => $this->language->get('token_total_descrtiption'),
            '{currency_code}' => $this->language->get('token_currency_code_descrtiption'),
        );

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['errors'] = $this->error;

        foreach ($attributes as $key) {
            if (isset($this->error[$key])) {
                $data['error_'.$key] = $this->error[$key];
            } else {
                $data['error_'.$key] = '';
            }
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/smsc', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('module/smsc', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        foreach ($attributes as $key) {
            if (isset($this->request->post[$key])) {
                $data[$key] = $this->request->post[$key];
            } else {
                $data[$key] = $this->config->get($key);
            }
        }

        if (! $data['smsc_sender_name']) {
            $data['smsc_sender_name'] = $this->config->get('config_name');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/smsc.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/smsc')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $required = array('smsc_phones', 'smsc_template', 'smsc_login', 'smsc_password', 'smsc_charset', 'smsc_sender_name');

        foreach ($required as $attibute) {
            if (! $this->request->post[$attibute]) {
                $this->error[$attibute] = $this->language->get('error_'.$attibute);
            }
        }

        return ! $this->error;
    }

    public function install()
    {
        $this->load->model('extension/event');

        $this->model_extension_event->addEvent('smsc', 'post.order.add', 'module/smsc/notification');
    }

    public function uninstall()
    {
        $this->load->model('extension/event');

        $this->model_extension_event->deleteEvent('smsc');
    }

    /**
     * Clear phones from a string
     *
     * @param $phonesInput
     * @return array
     */
    private function clearPhones($phonesInput)
    {
        $phones = explode(PHP_EOL, $phonesInput);

        if (count($phones) === 0) {
            return [];
        }

        return implode(PHP_EOL, array_filter(
            array_map(function($phone) {
                return preg_replace('/[^\d\+]/', '', $phone);
            }, $phones)
        ));
    }

    /**
     * Check string for md5
     *
     * @param $string
     * @return bool
     */
    private function isMd5($string) {
        return strlen($string) == 32 && ctype_xdigit($string);
    }
}
