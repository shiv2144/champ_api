<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - User Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class General extends MY_Controller {

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Force encrypted connection
		$this->force_ssl();
	}

	public function get_trades()
	{
		$output = array();
		$query = $this->db->query('SELECT id, name FROM trades');
		if ($query->num_rows() > 0)
		{
	        foreach ($query->result() as $row)
	        {
				$output[$row->id] = $row->name;
			}
		}
		
		$this->output
				->set_status_header(200)
			    ->set_content_type('application/json')
			    ->set_output( json_encode( $output ))
			    ->_display();
			    die;
	}

public function get_jobTypes()
	{
		$output = array();
		$query = $this->db->query('SELECT id, name FROM job_types');
		if ($query->num_rows() > 0)
		{
	        foreach ($query->result() as $row)
	        {
				$output[$row->id] = $row->name;
			}
		}
		
		$this->output
				->set_status_header(200)
			    ->set_content_type('application/json')
			    ->set_output( json_encode( $output ))
			    ->_display();
			    die;
	}

}

/* End of file users.php */
/* Location: /application/controllers/users.php */