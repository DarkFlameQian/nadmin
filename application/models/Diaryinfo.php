<?php
/**
 * Created by PhpStorm.
 * User: qianyucheng
 * Date: 2017/3/7
 * Time: 下午2:56
 */
class Mtest extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getDiaryMain($date)
    {
        $this->load->database('default');
        $start_day = date('Y-m-d', strtotime('-1 Month', strtotime($date)));
        $sql = "select * from `diary_stat` where day between '{$start_day}' and '{$date}' order by day desc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}