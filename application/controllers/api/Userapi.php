<?php
/**
 * Created by PhpStorm.
 * User: qianyucheng
 * Date: 2017/3/1
 * Time: 下午1:36
 */
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Userapi extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function users_get()
    {

    }

    public function user_get()
    {
        $id = $this->get('id');
        if($id === Null){
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
        }
        $this->load->model('mtest');
        $user = $this->mtest->get_user_info($id);
        if(!empty($user)) {
            $this->response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function user_post()
    {

    }

    public function user_delete()
    {
        $id = $this->get('id');
        if($id === Null){
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
        }
       // $this->load->model('mtest');
        //$status = $this->mtest->delete_user_info($id);
        $status = false;
        $message = [
            'status' => $status,
            'message' => 'Deleted the resource'
        ];
        //$this->response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

    public function user_put()
    {

    }
}

