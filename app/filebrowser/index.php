<?php

date_default_timezone_set('Africa/Johannesburg');
setlocale(LC_MONETARY, 'en_ZA');
//ini_set('memory_limit', '256M');
if (session_id() == "") {
	$SID = @session_start();
} else $SID = session_id();
if (!$SID) {
	session_start();
	$SID = session_id();
}

require_once('../../vendor/autoload.php');

$f3 = \base::instance();

require_once('../../inc/timer.php');
require_once('../../inc/functions.php');

require_once('template.php');
require_once('folder.php');

$cfg = array();
require_once('../../config.default.inc.php');
if (file_exists("../../config.inc.php")) {
	require_once('../../config.inc.php');
}

$f3->set('DB', new DB\SQL('mysql:host=' . $cfg['DB']['host'] . ';dbname=' . $cfg['DB']['database'] . '', $cfg['DB']['username'], $cfg['DB']['password']));
$f3->set('cfg', $cfg);
$f3->set('UI', 'ui/|media/');
$f3->set('AUTOLOAD', './|lib/|controllers/|inc/|/modules/');
$f3->set('PLUGINS', 'lib/f3/');

//$media_dir = 'D:\Web\LiN\media\uploads\1';

//$domainID = $f3->get("domain");
$media_dir = '../../media/uploads/';
$media_dir_use = "/media/uploads/";



if (!is_dir($media_dir)){
	mkdir( $media_dir, 0777, true );
}
//test_array(($media_dir)); 

//test_array($domain); 

$folders = folder::_tree($media_dir);

$section = isset($_REQUEST['section']) ? $_REQUEST['section'] : "";
$folder = isset($_REQUEST['path']) ? $_REQUEST['path'] : "/";
$folder_raw = $folder;

//test_array($folder); 
$targetDir = realpath($media_dir . $folder);

if ($section == "files") {

	$targetDir = realpath($media_dir . $folder);

	$sorter = "1";
	if (isset($_REQUEST['sort'])) {
		$sorter = $_REQUEST['sort'];
	}
	$order = "ASC";
	if (isset($_REQUEST['order'])) {
		$order = $_REQUEST['order'];
	}

	$targetDir = $targetDir . DIRECTORY_SEPARATOR;
	$filter = $targetDir;
	$files = array();
	$sort = array();
	foreach (glob($filter . "*", GLOB_BRACE) as $file) {
		//echo $file;
		// if file isn't this directory or its parent, add it to the results

		$filepath = $file;
		$file = explode(DIRECTORY_SEPARATOR, $file);
		$file = $file[count($file) - 1];
		//print_r($file);
		//	echo $file . "<br>";
		if (is_dir($filepath)) {

		} else {
			$imginfo = @getimagesize($filepath);

			$files[] = array("name" => $file, "type" => pathinfo($file, PATHINFO_EXTENSION), "raw_size" => filesize($filepath), "size" => file_size(filesize($filepath)), "a_time" => date("F d Y H:i:s", fileatime($filepath)), "c_time" => date("F d Y H:i:s", filectime($filepath)), "m_time" => date("F d Y H:i:s", filemtime($filepath)), "raw_m_time" => filemtime($filepath), "perms" => fileperms($filepath), "image_w" => $imginfo[0], "image_h" => $imginfo[1], "image_t" => $imginfo[2]);

			switch ($sorter) {
				case 1:
					$sort[] = $file;
					break;

				case 3:
					$sort[] = filesize($filepath);
					break;
				case 4:
					$sort[] = strtolower(pathinfo($file, PATHINFO_EXTENSION));
					break;
				case 20:
					$sort[] = filemtime($filepath);
					break;
				case 21:
					$sort[] = fileatime($filepath);
					break;
				case 22:
					$sort[] = filectime($filepath);
					break;
			}
		}




	}


	//if ()

	array_multisort($sort, ($order == "DESC") ? SORT_DESC : SORT_ASC, $files);





	$output = array("folder" => $folder,

		"files" => $files, "folders" => $folders,);

	test_array($output);

} else if ($section == "thumbnail") {
	require_once('../../vendor/bcosca/fatfree/lib/image.php');
	$file = isset($_GET['file']) ? $_GET['file'] : "";
	$w = isset($_GET['w']) ? $_GET['w'] : "";
	$h = isset($_GET['h']) ? $_GET['h'] : "";



	$thumb = explode(".", $file);
	$thumbExt = $thumb[count($thumb) - 1];

	$file_path = realpath($media_dir . $file);


	//test_array(array("file"=>$file,"ext"=>$thumbExt,"path"=>$file_path)); 
	//$file = "Folder 1/Folder 1.2/Folder 1.2.2/p16mv2glf21jac1tf51egb1nb51t423_thumb.jpg";


	if (file_exists($file_path)) {
		//test_array($file_path); 
		$thumb = new Image($file, false, $media_dir . "/");
		$thumb->resize($w, $h);
		$thumb->render();

	}
	$showthumb = true;

} else if ($section == "do_file_rename") {
	$old = isset($_REQUEST['old']) ? $_REQUEST['old'] : "";
	$new = isset($_REQUEST['new']) ? $_REQUEST['new'] : "";

	if ($new == "") {
		test_array(array("error" => "No filename specified"));
	}
	$targetDir = realpath($media_dir . $folder);

	$old_parts = explode(".", $old);
	$old_ext = $old_parts[count($old_parts) - 1];
	$new_parts = explode(".", $new);
	$new_ext = $new_parts[count($new_parts) - 1];

	if (count($new_parts) <= 1) {
		$new = $new . "." . $old_ext;
	}

	$new = sanitize_filename($new);


	if ($old == $new) {
		test_array(array("error" => "No change detected to the filename"));
	}
	if (!file_exists($targetDir . "/" . $old)) {
		test_array(array("error" => "File doesnt exist: " . $old));
	}

	if (file_exists($targetDir . "/" . $new)) {
		test_array(array("error" => "File with that name already exists"));
	}
	if ($old != $new) {
		rename($targetDir . "/" . $old, $targetDir . "/" . $new);
	}



	test_array(array("folder" => $folder, "old" => $old, "new" => $new));
} else if ($section == "do_file_delete") {
	$file = isset($_REQUEST['file']) ? $_REQUEST['file'] : "";


	if (!file_exists($targetDir . "/" . $file)) {
		test_array(array("error" => "File doesnt exist: " . $file));
	}
	unlink($targetDir . "/" . $file);



	test_array("done");

} else if ($section == "do_folder_new") {
	$new = isset($_REQUEST['new']) ? $_REQUEST['new'] : "";
	$new = sanitize_filename($new);

	if (is_dir($targetDir . "/" . $new)) {
		test_array(array("error" => "Folder with that name already exists"));
	}

	mkdir($targetDir . "/" . $new, 0777);
	
	$new_path = $folder . "/" . $new;
	$new_path = preg_replace('/\/\//i', '/', $new_path);
	$new_path = preg_replace('/\/\//i', '/', $new_path);

	test_array(array("folder" => $new_path, "new" => $new));


} else if ($section == "do_folder_rename") {
	$old = isset($_REQUEST['old']) ? $_REQUEST['old'] : "";
	$new = isset($_REQUEST['new']) ? $_REQUEST['new'] : "";
	$new = sanitize_filename($new);
	if ($new == "") {
		test_array(array("error" => "No folder name specified"));
	}

	//$folder = "/";

	$folder_parent = explode("/", $folder);
	unset($folder_parent[count($folder_parent) - 1]);

	$folder_parent = implode("/", $folder_parent);
	if (!$folder_parent) {
		$folder_parent = "/";
	}
	if ($folder == "/") {
		test_array(array("error" => "Cant rename the root folder"));
	}



	$targetDir = realpath($media_dir . $folder);
	$targetDir_parent = realpath($media_dir . $folder_parent);

	//test_array(array("parent" => $folder_parent, "folder" => $folder, "new" => $new, "old" => $old, "target"=> $targetDir_parent));


	if ($old == $new) {
		test_array(array("error" => "No change detected to the folder name"));
	}


	if (file_exists($targetDir . "/" . $new)) {
		test_array(array("error" => "Folder with that name already exists"));
	}


	rename($targetDir_parent . "/" . $old, $targetDir_parent . "/" . $new);

	$new_path = $folder_parent . "/" . $new;
	$new_path = preg_replace('/\/\//i', '/', $new_path);
	$new_path = preg_replace('/\/\//i', '/', $new_path);

	test_array(array("folder" => $folder, "old" => $old, "new" => $new, "path" => $new_path));

} else if ($section == "do_folder_delete") {

	
	//test_array($folder); 
	if ($folder != "/"){
		
	} else {
		test_array(array("error" => "Cant delete the root folder"));
	}

	if (!file_exists($targetDir)) {
		test_array(array("error" => "Folder doesnt exist: " . $file));
	}


	$folder_parent = explode("/", $folder);
	unset($folder_parent[count($folder_parent) - 1]);

	$folder_parent = implode("/", $folder_parent);
	
	
	
	deleteDir($targetDir);



	test_array(array("path"=>$folder_parent,"folder"=>$folder));

} else if ($section == "upload") {
	
	

	// HTTP headers for no cache etc
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	// Settings


	//$cleanupTargetDir = false; // Remove old files
	//$maxFileAge = 60 * 60; // Temp file age in seconds

	// 5 minutes execution time
	@set_time_limit(5 * 60);

	// Uncomment this one to fake upload time
	// usleep(5000);

	// Get parameters
	$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
	$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
	$fileName = sanitize_filename(isset($_REQUEST["name"]) ? $_REQUEST["name"] : '');

	// Clean the fileName for security reasons
	$fileName = preg_replace('/[^\w\._]+/', '', $fileName);

	// Make sure the fileName is unique but only if chunking is disabled
	if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
		$ext = strrpos($fileName, '.');
		$fileName_a = substr($fileName, 0, $ext);
		$fileName_b = substr($fileName, $ext);

		$count = 1;
		while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b)) $count++;

		$fileName = $fileName_a . '_' . $count . $fileName_b;
	}

	// Create target dir
	if (!file_exists($targetDir)) @mkdir($targetDir);

	// Remove old temp files
	/* this doesn't really work by now

if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
  while (($file = readdir($dir)) !== false) {
	  $filePath = $targetDir . DIRECTORY_SEPARATOR . $file;

	  // Remove temp files if they are older than the max age
	  if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $maxFileAge))
		  @unlink($filePath);
  }

  closedir($dir);
} else
  die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
*/

	// Look for the content type header
	if (isset($_SERVER["HTTP_CONTENT_TYPE"])) $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

	if (isset($_SERVER["CONTENT_TYPE"])) $contentType = $_SERVER["CONTENT_TYPE"];

	// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
	if (strpos($contentType, "multipart") !== false) {
		if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
			// Open temp file
			$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen($_FILES['file']['tmp_name'], "rb");

				if ($in) {
					while ($buff = fread($in, 4096)) fwrite($out, $buff);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				fclose($in);
				fclose($out);
				@unlink($_FILES['file']['tmp_name']);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	} else {
		// Open temp file
		$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = fopen("php://input", "rb");

			if ($in) {
				while ($buff = fread($in, 4096)) fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

			fclose($in);
			fclose($out);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	}


	// Return JSON-RPC response
	die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
	
	
	
	
} else {
	$tmpl = new \template("filebrowser.tmpl", "../../app/filebrowser", true);
	$tmpl->folders = $folders;
	$tmpl->CKEditorFuncNum = (isset($_GET['CKEditorFuncNum'])) ? $_GET['CKEditorFuncNum'] : "";
	$tmpl->media =$media_dir_use;

	$tmpl->output();
}

function sanitize_filename($filename) {

	if ($filename) {
		$filename = preg_replace('/[^a-z0-9_\.\-]/i', '_', $filename);
		$filename = preg_replace('/__/i', '_', $filename);
		$filename = preg_replace('/__/i', '_', $filename);
		$filename = preg_replace('/__/i', '_', $filename);
		$filename = preg_replace('/__/i', '_', $filename);
	}


	return $filename;
}
function deleteDir($dirPath) {
	if (! is_dir($dirPath)) {
		throw new InvalidArgumentException("$dirPath must be a directory");
	}
	if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
		$dirPath .= '/';
	}
	$files = glob($dirPath . '*', GLOB_MARK);
	foreach ($files as $file) {
		if (is_dir($file)) {
			deleteDir($file);
		} else {
			unlink($file);
		}
	}
	rmdir($dirPath);
}

?>
 