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

/**
 * <ModuleClassName> => Get
 * <FileName> => params.php
 * Format expected: <ModuleClassName><FileName>ModuleFrontController
 */
class SmstoParamsModuleFrontController extends ModuleFrontController
{
  public function initContent()
  {
    parent::initContent();

    echo json_encode([
      "success" => true,
      "message" => null,
      "messages" => null,
      "data" => [
        "show_reports" => (string) Configuration::get('SMSTO_SHOW_REPORTS'),
        "show_people" => (string) Configuration::get('SMSTO_SHOW_PEOPLE')
      ]
      ]);
      die;
  }
}
