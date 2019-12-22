<?php

class UserAdminService extends AdminService {
    
    public function getRoute() {
        return 'user-admin';
    }
    
    public function getTitles() {
        return [
            AdminService::LIST   => ['user-admin', 'users'],
            AdminService::EDIT   => ['user-admin', 'edit_user'],
            AdminService::CREATE => ['user-admin', 'create_user']
        ];
    }
    
    public function createListView(array $filter) {
        $listView = parent::createListView($filter);
        $listView->setColumns([
            'id'         => ['label' => 'ID', 'width' => '10%', 'align' => 'right'],
            'email'      => ['label' => 'Email', 'width' => '30%'],
            'name'       => ['label' => ['user', 'name'], 'width' => '10%'],
            'last_name'  => ['label' => ['user', 'last_name'], 'width' => '12%'],
            'first_name' => ['label' => ['user', 'first_name'], 'width' => '12%'],
            'roles'      => ['label' => ['user-admin', 'roles'], 'view' => 'textArray', 'disabled' => true, 'width' => '16%'],
            'active'     => ['label' => ['user-admin', 'active'], 'view' => 'check', 'align' => 'center', 'width' => '10%'],
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
        return true;
    }
    
    public function deleteByIds(array $ids) {
        if (in_array($this->userSession->get('id'), $ids)) {
            $this->setListErrorMessage(['user-admin', 'cant_delete_yourself']);
        } else {
            parent::deleteByIds($ids);
        }
    }
    
}