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

class User extends MY_Controller {

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Force encrypted connection
		$this->force_ssl();
	}
public function create_user()
	{
		// Customize this array for your user
		$user_data = array(
			'user_name'     => $this->input->post('user_name'),
			'user_pass'     => $this->input->post('user_pass'),
			'user_email'    => $this->input->post('user_email'),
			'user_level'    => 1,
			'user_id'       => $this->_get_unused_id(),
			'user_salt'     => $this->authentication->random_salt(),
			'user_date'     => time(),
			'user_modified' => time()
		);

		$user_data['user_pass'] = $this->authentication->hash_passwd( $user_data['user_pass'], $user_data['user_salt'] );
		
		$query = $this->db->where('user_email', $this->input->post('user_email'))
			->get_where( config_item('user_table'));

		if( !($query->num_rows() > 0) )
		{
			
			$this->db->set($user_data)
			->insert(config_item('user_table'));

			if( $this->db->affected_rows() == 1 )
			{
				$this->output
				->set_status_header(200)
			    ->set_content_type('application/json')
			    ->set_output( json_encode(array('mesg' =>  str_replace('{0}', $this->input->post('user_emailuser_email'), $this->lang->line('mesg_user_created')), 'code' => '1') ))
			    ->_display();
			    die;
			}
		
		}else{
			
			$this->output
			->set_status_header(200)
		    ->set_content_type('application/json')
		    ->set_output( json_encode(array('mesg' =>  str_replace('{0}', $this->input->post('user_email'), $this->lang->line('error_user_exist')), 'code' => '1') ))
		    ->_display();
		    die;

	}
	
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Get an unused ID for user creation
	 * 
	 * @return  int
	 */
	private function _get_unused_id()
	{
		// Create a random user id
		$random_unique_int = mt_rand(1200,999999999);

		// Make sure the random user_id isn't already in use
		$query = $this->db->where('user_id', $random_unique_int)
			->get_where( config_item('user_table'));

		if( $query->num_rows() > 0 )
		{
			$query->free_result();

			// If the random user_id is already in use, get a new number
			return $this->_get_unused_id();
		}

		return $random_unique_int;
	}
	// --------------------------------------------------------------

	/**
	 * This login method only serves to redirect a user to a 
	 * location once they have successfully logged in. It does
	 * not attempt to confirm that the user has permission to 
	 * be on the page they are being redirected to.
	 */
	public function login()
	{
		// Method should not be directly accessible
		if( $this->uri->uri_string() == 'user/login')
		{
			show_404();
		}

		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' )
		{
			$this->require_min_level(1);
		}

		$this->setup_login_form();
		if( $this->load->get_var('login_error_mesg') )
		{
		//echo $this->load->get_var('login_error_mesg'); die;
		$this->output
		->set_status_header(200)
	    ->set_content_type('application/json')
	    ->set_output( json_encode(array('mesg' =>  $this->lang->line('error_login_failed'), 'code' => 0) ))
	    ->_display();
	    die;
		
		}else{
		
		$this->output
		->set_status_header(200)
	    ->set_content_type('application/json')
	    ->set_output( json_encode(array('mesg' =>  str_replace('{0}', $this->input->post('login_string'), $this->lang->line('mesg_user_logged')), 'code' => 1) ))
	    ->_display();
	    die;

		}

		//$this->load->view( 'auth/login_form' );
	}

	// --------------------------------------------------------------

	/**
	 * Log out
	 */
	public function logout()
	{
		$this->authentication->logout();

		$this->output
		->set_status_header(200)
	    ->set_content_type('application/json')
	    ->set_output( json_encode(array('mesg' =>  $this->lang->line('mesg_user_loggedout'), 'code' => 1) ))
	    ->_display();
	    die;
	}

	// --------------------------------------------------------------

	/**
	 * User recovery form
	 */
	public function recover()
	{
		// Load resources
		$this->load->model('user_model');

		/// If IP or posted email is on hold, display message
		if( $on_hold = $this->authentication->current_hold_status( TRUE ) )
		{
			$view_data['disabled'] = 1;
		}
		else
		{
			// If the form post looks good
			if( $this->tokens->match && $this->input->post('user_email') )
			{
				if( $user_data = $this->user_model->get_recovery_data( $this->input->post('user_email') ) )
				{
					// Check if user is banned
					if( $user_data->user_banned == '1' )
					{
						// Log an error if banned
						$this->authentication->log_error( $this->input->post('user_email', TRUE ) );

						// Show special message for banned user
						$view_data['user_banned'] = 1;
					}
					else
					{
						/**
						 * Use the password generator to create a random string
						 * that will be hashed and stored as the password recovery key.
						 */
						$this->load->library('generate_password');
						$recovery_code = $this->generate_password->set_options( 
							array( 'exclude' => array( 'char' ) ) 
						)->random_string(64)->show();

						$hashed_recovery_code = $this->_hash_recovery_code( $user_data->user_salt, $recovery_code );

						// Update user record with recovery code and time
						$this->user_model->update_user_raw_data(
							$user_data->user_id,
							array(
								'passwd_recovery_code' => $hashed_recovery_code,
								'passwd_recovery_date' => time()
							)
						);

						$view_data['special_link'] = secure_anchor( 
							'user/recovery_verification/' . $user_data->user_id . '/' . $recovery_code, 
							secure_base_url() . 'user/recovery_verification/' . $user_data->user_id . '/' . $recovery_code, 
							'target ="_blank"' 
						);

						$view_data['confirmation'] = 1;
					}
				}

				// There was no match, log an error, and display a message
				else
				{
					// Log the error
					$this->authentication->log_error( $this->input->post('user_email', TRUE ) );

					$view_data['no_match'] = 1;
				}
			}
		}

		$this->load->view( 'user/recover_form', ( isset( $view_data ) ) ? $view_data : '' );
	}

	// --------------------------------------------------------------

	/**
	 * Verification of a user by email for recovery
	 * 
	 * @param  int     the user ID
	 * @param  string  the passwd recovery code
	 */
	public function recovery_verification( $user_id = '', $recovery_code = '' )
	{
		/// If IP is on hold, display message
		if( $on_hold = $this->authentication->current_hold_status( TRUE ) )
		{
			$view_data['disabled'] = 1;
		}
		else
		{
			// Load resources
			$this->load->model('user_model');

			if( 
				/**
				 * Make sure that $user_id is a number and less 
				 * than or equal to 10 characters long
				 */
				is_numeric( $user_id ) && strlen( $user_id ) <= 10 &&

				/**
				 * Make sure that $recovery code is exactly 64 characters long
				 */
				strlen( $recovery_code ) == 64 &&

				/**
				 * Try to get a hashed password recovery 
				 * code and user salt for the user.
				 */
				$recovery_data = $this->user_model->get_recovery_verification_data( $user_id ) )
			{
				/**
				 * Check that the recovery code from the 
				 * email matches the hashed recovery code.
				 */
				if( $recovery_data->passwd_recovery_code == $this->_hash_recovery_code( $recovery_data->user_salt, $recovery_code ) )
				{
					$view_data['user_id']       = $user_id;
					$view_data['user_name']     = $recovery_data->user_name;
					$view_data['recovery_code'] = $recovery_data->passwd_recovery_code;
				}

				// Link is bad so show message
				else
				{
					$view_data['recovery_error'] = 1;

					// Log an error
					$this->authentication->log_error('');
				}
			}

			// Link is bad so show message
			else
			{
				$view_data['recovery_error'] = 1;

				// Log an error
				$this->authentication->log_error('');
			}

			/**
			 * If form submission is attempting to change password 
			 * verify that the user_name was good, because there will only
			 * be a user_name if everything else was good.
			 */
			if( 
				$this->tokens->match && 
				isset( $view_data['user_name'] ) && 
				$view_data['user_name'] !== FALSE 
			)
			{
				$this->user_model->recovery_password_change();
			}
		}

		$this->load->view( 'user/choose_password_form', $view_data );
	}

	// --------------------------------------------------------------

	/**
	 * Hash the password recovery code (uses the authentication library's hash_passwd method)
	 */
	private function _hash_recovery_code( $user_salt, $recovery_code )
	{
		return $this->authentication->hash_passwd( $recovery_code, $user_salt );
	}

	// --------------------------------------------------------------
	public function update_profile()
	{
			$query = $this->db->query("UPDATE users set 
			first_name = '$this->input->post('first_name')',
			last_name = '$this->input->post('last_name')',
			phone = '$this->input->post('phone')' 
			WHERE email = '$this->input->post('email')' ");
			
			$this->output
			->set_status_header(200)
		    ->set_content_type('application/json')
		    ->set_output( json_encode(array('mesg' => $this->lang->line('mesg_profile_updated'), 'code' => 1) ))
		    ->_display();
		    die;
	}

	// --------------------------------------------------------------
	public function update_address()
	{
			$query = $this->db->query("UPDATE users set 
			address = '$this->input->post('address')',
			province = '$this->input->post('province')',
			city = '$this->input->post('city')' 
			WHERE email = '$this->input->post('email')' ");
			
			$this->output
			->set_status_header(200)
		    ->set_content_type('application/json')
		    ->set_output( json_encode(array('mesg' => $this->lang->line('mesg_address_updated'), 'code' => 1) ))
		    ->_display();
		    die;
	}
}

/* End of file users.php */
/* Location: /application/controllers/users.php */