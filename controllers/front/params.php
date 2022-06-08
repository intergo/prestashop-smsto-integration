<?php

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
