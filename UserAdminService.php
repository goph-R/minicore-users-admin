<?php

class UserAdminService extends AdminService {
    
    public function getRoute() {
        return 'user-admin';
    }
    
    public function getListTitle() {
        return $this->translation->get('user-admin', 'users');
    }
    
    public function getEditTitle() {
        return $this->translation->get('user-admin', 'edit_user');        
    }
    
    public function getCreateTitle() {
        return $this->translation->get('user-admin', 'create_user');        
    }
    
    public function createListView(array $filter) {
        $listView = parent::createListView($filter);
        $listView->setColumns([
            'id'         => ['label' => 'ID', 'align' => 'right'],
            'email'      => ['label' => 'Email'],
            'name'       => ['label' => ['user', 'name']],
            'last_name'  => ['label' => ['user', 'last_name']],
            'first_name' => ['label' => ['user', 'first_name']],
            'active'     => ['label' => ['app', 'active'], 'view' => 'check', 'align' => 'center'],
        ]);
        return $listView;
    }
    
    public function createForm(Record $record) {
        $form = $this->framework->create(['Form', 'data']);
        $form->addInput('Email', ['TextInput', 'email', $record->get('email')]);
        $form->addInput(['user', 'name'], ['TextInput', 'name', $record->get('name')]);
        $form->addInput(['user', 'first_name'], ['TextInput', 'first_name', $record->get('first_name')]);
        $form->addInput(['user', 'last_name'], ['TextInput', 'last_name', $record->get('last_name')]);
        $form->addInput(['user-admin', 'status'], ['CheckboxInput', 'active', '1', ['user-admin', 'active'], $record->get('active')]);
        $form->addInput('', ['SubmitInput', 'submit', $this->translation->get('admin', 'save')]);
        return $form;
    }
    
    public function save(Form $form, Record $record) {
        // $record->setArray($form->getValues());
        // $record->save();
    }
    
}