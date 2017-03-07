<?php

/**
 * Created by PhpStorm.
 * User: qianyucheng
 * Date: 2017/3/7
 * Time: 下午3:36
 */
class Topic extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database('default');
    }
    //查询发帖数
    public function getTopicNum($date)
    {
        $yesterday = date('Y-m-d', strtotime('-1 Day', strtotime($date)));
        $sql = "select sum(post_num) as post_num,day from `post_stat` where day='{$date}' or day='{$yesterday}' group by day order by day limit 2";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function  getTopicChangeNum($date)
    {
        $month_ago = date('Y-m-d', strtotime('-1 Month', strtotime($date)));
        $sql = "select post_num, reply_num, day from `post_stat` where day between '{$month_ago}' and '{$date}' limit 62";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


}
