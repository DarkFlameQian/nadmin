<?php

/**
 * Created by PhpStorm.
 * User: qianyucheng
 * Date: 2017/3/7
 * Time: 下午5:29
 */
class Users extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database('default');
    }

    public function getUsersNum($date)
    {
        $yesterday = date('Y-m-d', strtotime('-1 Day', strtotime($date)));
        $sql = "select new_users,active_users from `users_stat` where week=0 and (day='{$date}' or day='{$yesterday}') group by day order by day limit 2";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getUsersChangeNum($date)
    {
        $month_ago = date('Y-m-d', strtotime('-1 Month', strtotime($date)));
        $sql = "select day, new_users, active_users from `users_stat` where day between '{$month_ago}' and '{$date}' limit 40";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}