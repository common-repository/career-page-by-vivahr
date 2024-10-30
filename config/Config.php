<?php defined('ABSPATH') OR exit('No direct script access allowed');

$vivahr_config['plugin_name'] = 'vivahr';

$vivahr_config['plugin_version'] = '1.0.5';

$vivahr_config['career_page_shortcode'] = 'vivahr_jobs';

$vivahr_config['vivahr_api_url'] = 'https://auth.vivahr.com/';
//$vivahr_config['vivahr_api_url'] = 'http://api.vivahr.local/';

$vivahr_config['vivahr_app_url'] = 'https://app.vivahr.com/';
//$vivahr_config['vivahr_app_url'] = 'https://app.vivahr.local/';

$vivahr_config['submenu_vivahr'] = [
    'Overview' => [
        'slug'     => 'vivahr_overview',
        'callback' => 'vivahr_overview',
		'type'     => 'menu',
		'position' => 1
    ],
    'Settings' => [
        'slug'     => 'vivahr_settings',
        'callback' => 'vivahr_settings',
		'type'     => 'menu',
		'position'     => 2
    ],
];

$vivahr_config['submenu_non_vivahr'] =  [
	'Overview' => [
        'slug'     => 'vivahr_overview',
        'callback' => 'vivahr_overview',
		'position' => 0,
		'type'     => 'menu'
    ],
    'Job Openings' => [
        'slug'     => 'vivahr_jobs',
        'callback' => '',
		'type'     => 'navbar'
    ],
    'Applications' => [
        'slug'     => 'vivahr_candidates',
        'callback' => '',
		'type'     => 'navbar'
    ],
    'Settings' => [
        'slug'     => 'vivahr_settings',
        'callback' => 'admin_settings',
		'position' => 3,
		'type'     => 'menu'
    ]
];

$vivahr_config['menu_tabs'] = array(
    'general'          => array('name' => 'General'), 
    'job-details'      => array('name' => 'Job Details'), 
    'application-form' => array('name' => 'Application Form'), 
    'notifications'    => array('name' => 'Notifications'), 
    'api-key'          => array('name' => 'VIVAHR API Integration')
);

$vivahr_config['menu_tabs_vivahr'] = array(
    'general'          => array('name' => 'General'), 
    'api-key'          => array('name' => 'VIVAHR API Integration')
);