<?php

/**
 * Created by PhpStorm.
 * User: qianyucheng
 * Date: 2017/2/27
 * Time: 下午4:28
 */
class Mtest extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function get_user_info($id)
    {
        $this->db->where('id',$id);
        $this->db->select('id,username,number');
        $query = $this->db->get('users',1);
        return $query->result_array();
    }

    function get_users_info($page,$limit)
    {
        $offset = (($page>0 ? $page : 1) - 1) * $limit;
        $this->db->select('id,username,number,time');
        $this->db->order_by('id desc');
        //$this->db->limit($limit,$offset);
        $query = $this->db->get('users',$limit,$offset);
        return $query->result_array();
    }

    function update_user_info($id){
        //$this->load->database();
        //$this->db->where('id',$id);
        //$this->db->update('zhangyan',);
    }

    function delete_user_info($id)
    {
        $this->db->where('id',$id);
        $rs = $this->db->delete('users');
        return $rs;
    }

    function insert_user_info($data)
    {
        $this->db->insert('users',$data);
    }

}