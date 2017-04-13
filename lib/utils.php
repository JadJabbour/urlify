<?php

function get_param($key = null)
{
	if($key == null){
		return '';
	}

	return isset($_REQUEST[$key]) ? trim($_REQUEST[$key]) : '';
}

function load_persistent_db_or_new(){
	return isset($_SESSION["DB"]) ? $_SESSION["DB"] : new usmdb();
}

function update_persistent_db($db){
	return ($_SESSION["DB"] = $db);
}

function die_json($error){
	die(json_encode(['error' => $error]));
}

function bad_request(){
	die_json('Wrong method or bad request');
}