<?php
ob_start();
date_default_timezone_set("Asia/Manila");

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'save_folder'){
	$save = $crud->save_folder();
	if($save)
		echo $save;
}
if($action == 'delete_folder'){
	$delete = $crud->delete_folder();
	if($delete)
		echo $delete;
}
if($action == 'delete_file'){
	$delete = $crud->delete_file();
	if($delete)
		echo $delete;
}
if($action == 'save_files'){
	$save = $crud->save_files();
	if($save)
		echo $save;
}
if($action == 'file_rename'){
	$save = $crud->file_rename();
	if($save)
		echo $save;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}

if($action == 'save_project'){
	$save = $crud->save_project();
	if($save)
		echo $save;
}
if($action == 'delete_project'){
	$save = $crud->delete_project();
	if($save)
		echo $save;
}
if($action == 'save_task'){
	$save = $crud->save_task();
	if($save)
		echo $save;
}
if($action == 'delete_task'){
	$save = $crud->delete_task();
	if($save)
		echo $save;
}
ob_end_flush();
?>