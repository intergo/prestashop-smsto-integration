<?php

declare(strict_types=1);

namespace PrestaShop\Module\Smsto\Controller\Admin;

use Configuration;
use Context;
use Customer;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Tools;
use Validate;

class IndexController extends FrameworkBundleAdminController
{
    const TAB_CLASS_NAME = 'AdminSmstoControllerIndexTab';

    /**
     * @AdminSecurity("is_granted('read', 'AdminSmstoControllerIndexTab')")
     *
     * @return Response
     */
    public function indexAction()
    {
        $to = null;
        $active_tab = null;

        $addresses = [];
        $customer_ids = Tools::getValue('customer_customers_bulk');
        if (is_array($customer_ids)) {
            foreach ($customer_ids as $customer_id) {
                $customer = new Customer((int) $customer_id);
                if (Validate::isLoadedObject($customer)) {
                    $addresses = array_merge($addresses, $customer->getSimpleAddresses());
                }
            }
            $phones = [];
            foreach ($addresses as $address) {
                if (!empty($address['phone'])) {
                    $phones[] = $address['phone'];
                }
                if (!empty($address['phone_mobile'])) {
                    $phones[] = $address['phone_mobile'];
                }
            }
            if (!empty($phones)) {
                $active_tab = 'pasted';
                $to = urlencode(json_encode($phones));
            }
        }
        return $this->render('@Modules/smsto/views/templates/admin/index.html.twig', ['to' => $to, 'active_tab' => $active_tab]);
    }

    /**
     *
     * @return Response
     */
    public function iframeAction()
    {
        $client = HttpClient::createForBaseUri('https://integration.sms.to');
        $response = $client->request('GET', '/component_bulk_sms/manifest.json');
        $manifest = json_decode($response->getContent(), true);
        
        $to = Tools::getValue('to') ? json_decode(Tools::getValue('to'), true) :  '';
        if (!empty($to)) {
            $to = implode($to, PHP_EOL);
        }
        $params = [
            'script_main' => $manifest['src/main.ts']['file'],
            'asset_main' => $manifest['src/main.ts']['css'][0],
            'VITE_ROUTE_PARAMS' => Context::getContext()->link->getModuleLink('smsto', 'params'),
            'VITE_ROUTE_SMSTO' => Context::getContext()->link->getModuleLink('smsto', 'call'),
            'sender_id' => Configuration::get('SMSTO_SENDER_ID'),
            'active_tab' => Tools::getValue('active_tab') ? Tools::getValue('active_tab') :  'single',
            'to' => $to,
        ];
        
        return $this->render('@Modules/smsto/views/templates/admin/iframe.html.twig', $params);
    }
}
