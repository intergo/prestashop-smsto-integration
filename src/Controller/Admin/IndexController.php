<?php
/**
 * Copyright since 2022 Intergo Telecom Ltd
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License 3.0 (OSL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@sms.to so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    Intergo Telecom Ltd <support@sms.to>
 * @copyright Since 2022 Intergo Telecom Ltd
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 */

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
        $ch = curl_init();
        ob_start();
        curl_setopt($ch, CURLOPT_URL, 'https://integration.sms.to/component_bulk_sms/manifest.json');
        $response = curl_exec($ch);
        curl_close($ch);
        $response = ob_get_clean();
        $manifest = json_decode($response, true);
        
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
