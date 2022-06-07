<?php

declare(strict_types=1);

namespace PrestaShop\Module\Smsto\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
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
        return $this->render('@Modules/Smsto/views/templates/admin/index.html.twig');
    }
}
