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

use Symfony\Component\HttpClient\HttpClient;

/**
 * <ModuleClassName> => Get
 * <FileName> => params.php
 * Format expected: <ModuleClassName><FileName>ModuleFrontController
 */
class SmstoCallModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $api_key = (string) Configuration::get('SMSTO_API_KEY');
        $method = (string) Tools::getValue('_method');
        $url = (string) Tools::getValue('_url');
        $payload = (string) Tools::getValue('payload');
        $response = $this->callSmsto($api_key, $method, $url, $payload);
        echo $response;
        die;
    }

    /**
     * Method to call SMSto api
     * 
     * @author Panayiotis Halouvas <phalouvas@kainotomo.com>
     *
     * @param string $api_key
     * @param string $method
     * @param string $url
     * @param string|array|null $payload
     * @return string
     */
    private function callSmsto(string $api_key, string $method, string $url, $payload = null)
    {

        if ($api_key == '' ) {
            throw new \Exception('No API/Secret key Provided');
        }
        $method = strtoupper($method);

        $curl = curl_init();
        $curlParams = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $api_key",
                'Content-Type: application/json',
                'Accept: application/json',
                'X-Smsto-Integration-Name: prestashop'
            ],
        ];

        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $curlParams[CURLOPT_CUSTOMREQUEST] = $method;
            $curlParams[CURLOPT_POSTFIELDS] = $payload;
        }

        curl_setopt_array($curl, $curlParams);

        $response = curl_exec($curl);

        $err = curl_error($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err) {
            throw new \Exception('Retry again.');
        }

        return $response;
    }
}
