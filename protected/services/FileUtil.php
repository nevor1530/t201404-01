<?php
class FileUtil {
	public static function mkdirs($dir, $mode = 0777) {
		if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
		if (!self::mkdirs(dirname($dir), $mode)) return FALSE;
		return @mkdir($dir, $mode);
	}
}
?>
