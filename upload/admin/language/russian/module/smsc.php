<?php
// Heading
$_['heading_title'] = 'SMSC уведомления о заказе';

// Text
$_['text_module'] = 'Модули';
$_['text_success'] = 'Настройки модуля обновлены!';
$_['text_edit'] = 'Редактирование модуля';
$_['text_demo'] = 'Демо режим';

// Entry
$_['entry_status'] = 'Статус';
$_['entry_phones'] = 'Номера телефонов для уведомлений';
$_['entry_template'] = 'Шаблон СМС сообщения';
$_['entry_login'] = 'Логин клиента';
$_['entry_password'] = 'Пароль клиента';
$_['entry_charset'] = 'Кодировка сообщения';
$_['entry_sender_name'] = 'Имя отправителя';

// Help
$_['help_phones'] = 'Номера телефонов необходимо вводить каждый на новой строке в формате: +79999999999';
$_['placeholder_phones'] = 'Например: +79991234567';
$_['help_template'] = 'В этом поле доступны токены. <a href="#tokensModal" data-toggle="modal" data-target="#tokensModal">Показать доступные токены</a>';
$_['placeholder_template'] = 'Например: Новый заказ #{order_id} на {total} {currency_code}. {store_name}.';
$_['module_disabled_notification'] = '<strong>Модуль выключен.</strong> Чтобы получать уведомления, измените статус на "Включено".';
$_['demo_mode_description'] = 'Демо режим позволяет проверить работу модуля, без реальной отправки СМС. Вместо СМС отправляется письмо на e-mail адрес магазина:';

// Error
$_['error_permission'] = 'У Вас нет прав для управления этим модулем.';
$_['error_phones'] = 'Необходимо ввести хотя бы 1 номер телефона.';
$_['error_template'] = 'Введите шаблон СМС сообщения для уведомлений.';
$_['error_smsc_login'] = 'Необходимо ввести логин клиента.';
$_['error_smsc_password'] = 'Необходимо ввести пароль клиента.';
$_['error_smsc_charset'] = 'Необходимо выбрать правильную кодировку.';
$_['error_smsc_sender_name'] = 'Необходимо ввести имя отправителя.';

$_['show_tokens_link_text'] = 'Показать доступные токены';
$_['token_modal_title'] = 'Список доступных токенов';
$_['close_btn_text'] = 'Закрыть';
$_['author_label'] = 'Автор модуля';
$_['site_label'] = 'сайт';
$_['button_save'] = 'Сохранить';

// token descriptions
$_['token_order_id_descrtiption'] = 'Уникальный идентификатор заказа в системе.';
$_['token_store_name_descrtiption'] = 'Название магазина, в котором произведен заказ.';
$_['token_customer_id_descrtiption'] = 'Уникальный идентификатор покупателя в системе.';
$_['token_firstname_descrtiption'] = 'Имя покупателя.';
$_['token_lastname_descrtiption'] = 'Фамилия покупателя.';
$_['token_email_descrtiption'] = 'Адрес e-mail покупателя.';
$_['token_telephone_descrtiption'] = 'Номер телефона покупателя.';
$_['token_payment_method_descrtiption'] = 'Выбранный метод оплаты.';
$_['token_total_descrtiption'] = 'Сумма заказа.';
$_['token_currency_code_descrtiption'] = 'Валюта.';