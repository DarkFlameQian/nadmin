<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

    function Test(){
        parent::__construct();
    }

    public function index()
	{

	    $this->load->model('mtest');
	    $data['info'] = $this->mtest->get_user_info();
        $this->load->view('test',$data);
        //$aa = $this->load->view('test','',TRUE);
        //echo $aa;
        //$this->load->view('aa');
        //$this->load->helper('url');
	}
    public function aa($a,$b)
    {
        //echo $a;
        //echo $b;
        $this->load->view('aa');
    }
    /*
    public function _remap($method)
    {
     echo 1234;
    }
    */
    /*
    public function _output($output)
    {
        echo $output;
        //echo 123;
    }
    */
}
