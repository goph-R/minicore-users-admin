<?php

class SelfAdministratorValidator extends Validator {
    
    /** @var UserSession */
    protected $userSession;
    protected $id;
    
    public function __construct($id) {
        parent::__construct();
        $framework = Framework::instance();
        $this->userSession = $framework->get('userSession');
        $this->id = $id;
        $this->message = $this->translation->get('user-admin', 'cant_remove_admin');
    }
    
    public function doValidate($value) {
        return in_array(Roles::ADMINISTRATOR_ID, $value) || $this->userSession->getId() != $this->id;
    }
    
}
