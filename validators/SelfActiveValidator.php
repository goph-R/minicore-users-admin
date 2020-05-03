<?php

class SelfActiveValidator extends Validator {
    
    /** @var UserSession */
    protected $userSession;
    protected $id;
    
    public function __construct($id) {
        parent::__construct();
        $framework = Framework::instance();
        $this->userSession = $framework->get('userSession');
        $this->id = $id;
        $this->message = $this->translation->get('user-admin', 'cant_inactivate_yourself');
    }
    
    public function doValidate($value) {
        return $value || $this->userSession->getId() != $this->id;
    }
    
}
