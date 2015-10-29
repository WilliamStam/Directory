<?php


$cfg['email'] = "no-reply@meetpad.org";
$cfg['contact'] = "team@meetpad.org";



$cfg['DB']['host'] = "localhost";
$cfg['DB']['username'] = "";
$cfg['DB']['password'] = "";
$cfg['DB']['database'] = "directory";

$cfg['git'] = array(
	'username'=>"",
	"password"=>"",
	"path"=>"github.com/WilliamStam/Directory.git",
	"branch"=>"master"
);


$cfg['media'] = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR. "media" . DIRECTORY_SEPARATOR;
$cfg['backup'] = $cfg['media'] . "backups" . DIRECTORY_SEPARATOR;

