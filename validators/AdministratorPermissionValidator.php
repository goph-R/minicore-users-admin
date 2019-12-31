<?php

class AdministratorPermissionValidator extends Validator {
    
    /** @var UserSession */
    protected $id;
    
    public function __construct(Framework $framework, $id) {
        parent::__construct($framework);
        $this->id = $id;
        $this->message = $this->translation->get('user-admin', 'cant_remove_admin_permission');
    }
    
    public function doValidate($value) {
        return in_array(AdminPermissions::ADMINISTRATION, $value) || $this->id != Roles::ADMINISTRATOR_ID;
    }
    
}
