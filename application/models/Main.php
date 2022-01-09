<?php

namespace application\models;

use application\core\Model;

class Main extends Model
{
    public function get_actuators()
    {
        $result = $this->db->row('SELECT `is_wpmp`, `is_fmt`, `is_led` FROM `actuators` ORDER BY `date` DESC LIMIT 1');
        return $result;
    }

    public function get_sensors()
    {
        $result = $this->db->row('SELECT `temperature`, `air_humidity`, `soil_humidity`, `light_intensity` FROM `sensors` ORDER BY `date` DESC LIMIT 1');
        return $result;
    }
    public function get_mode()
    {
        $result = $this->db->row('SELECT `is_wmp_auto`, `sens_wmp`, `sens_wmp_val`, `is_fmt_auto`, `sens_fmt`, `sens_fmt_val`, `is_led_auto`, `sens_led`, `sens_led_val`, `date` FROM `mode` ORDER BY `date` DESC LIMIT 1');
        if($result==false){
            $this->db->query('INSERT INTO `mode`(`is_wmp_auto`, `is_fmt_auto`, `is_led_auto`) VALUES (0, 0, 0)');
        }
        $result = $this->db->row('SELECT `is_wmp_auto`, `sens_wmp`, `sens_wmp_val`, `is_fmt_auto`, `sens_fmt`, `sens_fmt_val`, `is_led_auto`, `sens_led`, `sens_led_val`, `date` FROM `mode` ORDER BY `date` DESC LIMIT 1');
        return $result;
    }
    public function get_chart_actuators($period = '4')
    {
        $periods = array(
            '1' => array(32),
            '2' => array(16),
            '3' => array(8));
        $params = [
            'period' => $periods[$period][0],
        ];
        $result = $this->db->rows('SELECT `is_wpmp`, `is_fmt`, `is_led`, `date` FROM `actuators` ORDER BY `date` DESC LIMIT :period', $params, true);
        return array_reverse($result);
    }
    public function set_photo($name, $size, $dir, $date)
    {
        $params = [
            'name' => $name,
            'size' => $size,
            'dir' => $dir,
            'date' => $date,
        ];
        $result = $this->db->query('INSERT INTO `gallery`(`name`, `size`, `path`, `date`) VALUES (:name, :size, :dir, :date)', $params);
    }
    public function get_images($date=null)
    {
        if (is_null($date)) {
            $result = $this->db->rows('SELECT `id`, `name`, `size`, `path`, `date` FROM `gallery` ORDER BY `id` DESC LIMIT 24');
        } else {
            $params = [
                'date' => $date,
            ];
            $result = $this->db->rows('SELECT `id`, `name`, `size`, `path`, `date` FROM `gallery` WHERE DATE(`date`) = :date ORDER BY `id` DESC LIMIT 24', $params);
        }
        return array_reverse($result);
    }
    public function get_memory_info()
    {
        $result = $this->db->row('SELECT SUM(`size`) as total_size, AVG(`size`) as average FROM `gallery`');
        return $result;
    }
    public function del_photo($no_files)
    {
        $params = [
            'no_files' => $no_files,
        ];
        $result = $this->db->rows('SELECT `path` FROM `gallery` ORDER BY `id` ASC LIMIT :no_files; DELETE FROM `gallery` ORDER BY `id` ASC LIMIT :no_files', $params, true);
    }   
    public function get_chart_sensors($period = '4')
    {
        $periods = [
        	'1' => "SELECT ROUND(AVG(`temperature`), 2) as `temperature`, ROUND(AVG(`air_humidity`), 2) as `air_humidity`, ROUND(AVG(`soil_humidity`), 2) as `soil_humidity`, ROUND(AVG(`light_intensity`), 2) as `light_intensity`, DATE_FORMAT(`date`, '%m') as `date` FROM `sensors` GROUP BY DATE_FORMAT(`date`, '%m') ORDER BY `date` DESC",
        	'2' => "SELECT ROUND(AVG(`temperature`), 2) as `temperature`, ROUND(AVG(`air_humidity`), 2) as `air_humidity`, ROUND(AVG(`soil_humidity`), 2) as `soil_humidity`, ROUND(AVG(`light_intensity`), 2) as `light_intensity`, DATE_FORMAT(`date`, '%d') as `date` FROM `sensors` GROUP BY DATE_FORMAT(`date`, '%d') ORDER BY `date` DESC LIMIT 31",
        	'3' => "SELECT ROUND(AVG(`temperature`), 2) as `temperature`, ROUND(AVG(`air_humidity`), 2) as `air_humidity`, ROUND(AVG(`soil_humidity`), 2) as `soil_humidity`, ROUND(AVG(`light_intensity`), 2) as `light_intensity`, DATE_FORMAT(`date`, '%d') as `date` FROM `sensors` GROUP BY DATE_FORMAT(`date`, '%d') ORDER BY `date` DESC LIMIT 7",
        	'4' => "SELECT `temperature`, `air_humidity`, `soil_humidity`, `light_intensity`, `date` FROM `sensors` ORDER BY `date` DESC LIMIT 24;",
        ];
        $result = $this->db->rows($periods[$period]);
        return array_reverse($result);
    }

    public function set_actuators($wmp_val, $fmt_val, $led_val)
    {
        $date = date('Y-m-d H:i:s');
        $params = [
            'wmp_val' => $wmp_val,
            'fmt_val' => $fmt_val,
            'led_val' => $led_val,
            'date' => $date,
        ];
        $this->db->query('INSERT INTO `actuators`(`is_wpmp`, `is_fmt`, `is_led`, `date`) VALUES (:wmp_val, :fmt_val, :led_val, :date)', $params);
    }

    public function set_sensors($sens_tp, $sens_ah, $sens_sh, $sens_li)
    {
        $date = date('Y-m-d H:i:s');
        $params = [
            'sens_tp' => $sens_tp,
            'sens_ah' => $sens_ah,
            'sens_sh' => $sens_sh,
            'sens_li' => $sens_li,
            'date' => $date,
        ];
        $this->db->query('INSERT INTO `sensors`(`temperature`, `air_humidity`, `soil_humidity`, `light_intensity`, `date`) VALUES (:sens_tp, :sens_ah, :sens_sh, :sens_li, :date)', $params);
    }

}
