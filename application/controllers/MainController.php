<?php

namespace application\controllers;

use application\core\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        if (isset($_GET['date'])) {
            $date    = filter_input(INPUT_GET, 'date');
            $gallery = $this->model->get_images($date);
        } else {
            $date    = '';
            $gallery = $this->model->get_images();
        }
        $wmp_state            = ['state' => '', 'disable' => '', 'icon' => ''];
        $fmt_state            = ['state' => '', 'disable' => ''];
        $led_state            = ['state' => '', 'disable' => ''];
        $actuators            = $this->model->get_actuators();
        $sensors              = $this->model->get_sensors();
        $modes                = $this->model->get_mode();
        $wmp_state['state']   = ($actuators['is_wpmp'] == '1') ? 'checked' : '';
        $fmt_state['state']   = ($actuators['is_fmt'] == '1') ? 'checked' : '';
        $led_state['state']   = ($actuators['is_led'] == '1') ? 'checked' : '';
        $wmp_state['disable'] = ($modes['is_wmp_auto'] == '1') ? 'disabled' : '';
        $wmp_state['icon']    = ($modes['is_wmp_auto'] == '1') ? 'cloud-done-outline' : 'water-outline';
        $fmt_state['disable'] = ($modes['is_fmt_auto'] == '1') ? 'disabled' : '';
        $fmt_state['icon']    = ($modes['is_fmt_auto'] == '1') ? 'cloud-done-outline' : 'nuclear-outline';
        $led_state['disable'] = ($modes['is_led_auto'] == '1') ? 'disabled' : '';
        $led_state['icon']    = ($modes['is_led_auto'] == '1') ? 'cloud-done-outline' : 'flashlight';
        $vars                 = [
            'sensors'   => $sensors,
            'modes'     => $modes,
            'date'      => $date,
            'gallery'   => $gallery,
            'wmp_state' => $wmp_state,
            'fmt_state' => $fmt_state,
            'led_state' => $led_state,
        ];
        $this->view->render('HKBU IoT', $vars);
    }
    public function update_sensorsAction()
    {
        if ($_GET['auth_key'] == '34f47de7-843d-4bf2-942b-136a4ab64244') {
            $sens_tp = filter_input(INPUT_GET, 'sens_tp');
            $sens_ah = filter_input(INPUT_GET, 'sens_ah');
            $sens_sh = filter_input(INPUT_GET, 'sens_sh');
            $sens_li = filter_input(INPUT_GET, 'sens_li');
            $this->model->set_sensors($sens_tp, $sens_ah, $sens_sh, $sens_li);
            $get_actuators       = $this->model->get_actuators();
            $response            = ['is_wpmp' => '0', 'is_fmt' => '0', 'is_led' => '0'];
            $response['is_wpmp'] = $get_actuators['is_wpmp'];
            $response['is_fmt']  = $get_actuators['is_fmt'];
            $response['is_led']  = $get_actuators['is_led'];
            $modes               = $this->model->get_mode();
            if ($modes['is_wmp_auto'] == '1') {
                if ($_GET[$modes['sens_wmp']] > $modes['sens_wmp_val']) {
                    $response['is_wpmp'] = '1';
                } else {
                    $response['is_wpmp'] = '0';
                }
            }
            if ($modes['is_fmt_auto'] == '1') {
                if ($_GET[$modes['sens_fmt']] > $modes['sens_fmt_val']) {
                    $response['is_fmt'] = '1';
                } else {
                    $response['is_fmt'] = '0';
                }
            }
            if ($modes['is_led_auto'] == '1') {
                if ($_GET[$modes['sens_led']] > $modes['sens_led_val']) {
                    $response['is_led'] = '1';
                } else {
                    $response['is_led'] = '0';
                }
            }
            $this->model->set_actuators($response['is_wpmp'], $response['is_fmt'], $response['is_led']);
            echo json_encode($response);
        } else {
            echo "<h1>Access Denied!</h1>";
        }
    }

    public function uploadAction()
    {
        $memory_info   = $this->model->get_memory_info();
        $target_dir    = "public/uploads/";
        $datum         = mktime(date('H') + 0, date('i'), date('s'), date('m'), date('d'), date('y'));
        $name          = date('d', $datum) . ' ' . date('F', $datum) . ' ' . date('Y', $datum);
        $date          = date('Y-m-d', $datum);
        $target_file   = $target_dir . date('YmdHis', $datum) . basename($_FILES["imageFile"]["name"]);
        $file_size     = $_FILES["imageFile"]["size"];
        $uploadOk      = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["imageFile"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($file_size > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file)) {
                $this->model->set_photo($name, $file_size, $target_file, $date);
                if ((1073741824 - $memory_info['total_size']) < 0) {
                    //if exceeds 1gb delete earliest image
                    $no_files = round((1073741824 - $memory_info['total_size']) / $memory_info['average']);
                    $images   = $this->model->del_photo($no_files);
                    foreach ($images as $image) {
                        unlink($image['path']);
                    }
                }
                echo "The file " . basename($_FILES["imageFile"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    public function get_imagesAction()
    {
        if (!empty($_POST) && $_POST['auth_key'] == '4bf2-843d') {
            $date    = filter_input(INPUT_POST, 'date');
            $gallery = $this->model->get_images($date);
            echo json_encode($gallery);
        } else {
            echo "<h1>Access Denied!</h1>";
        }
    }

    public function live_sensorsAction()
    {
        if (!empty($_POST) && $_POST['auth_key'] == '843d-4bf2') {
            $period        = filter_input(INPUT_POST, 'period');
            $chart_sensors = $this->model->get_chart_sensors($period);
            echo json_encode($chart_sensors);
        } else {
            echo "<h1>Access Denied!</h1>";
        }
    }

    public function live_actuatorsAction()
    {
        if ($_POST['auth_key'] == '4bf2-843d') {
            $period          = filter_input(INPUT_POST, 'period');
            $chart_actuators = $this->model->get_chart_actuators($period);
            echo json_encode($chart_actuators);
        } else {
            echo "<h1>Access Denied!</h1>";
        }
    }

    public function update_actuatorAction()
    {
        if (!empty($_POST)) {
            $wmp_val = filter_input(INPUT_POST, 'wmp_val');
            $fmt_val = filter_input(INPUT_POST, 'fmt_val');
            $led_val = filter_input(INPUT_POST, 'led_val');
            $this->model->set_actuators($wmp_val, $fmt_val, $led_val);
            echo ('success');
        } else {
            echo ('error');
        }
    }
}
