<?php
namespace application\models;

use application\core\Model;

class Sign extends Model
{
    public function login($username, $password, $log_ip, $session_key)
    {
        $user = $this->db->authenticate($username, $password);
        if ($user !== false) {
            $isExist = $this->session_exists($user['id']);
            if ($isExist['isExists']) {
                $this->update_session($user['id'], $session_key, $log_ip);
                session_regenerate_id();
                $_SESSION['user_id']     = $user['id'];
                $_SESSION['username']    = $user['username'];
                $_SESSION['session_key'] = $session_key;
                $_SESSION['log_ip']      = $log_ip;
            } else {
                $this->set_session($user['id'], $session_key, $log_ip);
                $_SESSION['user_id']     = $user['id'];
                $_SESSION['username']    = $user['username'];
                $_SESSION['session_key'] = $session_key;
                $_SESSION['log_ip']      = $log_ip;
            }
            return true;
        } else {
            return false;
        }
    }
    private function session_exists($userid)
    {
        $params = [
            'userid' => $userid,
        ];
        $result = $this->db->row('SELECT EXISTS(SELECT * FROM `user_sessions` WHERE `userid` = :userid) as `isExists`', $params);
        return $result;
    }

    public function set_session($user_id, $session_key, $log_ip)
    {
        $params = [
            'user_id'     => $user_id,
            'session_key' => $session_key,
            'log_ip'      => $log_ip,
        ];
        $result = $this->db->query('INSERT INTO `user_sessions` (`userid`, `authKey`, `log_ip`) VALUES (:user_id, :session_key, :log_ip)', $params);
        return $result;
    }

    private function update_session($user_id, $session_key, $log_ip)
    {
        $params = [
            'user_id'     => $user_id,
            'session_key' => $session_key,
            'log_ip'      => $log_ip,
        ];
        $result = $this->db->query('UPDATE `user_sessions` SET `authKey` = :session_key, `log_ip` = :log_ip WHERE `userid` = :user_id', $params);
        return $result;
    }
}
