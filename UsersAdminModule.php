<?php

class UsersAdminModule extends Module {
    
    protected $id = 'minicore-users-admin';

    public function __construct() {
        $framework = Framework::instance();
        $framework->add([
            'userAdmin' => 'UserAdmin',
            'userAdminService' => ['UserAdminService', 'userAdmin'],
            'userAdminController' => ['AdminController', 'userAdminService'],
            'roleAdmin' => 'RoleAdmin',
            'roleAdminService' => ['RoleAdminService', 'roleAdmin'],
            'roleAdminController' => ['AdminController', 'roleAdminService'],
        ]);
    }
    
    public function init() {
        $framework = Framework::instance();
        /** @var Translation $translation */
        $translation = $framework->get('translation');
        $translation->add('user-admin', 'modules/minicore-users-admin/translations');
        /** @var Router $router */
        $router = $framework->get('router');
        $router->add([
            ['user-admin', 'userAdminController', 'index'],
            ['user-admin/delete', 'userAdminController', 'delete'],
            ['user-admin/edit', 'userAdminController', 'edit', ['GET', 'POST']],
            ['user-admin/create', 'userAdminController', 'create', ['GET', 'POST']],
            ['user-admin/role', 'roleAdminController', 'index'],
            ['user-admin/role/delete', 'roleAdminController', 'delete'],
            ['user-admin/role/edit', 'roleAdminController', 'edit', ['GET', 'POST']],
            ['user-admin/role/create', 'roleAdminController', 'create', ['GET', 'POST']],
        ]);
    }

}