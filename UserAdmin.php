<?php

class UserAdmin extends Admin {
    
    protected $recordClass = 'User';
    protected $tableName = 'user';
    
    protected function getWhere($filter) {
        if (!isset($filter['text']) || !$filter['text']) {
            return '';
        }
        $likeText = '%'.str_replace('%', '\%', $filter['text']).'%';
        $result = ' WHERE id = :id OR name LIKE :name OR';
        $result .= ' last_name LIKE :last_name OR first_name LIKE :first_name OR';
        $result .= ' email LIKE :email';
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