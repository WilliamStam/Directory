<?php

class folder {
	function __construct(){
		
	}
	public static function _tree($dir){
		return listFolders($dir);
	}
}
function listFolders($dir,$path=""){
	$dh = scandir($dir);
	$return = array();

	foreach ($dh as $folder) {
		if ($folder != '.' && $folder != '..') {
			if (is_dir($dir . '/' . $folder)) {
				
				$return[] = array(
					'path' => $path . "/" . $folder,
					'text' => $folder,
					'children' => listFolders($dir . '/' . $folder, $path . "/" . $folder)
				);
			}
		}
	}
	return $return;
}