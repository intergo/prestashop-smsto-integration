<?php

use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction;

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once dirname(__FILE__) . '/SmsAlert.php';

class SMSto extends Module
{
    public function __construct()
    {
        $this->name = 'smsto';
        $this->tab = 'advertising_marketing';
        $this->version = '1.0.0';
        $this->author = 'Intergo Telecom Ltd';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => '1.7.99',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('SMSto - Integration Module');
        $this->description = $this->l('The SMSto SMS Integration is an integration with the PrestaShop e-commerce platform. This Integration enables PrestaShop store admins to configure automated SMS notifications to the administrator and customers for important order status updates, and also allows sending bulk SMS messages to customers. The Integration is free, but a SMSto account is required to send messages. Signup with our service is free as well, and you pay only for the SMS messages. The Integration offers great flexibility, in sending individual SMS or bulk SMS messages to various groups.');

        $this->tabs = [
            [
                'route_name' => 'ps_controller_smsto_index_tab',
                'class_name' => 'AdminSmstoControllerIndexTab',
                'visible' => true,
                'name' => 'SMSto',
                'icon' => 'sms',
                'parent_class_name' => 'IMPROVE',
            ],
        ];

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('SMSTO_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }

    public function install()
    {
        $this->_clearCache('*');

        return parent::install()
        && Configuration::updateValue('SMSTO_SHOW_REPORTS', 1)
        && Configuration::updateValue('SMSTO_SHOW_PEOPLE', 1)
        && Configuration::updateValue('SMSTO_NEW_ORDER', 1)
        && $this->registerHook('displayHome')
        && $this->registerHook('actionCustomerGridDefinitionModifier')
        && $this->registerHook('actionValidateOrder');
    }

    public function uninstall()
    {
        $this->_clearCache('*');

        return parent::uninstall()
        && Configuration::deleteByName('SMSTO_SHOW_REPORTS')
        && Configuration::deleteByName('SMSTO_SHOW_PEOPLE')
        && Configuration::deleteByName('SMSTO_NEW_ORDER');
    }

    /**
     * This method handles the module's configuration page
     * @return string The page's HTML content 
     */
    public function getContent()
    {
        $output = '';

        // this part is executed only when the form is submitted
        if (Tools::isSubmit('submit' . $this->name)) {
            $value = (string) Tools::getValue('SMSTO_API_KEY');
            if (empty($value)) {
                $output = $this->displayError($this->l('Invalid API key value'));
            } else {
                Configuration::updateValue('SMSTO_API_KEY', $value);
                $output = $this->displayConfirmation($this->l('Settings updated'));
            }

            $value = (string) Tools::getValue('SMSTO_SENDER_ID');
            if (!Validate::isGenericName($value)) {
                $output = $this->displayError($this->l('Invalid Sender value'));
            } else {
                Configuration::updateValue('SMSTO_SENDER_ID', $value);
                $output = $this->displayConfirmation($this->l('Settings updated'));
            }

            $value = (string) Tools::getValue('SMSTO_PHONE');
            if (!Validate::isPhoneNumber($value)) {
                $output = $this->displayError($this->l('Invalid Phone value'));
            } else {
                Configuration::updateValue('SMSTO_PHONE', $value);
                $output = $this->displayConfirmation($this->l('Settings updated'));
            }

            $value = (bool) Tools::getValue('SMSTO_SHOW_REPORTS');
            if (!Validate::isBool($value)) {
                $output = $this->displayError($this->l('Invalid Show Reports value'));
            } else {
                Configuration::updateValue('SMSTO_SHOW_REPORTS', $value);
                $output = $this->displayConfirmation($this->l('Settings updated'));
            }

            $value = (bool) Tools::getValue('SMSTO_SHOW_PEOPLE');
            if (!Validate::isBool($value)) {
                $output = $this->displayError($this->l('Invalid Show People value'));
            } else {
                Configuration::updateValue('SMSTO_SHOW_PEOPLE', $value);
                $output = $this->displayConfirmation($this->l('Settings updated'));
            }

            $value = (bool) Tools::getValue('SMSTO_NEW_ORDER');
            if (!Validate::isBool($value)) {
                $output = $this->displayError($this->l('Invalid Show People value'));
            } else {
                Configuration::updateValue('SMSTO_NEW_ORDER', $value);
                $output = $this->displayConfirmation($this->l('Settings updated'));
            }
        }

        // display any message, then the form
        return $output . $this->displayForm();
    }

    /**
     * Builds the configuration form
     * @return string HTML code
     */
    public function displayForm()
    {
        // Init Fields form array
        $form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                ],
                'input' => [
                    [
                        'type' => 'password',
                        'label' => $this->l('API key'),
                        'desc' => 'To make successful API requests, you need a <a href="https://support.sms.to/en/support/solutions/articles/43000571250-account-creation-verification" target="_blank">verified account on SMS.to</a> and to authorize the API calls using your api key.<br>You can generate, retrieve and manage your <em>API keys</em> or <em>Client IDs &amp; Secrets</em> in your <a href="https://sms.to/app" target="_blank">SMS.to dashboard</a> under the <a href="https://sms.to/app#/api/client" target="_blank">API Clients</a> section.',
                        'name' => 'SMSTO_API_KEY',
                        'required' => true,
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Sender'),
                        'desc' => 'The displayed value of who sent the message <a href="https://intergo.freshdesk.com/a/solutions/articles/43000513909" target="_blank">More info</a>',
                        'name' => 'SMSTO_SENDER_ID',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Phone'),
                        'desc' => 'The phone to receive the SMS notifications upon new orders. Please add a full number e.g. +35799999999999, or leave empty if you do not want to receive notifications when a new order is placed.',
                        'name' => 'SMSTO_PHONE',
                        'placeholder' => '+35799999999999'
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Show Reports'),
                        'name' => 'SMSTO_SHOW_REPORTS',
                        'is_bool' => true,
                        'default' => true,
                        'values' => [
                            [
                                'id' => 'SMSTO_SHOW_REPORTS_on',
                                'value' => 1,
                                'label' => $this->trans('Yes', [], 'Admin.Global')
                            ],
                            [
                                'id' => 'SMSTO_SHOW_REPORTS_off',
                                'value' => 0,
                                'label' => $this->trans('No', [], 'Admin.Global')
                            ]
                        ]
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Show Contacts & Lists'),
                        'name' => 'SMSTO_SHOW_PEOPLE',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'SMSTO_SHOW_PEOPLE_on',
                                'value' => 1,
                                'label' => $this->trans('Yes', [], 'Admin.Global')
                            ],
                            [
                                'id' => 'SMSTO_SHOW_PEOPLE_off',
                                'value' => 0,
                                'label' => $this->trans('No', [], 'Admin.Global')
                            ]
                        ]
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('New Order Notification'),
                        'desc' => $this->l('Send SMS notifications upon new order'),
                        'name' => 'SMSTO_NEW_ORDER',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'SMSTO_NEW_ORDER_on',
                                'value' => 1,
                                'label' => $this->trans('Yes', [], 'Admin.Global')
                            ],
                            [
                                'id' => 'SMSTO_NEW_ORDER_off',
                                'value' => 0,
                                'label' => $this->trans('No', [], 'Admin.Global')
                            ]
                        ]
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                ],
            ],
        ];

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->table = $this->table;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
        $helper->submit_action = 'submit' . $this->name;

        // Default language
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');

        // Load current value into the form
        $helper->fields_value['SMSTO_API_KEY'] = Tools::getValue('SMSTO_API_KEY', Configuration::get('SMSTO_API_KEY'));
        $helper->fields_value['SMSTO_SENDER_ID'] = Tools::getValue('SMSTO_SENDER_ID', Configuration::get('SMSTO_SENDER_ID'));
        $helper->fields_value['SMSTO_PHONE'] = Tools::getValue('SMSTO_PHONE', Configuration::get('SMSTO_PHONE'));
        $helper->fields_value['SMSTO_SHOW_REPORTS'] = Tools::getValue('SMSTO_SHOW_REPORTS', Configuration::get('SMSTO_SHOW_REPORTS'));
        $helper->fields_value['SMSTO_SHOW_PEOPLE'] = Tools::getValue('SMSTO_SHOW_PEOPLE', Configuration::get('SMSTO_SHOW_PEOPLE'));
        $helper->fields_value['SMSTO_NEW_ORDER'] = Tools::getValue('SMSTO_NEW_ORDER', Configuration::get('SMSTO_NEW_ORDER'));

        return $helper->generateForm([$form]);
    }

    /**
     * This hook displays a new block on the admin customer page
     *
     * @param array $params
     *
     * @return string
     */
    public function hookActionCustomerGridDefinitionModifier(array $params)
    {
        // $params['definition'] is instance of \PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinition
        $params['definition']->getBulkActions()->add(
                (new SubmitBulkAction('send_sms'))
                    ->setName('SMS')
                    ->setOptions([
                        // in most cases submit action should be implemented by module
                        'submit_route' => 'ps_controller_smsto_index_tab',
                    ]) 
            );
    }

    /**
     * After an order has been validated. Doesn’t necessarily have to be paid.
     *
     * @param array $params
     * @return void
     */
    public function hookActionValidateOrder($params)
    {
        if (!(bool) Configuration::get('SMSTO_NEW_ORDER')) {
            return;
        }

        // Getting differents vars
        $context = Context::getContext();
        $id_lang = (int) $context->language->id;

        $id_shop = (int) $context->shop->id;
        $order = $params['order'];
        $customer = $params['customer'];
        $PS_SHOP_NAME = Configuration::get('PS_SHOP_NAME', $id_lang, null, $id_shop);
        $delivery = new Address((int) $order->id_address_delivery);
        $invoice = new Address((int) $order->id_address_invoice);
        $order_state = $params['orderStatus'];

        // customer notification
        $api_key = (string) Configuration::get('SMSTO_API_KEY');
        $phones = [];        
        $delivery_phone = $delivery->phone ? $delivery->phone : $delivery->phone_mobile;
        if (!empty($delivery_phone)) {
            $phones[] = $delivery_phone;
        }
        $invoice_phone = $invoice->phone ? $invoice->phone : $invoice->phone_mobile;
        if (!empty($invoice_phone)) {
            $phones[] = $invoice_phone;
        }
        if (empty($phones)) {
            return;
        }
        $to = $phones[0];
        if (count($phones) > 1) {
            $to = $phones;
        }
        $order_history = Context::getContext()->link->getPageLink(
            'my-account',
            true,
            $id_lang,
            null,
            false,
            $id_shop
        );
        $payload = [
            'to' => $to,
            'sender_id' => (string) Configuration::get('SMSTO_SENDER_ID'),
            'message' => "Hi $customer->firstname $customer->lastname," . PHP_EOL .
                "Thank you for shopping on $PS_SHOP_NAME" . PHP_EOL . PHP_EOL .
                "Order details" . PHP_EOL .
                "Order: $order->reference" . PHP_EOL .
                "Status: $order_state->name" . PHP_EOL .
                "Follow your order and download your invoice on our shop. Go to $order_history"
        ];
        SmsAlert::callSmsto($api_key, 'POST', 'https://api.sms.to/sms/send', $payload);

        //admin notification
        $admin_phone = (string) Configuration::get('SMSTO_PHONE');
        if (!empty($admin_phone)) {
            $payload = [
                'to' => $admin_phone,
                'sender_id' => (string) Configuration::get('SMSTO_SENDER_ID'),
                'message' => "You have a new order on $PS_SHOP_NAME" . PHP_EOL .
                    "Order details" . PHP_EOL .
                    "Order: $order->reference" . PHP_EOL .
                    "Status: $order_state->name"
            ];
            SmsAlert::callSmsto($api_key, 'POST', 'https://api.sms.to/sms/send', $payload);
        }
    }

    public function getAllMessages($id)
    {
        $messages = Db::getInstance()->executeS('
			SELECT `message`
			FROM `' . _DB_PREFIX_ . 'message`
			WHERE `id_order` = ' . (int) $id . '
			ORDER BY `id_message` ASC');
        $result = [];
        foreach ($messages as $message) {
            $result[] = $message['message'];
        }

        return implode('<br/>', $result);
    }

}
