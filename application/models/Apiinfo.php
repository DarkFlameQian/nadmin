<?php

/**
 * Created by PhpStorm.
 * User: qianyucheng
 * Date: 2017/3/8
 * Time: 上午11:44
 */
class Apiinfo extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database('default');
    }

    public function getTodayApi($date)
    {
        $sql = "select day,api_name,sum(count) as num_ios,plat as num_android from `api_stat` where day='{$date}' group by day,api_name,plat";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function showApiVersion($name,$date)
    {
        $sql = "select plat,version,sum(count) as version_num from `api_stat` where day='{$date}' and api_name='{$name}' group by version,plat";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function showApiDetail($name,$start,$end)
    {
        $sql = "select day,sum(count) as ios_num,plat as android_num from `api_stat` where api_name='{$name}' and day between '{$start}' and '{$end}' group by day,plat";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}