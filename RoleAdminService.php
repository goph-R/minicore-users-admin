<?php

class RoleAdminService extends AdminService {
    
    public function getRoute() {
        return 'user-admin/role';
    }
    
    public function getTitles() {
        return [
            AdminService::LIST   => ['user-admin', 'roles'],
            AdminService::EDIT   => ['user-admin', 'edit_role'],
            AdminService::CREATE => ['user-admin', 'create_role']
        ];
    }
    
    public function createListView(array $filter) {
        $listView = parent::createListView($filter);
        $listView->setColumns([
            'id'          => ['label' => 'ID', 'align' => 'right', 'width' => '10%'],
            'name'        => ['label' => ['user-admin', 'name'], 'width' => '30%'],
            'permissions' => ['label' => ['user-admin', 'permissions'], 'view' => 'textArray', 'disabled' => true, 'width' => '60%'],
        ]);
        return $listView;
    }
    
    public function createForm(Record $record) {
        $form = $this->framework->create(['Form', 'data']);
        $form->addInput(['user-admin', 'name'], ['TextInput', 'name', $record->get('name')]);
        $form->addInput('', ['SubmitInput', 'submit', $this->translation->get('admin', 'save')]);
        return $form;
    }
    
    public function save(Form $form, Record $record) {
        // $record->setArray($form->getValues());
        // $record->save();
        return true;
    }
    
    public function deleteByIds(array $ids) {
    }
    
}