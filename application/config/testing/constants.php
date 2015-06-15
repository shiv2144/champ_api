<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

// SMTP
define('SMTP_HOST', 'smtp.mandrillapp.com');
define('SMTP_USER', 'support@wholenewhome.com');
define('SMTP_PASS', 'k6EgIRAv5COrlcQBAijU4g');
define('SMTP_PORT', '587');

// DB Constants
define('DB_HOSTNAME', 'test1');
define('DB_USERNAME', 'sa');
define('DB_PASSWORD', 'woofer8');
define('DB_DATABASE', 'pdt_test');

define('ADMIN_URL', 'https://test1.wholenewhome.com/webadmin/');
define('BASE_URL', 'https://test1.wholenewhome.com/');
define('BASE_DIR', '/workspace/propertydealtoday');

// partial directory definitions
define('PRODUCT_COVER_IMG_DIR', '/uploads/products/');
define('POST_COVER_IMG_DIR', '/uploads/blog/');

define('SALES_EMAIL', 'enquiries@wholenewhome.com');
define('PRODUCTS_EMAIL', 'products@wholenewhome.com');

// production
define('ENV', 'test');
define('COOKIE_NAME', 'wnh_test');

define('MINIFY_ASSETS', true);
define('CI_QUERY_LOGGING', TRUE);	//enables codeigniter query logging(php mysqli queries are not logged)

/* End of file constants.php */
/* Location: ./application/config/constants.php */
?>