<?php
namespace controllers\admin\save;
use models as models;

class _ extends \controllers\admin\_ {
	public $errors;
	function __construct() {
		parent::__construct();
		$this->user = $this->f3->get("user");
		$this->f3->set("__runJSON",true);
		
		
	}
	
	function post($key, $required = false) {
		$val = isset($_POST[$key]) ? $_POST[$key] : "";
		if ($required && $val == "") {
			$this->errors[$key] = $required === true ? "" : $required;
		}
		return $val;
	}
	function uploader($folder,$filename) {
		$result = array();
		$errors = $this->errors;
		
		
				
		$cfg = $this->f3->get("cfg");
		
		
		
		
			
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
		
		
		

// 5 minutes execution time
		@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);

// Settings
		
		
 
		
		$targetDir =  $cfg['media'] . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR ;
//$targetDir = 'uploads';
		//	$cleanupTargetDir = true; // Remove old files
		//$maxFileAge = 5 * 3600; // Temp file age in seconds

	//test_array($targetDir); 

// Create target dir
		if (!file_exists($targetDir)) {
			mkdir($targetDir, 0777, true);
		}

// Get a file name
		if (isset($filename)) {
			$fileName = $filename;
		} elseif (!empty($_FILES)) {
			$fileName = $_FILES["file"]["name"];
		} else {
			$fileName = uniqid("file_");
		}
		
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;



// Open temp file
		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}
		
		if (!empty($_FILES)) {
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
				$return = ('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			}
			
			// Read binary input stream and append it to temp file
			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
				$return = ('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		} else {
			if (!$in = @fopen("php://input", "rb")) {
				$return = ('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		}
		
		while ($buff = fread($in, 4096)) {
			fwrite($out, $buff);
		}
		
		@fclose($out);
		@fclose($in);

// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$filePath}.part", $filePath);
		}

// Return Success JSON-RPC response
		$return = ('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
		
		$return = array(
			"folder"=>$folder,
			"filename"=>$filename, 
			"status"=>json_decode($return)
		);
		
		return $GLOBALS["output"]['data'] = ($return);
	}
}
