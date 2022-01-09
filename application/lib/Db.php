<?php

namespace application\lib;

use PDO;

class Db
{
    protected $db;

    public function __construct()
    {
        $config = require 'application/config/db.php';
        try {
            $this->db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['username'], $config['password']);
            $this->db->exec('set names utf8');
        } catch (Exception $e) {
            $error_message = $e->getMessages();
            echo "<p>An error occured while connecting to the database: $error_message </p>";
        }
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $val) {
                $stmt->bindValue(':' . $key, $val);
            }
        }
        $stmt->execute();
        return $stmt;
    }
    public function query_bindint($sql, $params = [], $limit_on = false)
    {
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $val) {
                if ($limit_on) {
                    $stmt->bindValue(':' . $key, $val, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue(':' . $key, $val);
                }
            }
        }
        $stmt->execute();
        return $stmt;
    }
    public function row_index($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchAll();
    }
    public function rows($sql, $params = [], $limit_on = false)
    {
        if ($limit_on) {
            $result = $this->query_bindint($sql, $params, true);
        } else {
            $result = $this->query($sql, $params);
        }
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
    public function row($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
    public function column($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchColumn();
    }
    public function authenticate($post_username, $post_password)
    {
        $stmt = $this->db->prepare('SELECT `id`, `username`, `password` FROM `users` WHERE  `username` = :username');
        $stmt->bindValue(':username', $post_username);
        $stmt->execute();
        if ($stmt) {
        	$result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result !== false) {
            	extract($result);
                if (password_verify($post_password, $password)) {
                    return $result;
                }
            }
        }
        return false;
    }
}