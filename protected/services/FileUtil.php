<?php
class FileUtil {
	/*
	 * create directorys recursively 
	 */
	public static function mkdirs($dir, $mode = 0777) {
		if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
		if (!self::mkdirs(dirname($dir), $mode)) return FALSE;
		return @mkdir($dir, $mode);
	}
	
	public static function generateUniqueFilename($dir, $ext = "") {
		$filename = date('Ymdhis').rand(100,999) . "." . $ext;
		while (file_exists($dir . $filename)) {
			$filename = date('Ymdhis').rand(100,999) . "." . $ext;
		}
		return $filename;
	}
	
	// TODO: delete file
	public static function deleteFile($filePath) {
		
	}
}
?>
