<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class SMSto extends Module
{
    public function __construct()
    {
        $this->name = 'smsto';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Intergo Telecom Ltd';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => '1.7.99',
        ];
        $this->bootstrap = false;

        parent::__construct();

        $this->displayName = $this->l('SMSto - Integration Module');
        $this->description = $this->l('The SMSto SMS Integration is an integration with the OpenCart e-commerce platform. This Integration enables OpenCart store admins to configure automated SMS notifications to the administrator and customers for important order status updates, and also allows sending bulk SMS messages to customers. The Integration is free, but a SMSto account is required to send messages. Signup with our service is free as well, and you pay only for the SMS messages. The Integration offers great flexibility, in sending individual SMS or bulk SMS messages to various groups.
        
        No contracts, no commitments, pay only for what you use. In case of high volume SMS API usage, our sales team is prepared to give out additional discounts.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('SMSTO_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }
}
