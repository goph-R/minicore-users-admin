<?php

class RoleAdmin extends Admin {
    
    protected $recordClass = 'Role';
    protected $tableName = 'role';

    public function getSelect($fields='*', array $filter=[]) {
        if ($fields == '*') {
            $fields = "r.id AS id, rt.name AS name";
        }
        $query = "SELECT $fields FROM {$this->tableName} AS r";
        $query .= " JOIN role_text AS rt ON r.id = rt.text_id AND rt.locale = :locale";
        $this->addSqlParams([
            ':locale' => $this->translation->getLocale()
        ]);
        return $query;
    }
    
    protected function getWhere($filter) {
        if (!isset($filter['text']) || !$filter['text']) {
            return '';
        }
        $likeText = '%'.str_replace('%', '\%', $filter['text']).'%';
        $result = " WHERE r.id = :id OR rt.name LIKE :name";
        $this->addSqlParams([
            ':id' => $filter['text'],
            ':name' => $likeText
        ]);
        return $result;
    }
    
    public function findById($id) {
        $query = $this->getSelect();
        $query .= " WHERE r.id = :id  LIMIT 1";
        $this->addSqlParams(['id' => $id]);
        return $this->db->fetch($this->recordClass, $query, $this->sqlParams);
    }

    public function savePermissions($roleId, array $permissionsIds) {
        $this->db->query(
            "DELETE FROM role_permission WHERE role_id = :id", [
            ':id' => $roleId
        ]);
        foreach ($permissionsIds as $permissionId) {
            $this->db->query(
                "INSERT INTO role_permission (role_id, permission_id) VALUES (:role_id, :permission_id)", [
                ':role_id' => $roleId,
                ':permission_id' => $permissionId
            ]);
        }
    }   
}