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

class Stat extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

    }
    //日记
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
    //每日数据
    public function  diarylist_get()
    {
        $today = $this->get('date');
        if($today == "") {
            $today = date('Y-m-d');
        }

        $this->load->model('diaryinfo');
        $this->load->model('topic');
        $this->load->model('users');

        $tmp1 = $this->diaryinfo->getDiaryMonnth($today);
        $tmp2 = $this->topic->getTopicChangeNum($today);
        $tmp3 = $this->users->getUsersChangeNum($today);

        $form_array = [];
        foreach ($tmp2 as $k=>$v) {
            $day = $v['day'];
            $form_array["$day"]['day'] = $day;
            if(!isset($form_array["$day"]['post'])){
                $form_array["$day"]['post'] = 0;
            }
            if(!isset($form_array["$day"]['reply'])){
                $form_array["$day"]['reply'] = 0;
            }
            $form_array["$day"]['post'] += $v['post_num'];
            $form_array["$day"]['reply'] += $v['reply_num'];
            $form_array["$day"]['rp-ratio'] = @round($form_array["$day"]['reply'] / $form_array["$day"]['post'], 2);
        }

        foreach ($tmp3 as $k=>$v) {
            $day = $v['day'];
            $form_array["$day"]['day'] = $day;
            $form_array["$day"]['new_users'] = $v['new_users'];
            $form_array["$day"]['active_users'] = $v['active_users'];
        }

        foreach ($tmp1 as $k=>$v) {
            $day = $v['day'];
            $form_array["$day"]['day'] = $day;
            $form_array["$day"]['diary'] = $v['ios_diary_count'] + $v['android_diary_count'];
            $form_array["$day"]['user'] = $v['ios_user_count'] + $v['android_user_count'];
            $form_array["$day"]['du-ratio'] = round($form_array["$day"]['diary'] / $form_array["$day"]['user'], 2);
        }

        krsort($form_array);
        $data = [
            'form_array'       => $form_array
        ];
        $this->response($data, REST_Controller::HTTP_OK);
    }
    //每日数据
    public function diarytotal_get()
    {
        $today = $this->get('date');
        if($today == "") {
            $today = date('Y-m-d');
        }

        $this->load->model('diaryinfo');
        $this->load->model('topic');
        $this->load->model('users');

        $tmp1 = $this->diaryinfo->getDiaryInfo($today);
        $tmp2 = $this->topic->getTopicNum($today);
        $tmp3 = $this->users->getUsersNum($today);

        $diary_today = $tmp1[1]['diary_count'];
        $diary_yesterday = $tmp1[0]['diary_count'];

        $post_today = $tmp2[1]['post_num'];
        $post_yesterday = $tmp2[0]['post_num'];

        $new_users_today = $tmp3[1]['new_users'];
        $new_users_yesterday = $tmp3[0]['new_users'];
        $active_users_today = $tmp3[1]['active_users'];
        $active_users_yesterday = $tmp3[0]['active_users'];

        $output_array = [
            'post_today' => $post_today,
            'post_yesterday' => $post_yesterday,
            'new_users_today' => $new_users_today,
            'new_users_yesterday' => $new_users_yesterday,
            'live_users_today' => $active_users_today,
            'live_users_yesterday' => $active_users_yesterday,
            'diary_today' => $diary_today,
            'diary_yesterday' => $diary_yesterday
        ];

        if ($output_array['post_yesterday'] > 0) {
            $post_ratio = 100 * ($output_array['post_today'] - $output_array['post_yesterday']) / $output_array['post_yesterday'];
        } else {
            $post_ratio = "昨日暂无数据";
        }
        if ($output_array['new_users_yesterday'] > 0) {
            $new_users_ratio = 100 * ($output_array['new_users_today'] - $output_array['new_users_yesterday']) / $output_array['new_users_yesterday'];
        } else {
            $new_users_ratio = "昨日暂无数据";
        }
        if ($output_array['live_users_yesterday'] > 0) {
            $live_users_ratio = 100 * ($output_array['live_users_today'] - $output_array['live_users_yesterday']) / $output_array['live_users_yesterday'];
        } else {
            $live_users_ratio = "昨日暂无数据";
        }
        if ($output_array['diary_yesterday'] > 0) {
            $diary_ratio = 100 * ($output_array['diary_today'] - $output_array['diary_yesterday']) / $output_array['diary_yesterday'];
        } else {
            $diary_ratio = "昨日暂无数据";
        }
        $post_ratio = round($post_ratio, 2);
        $new_users_ratio = round($new_users_ratio, 2);
        $live_users_ratio = round($live_users_ratio, 2);
        $diary_ratio = round($diary_ratio, 2);

        $data = [
            'today_post'       => $output_array['post_today'],
            'post_ratio'       => $post_ratio,
            'today_new_users'  => $output_array['new_users_today'],
            'new_users_ratio'  => $new_users_ratio,
            'today_live_users' => $output_array['live_users_today'],
            'live_users_ratio' => $live_users_ratio,
            'today_diary'      => $output_array['diary_today'],
            'diary_ratio'      => $diary_ratio
        ];

        $this->response($data, REST_Controller::HTTP_OK);
    }
    //用户映画
    public function usersmain_get()
    {
        $date = $this->get('date');
        if($date == "") {
            $date = date('Y-m-d');
        }
        $this->load->model('users');
        $data = $this->users->getTodayUsers($date);
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
    //API
    public function apimain_get()
    {
        $date = $this->get('date');
        if($date == "") {
            $date = date('Y-m-d');
        }
        $this->load->model('apiinfo');
        $output_array = $this->apiinfo->getTodayApi($date);
        for ($i = 0; $i < count($output_array)-1; $i++) {
            if ($output_array[$i]['day'] == $output_array[$i+1]['day'] && $output_array[$i]['api_name'] == $output_array[$i+1]['api_name']) {
                $output_array[$i]['num_android'] = $output_array[$i+1]['num_ios'];
                array_splice($output_array,$i+1,1);
            } else {
                if ($output_array[$i]['num_android'] == 21) {
                    $output_array[$i]['num_android'] = $output_array[$i]['num_ios'];
                    $output_array[$i]['num_ios'] = 0;
                } else {
                    $output_array[$i]['num_android'] = 0;
                }
            }
        }
        if ($i < count($output_array)) {
            if ($output_array[$i]['num_android'] == 21) {
                $output_array[$i]['num_android'] = $output_array[$i]['num_ios'];
                $output_array[$i]['num_ios'] = 0;
            } else {
                $output_array[$i]['num_android'] = 0;
            }
        }
        $total = array();
        for ($i = 0;$i < count($output_array);$i++) {
            $output_array[$i]['num_total'] = $output_array[$i]['num_ios'] + $output_array[$i]['num_android'];
            $total[$i] = $output_array[$i]['num_total'];
        }
        array_multisort($total,SORT_DESC,$output_array);
        $this->response($output_array, REST_Controller::HTTP_OK);
    }
    //http://www.ladybirdedu.com/nadmin/index.php/api/stat/apiversion?name=/home&date=2017-03-07&format=json
    //路由比较麻烦
    public function apiversion_get()
    {
        $name = $this->get('name');
        $date = $this->get('date');
        if($name === NULL ) {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
        }
        if($date === NULL) {
            $date = date('Y-m-d');
        }
        $this->load->model('apiinfo');
        $data = $this->apiinfo->showApiVersion($name,$date);
        if(!empty($data)) {
            $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $rs = array(
                'status' => FALSE,
                'message' => 'api could not be found'
            );
            $this->set_response($rs, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function apidetail_get()
    {
        $name = $this->get('name');
        $end = $this->get('end');
        $start = $this->get('start');
        if($name === NULL ) {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
        }
        if($end === NULL) {
            $end = date('Y-m-d');
        }
        $month_ago = date('Y-m-d', strtotime('-1 Month', strtotime($end)));
        if($start === NULL){
            $start = $month_ago;
        }
        $this->load->model('apiinfo');
        $output_array = $this->apiinfo->showApiDetail($name,$start,$end);
        for ($i = 0;$i < count($output_array)-1;$i++){
            if ($output_array[$i]['day'] == $output_array[$i+1]['day']){
                $output_array[$i]['android_num'] = $output_array[$i+1]['ios_num'];
                array_splice($output_array,$i+1,1);
            } else {
                if ($output_array[$i]['android_num'] == 21) {
                    $output_array[$i]['android_num'] = $output_array[$i]['ios_num'];
                    $output_array[$i]['ios_num'] = 0;
                } else {
                    $output_array[$i]['android_num'] = 0;
                }
            }
        }
        if ($i < count($output_array)){
            if ($output_array[$i]['android_num'] == 21) {
                $output_array[$i]['android_num'] = $output_array[$i]['ios_num'];
                $output_array[$i]['ios_num'] = 0;
            } else {
                $output_array[$i]['android_num'] = 0;
            }
        }
        $data = [
            'api_name'        => $name,
            'start'           => $start,
            'end'             => $end,
            'output_json_str' => json_encode($output_array)
        ];
        $this->response($data, REST_Controller::HTTP_OK);
    }


}