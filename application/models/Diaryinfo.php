<?php
/**
 * Created by PhpStorm.
 * User: qianyucheng
 * Date: 2017/3/7
 * Time: 下午2:56
 */
class Diaryinfo extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database('default');
    }

    public function getDiaryMain($date)
    {
        $start_day = date('Y-m-d', strtotime('-1 Month', strtotime($date)));
        $sql = "select * from `diary_stat` where day between '{$start_day}' and '{$date}' order by day desc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //查询日志
    public function getDiaryInfo($date)
    {
        $yesterday = date('Y-m-d', strtotime('-1 Day', strtotime($date)));
        $sql = "select ios_diary_count+android_diary_count as diary_count from `diary_stat` where day='{$date}' or day='{$yesterday}' group by day order by day limit 2";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //查询日志
    public function getDiaryMonnth($date)
    {
        $month_ago = date('Y-m-d', strtotime('-1 Month', strtotime($date)));
        $sql = "select ios_diary_count, android_diary_count, ios_user_count, android_user_count, day from `diary_stat` where day between '{$month_ago}' and '{$date}' limit 35";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}