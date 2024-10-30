<?php defined('WP_UNINSTALL_PLUGIN') OR exit('No direct script access allowed');

if( file_exists( dirname( __FILE__ ). '/vendor/autoload.php' ) ) 
{
	require_once dirname( __FILE__ ). '/vendor/autoload.php';
}

use VIVAHR\Controllers\UninstallController;

	$uninstall = new UninstallController();
    $uninstall->init();