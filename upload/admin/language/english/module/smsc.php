<?php
// Heading
$_['heading_title'] = 'SMSC notifications';

// Text
$_['text_module'] = 'Modules';
$_['text_success'] = 'Module settings updated!';
$_['text_edit'] = 'Edit module settings';
$_['text_demo'] = 'Demo';

// Entry
$_['entry_status'] = 'Status';
$_['entry_phones'] = 'Phone numbers';
$_['entry_template'] = 'Message template';
$_['entry_login'] = 'Login';
$_['entry_password'] = 'Passwird';
$_['entry_charset'] = 'Charset';

// Help
$_['help_phones'] = 'Phone numbers must be entered each on a new line in the format: +79999999999';
$_['placeholder_phones'] = 'Example: +79991234567';
$_['help_template'] = 'This field is available for tokens. <a href="#tokensModal" data-toggle="modal" data-target="#tokensModal">Show tokens</a>';
$_['placeholder_template'] = 'For example: New Order #{order_id} on {total} {currency_code}. {store_name}.';
$_['module_disabled_notification'] = '<strong>The module is switched off.</strong> To receive notifications, change the status to "Enabled".';
$_['demo_mode_description'] = 'Demo mode allows you to check the operation of the module, without a real send SMS. Instead, SMS is sent to an e-mail address of the shop:';

// Error
$_['error_permission'] = 'You are not allowed to control this module.';
$_['error_phones'] = 'You must enter at least one phone number.';
$_['error_template'] = 'Enter the SMS message template for a notification.';
$_['error_smsc_login'] = 'You must enter the user\'s login name.';
$_['error_smsc_password'] = 'You must enter a client password.';
$_['error_smsc_charset'] = 'You must select the correct encoding.';

$_['show_tokens_link_text'] = 'Show available tokens';
$_['token_modal_title'] = 'A list of available tokens';
$_['close_btn_text'] = 'Close';
$_['author_label'] = 'Created by';
$_['site_label'] = 'site';
$_['button_save'] = 'Save';

// token descriptions
$_['token_order_id_descrtiption'] = 'Unique order ID in the system.';
$_['token_store_name_descrtiption'] = 'Store name, which made the order.';
$_['token_customer_id_descrtiption'] = 'Buyer unique identifier in the system.';
$_['token_firstname_descrtiption'] = 'First name of the buyer.';
$_['token_lastname_descrtiption'] = 'Last name of the buyer.';
$_['token_email_descrtiption'] = 'E-mail of the buyer.';
$_['token_telephone_descrtiption'] = 'Phone of the buyer.';
$_['token_payment_method_descrtiption'] = 'Payment method.';
$_['token_total_descrtiption'] = 'Order total.';
$_['token_currency_code_descrtiption'] = 'Currency.';