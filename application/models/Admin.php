<?php

namespace application\models;

use application\core\Model;

class Admin extends Model
{
    public function get_mode()
    {
        $result = $this->db->row('SELECT `is_wmp_auto`, `sens_wmp`, `sens_wmp_val`, `is_fmt_auto`, `sens_fmt`, `sens_fmt_val`, `is_led_auto`, `sens_led`, `sens_led_val`, `date` FROM `mode` ORDER BY `date` DESC LIMIT 1');
        if($result==false){
            $this->db->query('INSERT INTO `mode`(`is_wmp_auto`, `is_fmt_auto`, `is_led_auto`) VALUES (0, 0, 0)');
        }
        $result = $this->db->row('SELECT `is_wmp_auto`, `sens_wmp`, `sens_wmp_val`, `is_fmt_auto`, `sens_fmt`, `sens_fmt_val`, `is_led_auto`, `sens_led`, `sens_led_val`, `date` FROM `mode` ORDER BY `date` DESC LIMIT 1');
        return $result;
    }
    public function del_photo($no_files)
    {
        $params = [
            'no_files' => $no_files,
        ];
        $result = $this->db->rows('SELECT `path` FROM `gallery` ORDER BY `id` ASC LIMIT :no_files; DELETE FROM `gallery` ORDER BY `id` ASC LIMIT :no_files', $params, true);
    }  
    public function set_mode($is_wmp_auto, $sens_wmp, $sens_wmp_val, $is_fmt_auto, $sens_fmt, $sens_fmt_val, $is_led_auto, $sens_led, $sens_led_val)
    {
        $params = [
            'is_wmp_auto' => $is_wmp_auto,
            'sens_wmp' => $sens_wmp,
            'sens_wmp_val' => $sens_wmp_val,
            'is_fmt_auto' => $is_fmt_auto,
            'sens_fmt' => $sens_fmt,
            'sens_fmt_val' => $sens_fmt_val,
            'is_led_auto' => $is_led_auto,
            'sens_led' => $sens_led,
            'sens_led_val' => $sens_led_val,
        ];
        $this->db->query('INSERT INTO `mode`(`is_wmp_auto`, `sens_wmp`, `sens_wmp_val`, `is_fmt_auto`, `sens_fmt`, `sens_fmt_val`, `is_led_auto`, `sens_led`, `sens_led_val`) VALUES (:is_wmp_auto, :sens_wmp, :sens_wmp_val, :is_fmt_auto, :sens_fmt, :sens_fmt_val, :is_led_auto, :sens_led, :sens_led_val)', $params);
    }
}

?>