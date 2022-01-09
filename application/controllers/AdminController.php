<?php

namespace application\controllers;

use application\core\Controller;

class AdminController extends Controller
{
    private function isLogin()
    {
        $logout = false;
        if (isset($_SESSION['session_key'])) {
            $curr_ip    = $this->model->get_client_ip();
            $db_session = $this->model->get_key($_SESSION['user_id']);
            $logout     = ($_SESSION['session_key'] != $db_session['authKey']) ? true : false;
            $logout     = ($_SESSION['log_ip'] != $db_session['log_ip']) ? true : false;
        } else {
            $logout = true;
        }
        if ($logout) {
            $this->model->delete_key($this->id);
            session_destroy();
            $this->view->redirect('/');
        }
    }
    public function indexAction()
    {
        $this->isLogin();
        $modes    = $this->model->get_mode();
        $wmp_auto = ($modes['is_wmp_auto'] == '1') ? array('checked', 'actuator-setting') : array('', 'hidden');
        $fmt_auto = ($modes['is_fmt_auto'] == '1') ? array('checked', 'actuator-setting') : array('', 'hidden');
        $led_auto = ($modes['is_led_auto'] == '1') ? array('checked', 'actuator-setting') : array('', 'hidden');
        $vars     = [
            'modes'    => $modes,
            'wmp_auto' => $wmp_auto,
            'fmt_auto' => $fmt_auto,
            'led_auto' => $led_auto,
        ];
        $this->view->render('Dashboard', $vars);
    }
    public function clear_filesAction()
    {
        if ($_POST['auth_key'] == 'afb8-3a028ca910fd') {
            $images = $this->model->del_photo(10000000);
            array_map( 'unlink', array_filter((array) glob("public/uploads/*") ) );
        } else {
            echo "<h1>Access Denied!</h1>";
        }
    }
    public function automateAction()
    {
        $this->isLogin();
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $is_wmp_auto  = filter_input(INPUT_POST, 'is_wmp_auto');
            $is_wmp_auto = ($is_wmp_auto=='on') ? 1 : 0;
            $sens_wmp     = filter_input(INPUT_POST, 'sens_wmp');
            $sens_wmp_val = filter_input(INPUT_POST, 'sens_wmp_val');     
            $is_fmt_auto  = filter_input(INPUT_POST, 'is_fmt_auto');
            $is_fmt_auto  = ($is_fmt_auto=='on') ? 1 : 0;
            $sens_fmt     = filter_input(INPUT_POST, 'sens_fmt');
            $sens_fmt_val = filter_input(INPUT_POST, 'sens_fmt_val');
            $is_led_auto  = filter_input(INPUT_POST, 'is_led_auto');
            $is_led_auto  = ($is_led_auto=='on') ? 1 : 0;
            $sens_led     = filter_input(INPUT_POST, 'sens_led');
            $sens_led_val = filter_input(INPUT_POST, 'sens_led_val');
            $this->model->set_mode($is_wmp_auto, $sens_wmp, $sens_wmp_val, $is_fmt_auto, $sens_fmt, $sens_fmt_val, $is_led_auto, $sens_led, $sens_led_val);
            echo json_encode(array('success' => true, 'message' => 'Successfully saved!'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error occured!'));
        }
    }
    public function logoutAction()
    {
        $this->model->delete_key($this->id);
        session_destroy();
        $this->view->redirect('/signin');
    }
}
