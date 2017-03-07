<?php
/**
 * Created by PhpStorm.
 * User: qianyucheng
 * Date: 2017/3/7
 * Time: 上午11:54
 */
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
// use namespace
use Restserver\Libraries\REST_Controller;

class Diary extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

    }
    public function diary_get()
    {
        $this->load->model('diaryinfo');
        $today = $this->get('date');
        if($today == "") {
            $today = date('Y-m-d');
        }
        $data = $this->diaryinfo->getDiaryMain($today);
        if(!empty($data)) {
            $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $rs = array(
                'status' => FALSE,
                'message' => 'User could not be found'
            );
            $this->set_response($rs, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

}