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
        $this->author = 'Firstname Lastname';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => '1.7.99',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('My module');
        $this->description = $this->l('Description of my module.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }
}
