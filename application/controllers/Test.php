<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MY_Controller{
	
	public function __construct()
	{
		parent::__construct();

		// Force SSL
		//$this->force_ssl();
	}

	// -----------------------------------------------------------------------

	public function index()
	{
	$this->load->view('testing_api');
	}

}