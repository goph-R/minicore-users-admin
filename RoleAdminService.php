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
            'id' => [
                'label' => 'ID',
                'align' => 'right',
                'width' => '10%'
            ],
            'name' => [
                'label' => ['user-admin', 'name'],
                'width' => '30%'
            ],
            'permissions' => [
                'label' => ['user-admin', 'permissions'],
                'view' => 'textArray',
                'disabled' => true,
                'width' => '60%'
            ],
        ]);
        return $listView;
    }
    
    public function createForm(Record $record) {
        $form = $this->framework->create(['Form', 'data']);
        $this->createNameInput($form, $record);
        $this->createPermissionsInput($form, $record);
        $form->addInput('', ['SubmitInput', 'submit', text('admin', 'save')]);
        return $form;
    }
    
    private function createNameInput(Form $form, Record $record) {
        foreach ($this->translation->getAllLocales() as $locale) {
            $defaultValue = $record->getLocalizedText($locale, 'name');
            $input = $form->addInput(
                ['user-admin', 'name'],
                ['TextInput', 'name_'.$locale, $defaultValue]
            );
            $input->setLocale($locale);
        }        
    }

    private function createPermissionsInput(Form $form, Record $record) {
        $permissions = $this->framework->get('permissions');
        $namesByIds = [];
        foreach ($permissions->findAll() as $permission) {
            $namesByIds[$permission->getId()] = $permission->getName();
        }
        $checkedIds = [];
        foreach ($record->getPermissions() as $permission)  {
            $checkedIds[] = $permission->getId();
        }
        /** @var CheckboxGroupInput $input */
        $input = $form->addInput(
            ['user-admin', 'permissions'],
            ['CheckboxGroupInput', 'permissions', $namesByIds, $checkedIds]
        );
        $input->setMustValidate(true);
        $form->addValidator('permissions', ['AdministratorPermissionValidator', $record->getId()]);
    }
    
    public function save(Form $form, Record $record) {
        $localizedValues = $form->getValues(true);
        $record->save($localizedValues);
        $values = $form->getValues();
        $this->admin->savePermissions($record->getId(), $values['permissions']);
        return true;
    }
    
    public function deleteByIds(array $ids) {
        if (in_array(Roles::ADMINISTRATOR_ID, $ids)) {
            $this->setListErrorMessage(['user-admin', 'cant_delete_admin_role']);
        } else {
            parent::deleteByIds($ids);
        }
    }
    
}