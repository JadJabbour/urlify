<?php

header('Content-type: application/json');

//verify config file exists
define('CONFIG_FILE', realpath(__DIR__ . '/inc/config.php'));
if (!file_exists(CONFIG_FILE)){
	die_json('Configuration file not found');
}

//require needed libs
require_once(CONFIG_FILE);
require_once(realpath(__DIR__ . '/lib/Hashids.php'));
require_once(realpath(__DIR__ . '/lib/usmdb.php'));
require_once(realpath(__DIR__ . '/lib/utils.php'));
require_once(realpath(__DIR__ . '/lib/urlify.php'));

//init session
session_start();

//load dataset from session if available or create new
$db = load_persistent_db_or_new();

//getting the action and data - returning error in case of bad request format
switch($_SERVER['REQUEST_METHOD']){
	case 'POST':
		if(get_param('create') == 'true'){
			$action = 'create';
			$data = [
				'short_url' => get_param('short_url'),
				'text' => get_param('text')
			];
		}
		if(get_param('remove') == 'true'){
			$action = 'remove';
			$data = [
				'short_url' => get_param('id')
			];
		}
		break;
	case 'GET':
		if(get_param('stats') == 'true'){
			$action = 'stats';
			$data = [];
		}
		if(get_param('get') == 'true'){
			$action = 'get';
			$data = [
				'short_url' => get_param('id')
			];
		}
		if(get_param('all') == 'true'){
			die($db->json());
		}
		break;
	default:
		bad_request();
}

//if action is not null, instantiate URLify and get output otherwise return error
echo isset($action) ? (new URLify($action, $data, $db))->output('json') : bad_request();

//update session var
if(!update_persistent_db($db)){
	die_json('A server error has occured');
}

exit;