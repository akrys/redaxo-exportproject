<?php

namespace akrys\redaxo\addon\ProjectExport\Export;

use rex_path;

/**
 * Datei für ...
 *
 * @version       1.0 / 2018-01-28
 * @author        akrys
 */

/**
 * Description of Delete
 *
 * @author akrys
 */
class Delete
	extends Base
{

	/**
	 *
	 * @param string $dirname
	 * @param string $entry
	 * @return string
	 */
	protected function performFile(string $dirname, string $entry): bool
	{
		$filename = $dirname.'/'.$entry;
		if (file_exists($filename)) {
			if ($this->debug) {
				print "unlink($filename)".PHP_EOL;
			}
			unlink($filename);
		}
		return true;
	}

	/**
	 *
	 * @param string $dirname
	 * @param string $entry
	 * @return bool
	 */
	protected function performDir(string $dirname, string $entry): bool
	{
		$dir = $dirname.'/'.$entry;
		if (parent::performDir($dirname, $entry)) {
			if (is_dir($dir)) {
				if ($this->debug) {
					print "rmdir($dir)".PHP_EOL;
				}
				return rmdir($dir);
			}
		}
		return false;
	}

	/**
	 *
	 * @return Base
	 */
	public function perform(): Base
	{
		//1. Schritt: alte Daten löschen
		if ($this->debug) {
			print 'Step 1: deleting all export files: '.PHP_EOL;
		}

		$this->walkDir(rex_path::frontend('export').'/'.$this->dir);
		return $this;
	}
}
