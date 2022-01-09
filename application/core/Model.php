<?php 

namespace application\core;

use application\lib\Db;

abstract class Model 
{
	public $db;

	function __construct()
	{
		$this->db = new Db;
	}
    public function get_client_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    public function delete_key($userid)
    {
        $params = [
            'userid'  => $userid,
        ];
        $result = $this->db->query('DELETE FROM `user_sessions` WHERE `userid` = :userid', $params);
        return $result;
    }

    public function get_key($userid)
    {
        $params = [
            'userid'  => $userid,
        ];
        $result = $this->db->row('SELECT `userid`, `authKey`, `log_ip` FROM user_sessions WHERE userid = :userid', $params);
        return $result;
    }
}

?>