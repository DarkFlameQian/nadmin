<?php

/**
 * Created by PhpStorm.
 * User: qianyucheng
 * Date: 2017/3/3
 * Time: 下午2:38
 */
class DailyData extends CI_Model
{
    function __construct()
    {
        parent::__construct();

    }

    public function getTodayData($today)
    {
        $db =  $this->load->database();
        $month_ago = date('Y-m-d', strtotime('-1 Month', strtotime($today)));
        $yesterday = date('Y-m-d', strtotime('-1 Day', strtotime($today)));
//        $tomorrow = date('Y-m-d',strtotime('+1 Day',strtotime($today)));
//        $day_before_yesterday = date('Y-m-d', strtotime('-1 Day', strtotime($yesterday)));
///////////////////////////////////////查询大字数据/////////////////////////////////////////////////////////
        //查询发帖数
        $sql_post = "select sum(post_num) as post_num,day from `post_stat` where day='{$today}' or day='{$yesterday}' group by day order by day limit 2";
        $output_array = $db->get_all($sql_post);
        $post_today = $output_array[1]['post_num'];
        $post_yesterday = $output_array[0]['post_num'];
        //查询用户
        $sql = "select new_users,active_users from `users_stat` where week=0 and (day='{$today}' or day='{$yesterday}') group by day order by day limit 2";
        $tmp = $db->get_all($sql);
        $new_users_today = $tmp[1]['new_users'];
        $new_users_yesterday = $tmp[0]['new_users'];
        $active_users_today = $tmp[1]['active_users'];
        $active_users_yesterday = $tmp[0]['avtive_users'];
        //查询日记
        $sql_diary = "select ios_diary_count+android_diary_count as diary_count from `diary_stat` where day='{$today}' or day='{$yesterday}' group by day order by day limit 2";
        $output_array_diary = $db->get_all($sql_diary);
        $diary_today = $output_array_diary[1]['diary_count'];
        $diary_yesterday = $output_array_diary[0]['diary_count'];
//////////////////////////////////////////////查询表格数据/////////////////////////////////////////////////////////
        //查询发帖
        $form_array = array();
        $sql_post_form = "select post_num, reply_num, day from `post_stat` where day between '{$month_ago}' and '{$today}' limit 62";
        $output_array_post_form = $db->get_all($sql_post_form);
        foreach ($output_array_post_form as $k=>$v) {
            $day = $v['day'];
            $form_array["$day"]['day'] = $day;
            $form_array["$day"]['post'] += $v['post_num'];
            $form_array["$day"]['reply'] += $v['reply_num'];
            $form_array["$day"]['rp-ratio'] = @round($form_array["$day"]['reply'] / $form_array["$day"]['post'], 2);
        }
        //查询用户
        $sql = "select day, new_users, active_users from `users_stat` where day between '{$month_ago}' and '{$today}' limit 40";
        $tmp = $db->get_all($sql);
        foreach ($tmp as $k=>$v) {
            $day = $v['day'];
            $form_array["$day"]['day'] = $day;
            $form_array["$day"]['new_users'] = $v['new_users'];
            $form_array["$day"]['active_users'] = $v['active_users'];
        }
        //查询日记
        $sql_diary_form = "select ios_diary_count, android_diary_count, ios_user_count, android_user_count, day from `diary_stat` where day between '{$month_ago}' and '{$today}' limit 35";
        $output_array_diary_form = $db->get_all($sql_diary_form);
        foreach ($output_array_diary_form as $k=>$v) {
            $day = $v['day'];
            $form_array["$day"]['day'] = $day;
            $form_array["$day"]['diary'] = $v['ios_diary_count'] + $v['android_diary_count'];
            $form_array["$day"]['user'] = $v['ios_user_count'] + $v['android_user_count'];
            $form_array["$day"]['du-ratio'] = round($form_array["$day"]['diary'] / $form_array["$day"]['user'], 2);
        }
        krsort($form_array);
        return array(
            'post_today' => $post_today,
            'post_yesterday' => $post_yesterday,
            'new_users_today' => $new_users_today,
            'new_users_yesterday' => $new_users_yesterday,
            'live_users_today' => $active_users_today,
            'live_users_yesterday' => $active_users_yesterday,
            'diary_today' => $diary_today,
            'diary_yesterday' => $diary_yesterday,
            'form_array' => $form_array
        );
    }
}