<?php

class UserAdminService extends AdminService {
    
    /** @var UserAdmin */
    protected $admin;
    
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
    
    /**
     * @return Form
     */
    public function createFilterForm() {
        $framework = Framework::instance();
        /** @var Form $form */
        $form = parent::createFilterForm();
        /** @var Roles $roles */
        $roles = $framework->get('roles');
        $namesByIds = [];
        foreach ($roles->findAll() as $role) {
            $namesByIds[$role->getId()] = $role->getName();
        }
        $defaultValue = $this->getFilterFromSession('roles', []);
        $form->addInput(null, ['CheckboxGroupInput', 'roles', $namesByIds, $defaultValue]);        
        /** @var View $view */
        $view = $framework->get('view');
        $view->addScript('/modules/minicore-users-admin/static/role-filter-checkboxes.js');
        return $form;
    }    
    
    public function showRemoveFilter(array $filter) {
        return $filter['text'] || $filter['roles'];
    }
    
    protected function removeFilter() {
        $filter = [
            'text' => '',
            'roles' => []
        ];
        return $filter;
    }    
    
    public function getDefaultOrderBy() {
        return 'email';
    }
    
    public function getDefaultOrderDir() {
        return 'asc';
    }
    
    public function createListView(array $filter) {
        $listView = parent::createListView($filter);
        $listView->setColumns([
            'email' => [
                'label' => 'Email',
                'width' => '40%'
            ],
            'name' => [
                'label' => ['user', 'name'],
                'width' => '10%'
            ],
            'first_name' => [
                'label' => ['user', 'first_name'],
                'width' => '12%'
            ],
            'last_name' => [
                'label' => ['user', 'last_name'],
                'width' => '12%'
            ],
            'roles' => [
                'label' => ['user-admin', 'roles'],
                'view' => 'textArray',
                'disabled' => true,
                'width' => '16%'
            ],
            'active' => [
                'label' => ['user-admin', 'active'],
                'view' => 'check',
                'align' => 'center',
                'width' => '10%'
            ],
        ]);
        return $listView;
    }
    
    public function createForm(Record $record) {
        $framework = Framework::instance();
        $form = $framework->create(['Form', 'data']);
        $form->addInput('Email', ['TextInput', 'email', $record->get('email')]);
        /** @var TextInput $passwordInput */
        $pwDescription = $record->isNew() ? null : ['user', 'set_if_change_password'];
        $passwordInput = $form->addInput(
            ['user', 'password'],
            ['TextInput', 'password'],
            $pwDescription
        );
        $passwordInput->setRequired($record->isNew());
        $passwordInput->setAutocomplete(false);
        $form->addInput(
            ['user', 'name'],
            ['TextInput', 'name', $record->get('name')]
        );
        $form->addInput(
            ['user', 'first_name'],
            ['TextInput', 'first_name', $record->get('first_name')]
        );
        $form->addInput(
            ['user', 'last_name'],
            ['TextInput', 'last_name', $record->get('last_name')]
        );
        /** @var CheckboxInput $activeInput */
        $activeInput = $form->addInput(
            ['user-admin', 'status'],
            ['CheckboxInput', 'active', '1', ['user-admin', 'active'], $record->get('active')]
        );
        $activeInput->setMustValidate(true);
        $form->addValidator('active', ['SelfActiveValidator', $record->getId()]);
        $this->createRolesInput($form, $record);
        $form->addInput('', ['SubmitInput', 'submit', text('admin', 'save')]);
        return $form;
    }
    
    private function createRolesInput(Form $form, Record $record) {
        $framework = Framework::instance();
        $roles = $framework->get('roles');
        $namesByIds = [];
        foreach ($roles->findAll() as $role) {
            $namesByIds[$role->getId()] = $role->getName();
        }
        $checkedIds = [];
        foreach ($record->getRoles() as $role)  {
            $checkedIds[] = $role->getId();
        }
        /** @var CheckboxGroupInput $rolesInput */
        $rolesInput = $form->addInput(
            ['user-admin', 'roles'],
            ['CheckboxGroupInput', 'roles', $namesByIds, $checkedIds]
        );
        $rolesInput->setMustValidate(true);
        $form->addValidator('roles', ['SelfAdministratorValidator', $record->getId()]);
    }
    
    public function save(Form $form, Record $record) {
        $values = $form->getValues();
        $keysForSave = ['email', 'name', 'first_name', 'last_name', 'active'];
        $record->setArray($values, $keysForSave);
        if ($values['password']) {
            $hashedPassword = $this->userService->hashPassword($values['password']);
            $record->setPassword($hashedPassword);
        }
        $record->save();
        $this->admin->saveRoles($record->getId(), $values['roles']);
        return true;
    }
    
    public function deleteByIds(array $ids) {
        if (in_array($this->userSession->getId(), $ids)) {
            $this->setListErrorMessage(['user-admin', 'cant_delete_yourself']);
        } else {
            parent::deleteByIds($ids);
        }
    }
    
}