<?php

declare(strict_types=1);

namespace PrestaShop\Module\Smsto\Controller\Admin;

use Configuration;
use Context;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Tools;

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
        return $this->render('@Modules/smsto/views/templates/admin/index.html.twig');
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
        $params = [
            'script_main' => $manifest['src/main.ts']['file'],
            'asset_main' => $manifest['src/main.ts']['css'][0],
            'VITE_ROUTE_PARAMS' => Context::getContext()->link->getModuleLink('smsto', 'params'),
            'VITE_ROUTE_SMSTO' => Context::getContext()->link->getModuleLink('smsto', 'call'),
            'sender_id' => Configuration::get('SMSTO_SENDER_ID'),
            'active_tab' => Tools::getValue('active_tab') ? Tools::getValue('active_tab') :  'single',
            'to' => Tools::getValue('to') ? Tools::getValue('to') :  '',
        ];
        return $this->render('@Modules/smsto/views/templates/admin/iframe.html.twig', $params);
    }
}
