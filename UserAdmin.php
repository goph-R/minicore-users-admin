<?php

class UserAdmin extends Admin {
    
    protected $recordClass = 'User';
    protected $tableName = 'user';
    
    public function getSelect($fields='*', $filter=[]) {
        if ($fields == '*') {
            $fields = "u.*";
        }
        $query = "SELECT $fields FROM {$this->tableName} AS u";
        if ($filter['roles']) {
            $in = $this->db->getInConditionAndParams($filter['roles']);
            $query .= " JOIN user_role AS ur ON ur.user_id = u.id AND ur.role_id IN (".$in['condition'].")";
            $this->addSqlParams($in['params']);
        }
        return $query;
    }
    
    protected function getWhere($filter) {
        if (!isset($filter['text']) || !$filter['text']) {
            return '';
        }
        $likeText = '%'.str_replace('%', '\%', $filter['text']).'%';
        $result = ' WHERE u.id = :id OR u.name LIKE :name OR';
        $result .= ' u.last_name LIKE :last_name OR u.first_name LIKE :first_name OR';
        $result .= ' u.email LIKE :email';
        $this->addSqlParams([
            ':id' => $filter['text'],
            ':email' => $likeText,
            ':name' => $likeText,
            ':last_name' => $likeText,
            ':first_name' => $likeText,
        ]);
        return $result;
    }
    
}    