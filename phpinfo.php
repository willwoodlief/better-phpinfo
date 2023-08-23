<?php
/*
 * Plugin Name: Better PHP Info
 * Description: Shows php info to admins
 * Version:     1.1.2
 * Author: Stanimir Stoyanov slightly modified by Will Woodlief
 * Text Domain: better-php-info
*/

namespace Php_Info;

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'PHPINFO_DIR' ) ) {
	define( 'PHP_INFO_DIR' , dirname( __FILE__ ) );
}

require_once  "src/Info.php";

new Info();