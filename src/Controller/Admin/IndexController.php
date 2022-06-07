<?php

declare(strict_types=1);

namespace PrestaShop\Module\Smsto\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;

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
        //$VITE_ROUTE_PARAMS =  Uri::root() . "index.php?option=com_smsto&task=smsto.getParams";
        //$VITE_ROUTE_SMSTO =  Uri::root() . "index.php?option=com_smsto&task=smsto.callSmsto";
        $params = [
            'script_main' => $manifest['src/main.ts']['file'],
            'asset_main' => $manifest['src/main.ts']['css'][0],
            'VITE_ROUTE_PARAMS' => 'aaaaaaaa',
            'VITE_ROUTE_SMSTO' => 'bbbbbbbb',
        ];
        return $this->render('@Modules/smsto/views/templates/admin/iframe.html.twig', $params);
    }
}
