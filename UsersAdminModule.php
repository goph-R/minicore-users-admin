<?php

class UsersAdminModule extends Module {
    
    /** @var Framework */
    protected $framework;
    protected $id = 'minicore-users-admin';

    public function __construct(Framework $framework) {
        parent::__construct($framework);
        $this->framework = $framework;
        $this->framework->add([
            'userAdmin' => 'UserAdmin',
            'userAdminService' => ['UserAdminService', 'userAdmin', 'user-admin'],
            'userAdminController' => ['AdminController', 'userAdminService']
        ]);
    }
    
    public function init() {
        /** @var Translation $translation */
        $translation = $this->framework->get('translation');
        $translation->add('user-admin', 'modules/minicore-users-admin/translations');
        /** @var Router $router */
        $router = $this->framework->get('router');
        $router->add([
            ['user-admin', 'userAdminController', 'index'],
            ['user-admin/delete', 'userAdminController', 'delete'],
            ['user-admin/edit', 'userAdminController', 'edit', ['GET', 'POST']],
            ['user-admin/create', 'userAdminController', 'create', ['GET', 'POST']]
        ]);
    }

}