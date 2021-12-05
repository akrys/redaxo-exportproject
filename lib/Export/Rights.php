<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace akrys\redaxo\addon\ProjectExport\Export;

use rex_path;

/**
 * Datei für ...
 *
 * @version       1.0 / 2018-01-28
 * @package       new_package
 * @subpackage    new_subpackage
 * @author        akrys
 */

/**
 * Description of Rights
 *
 * @author akrys
 */
class Rights
	extends Base
{

	/**
	 *
	 * @return $this
	 */
	public function perform(): Base
	{
		// 4. Schritt: Rechte reparieren.
		if ($this->debug) {
			print PHP_EOL.PHP_EOL;
			print 'Step 4: repair all right issues: '.PHP_EOL;
		}

		$this->walkDir(rex_path::frontend('export').'/'.$this->dir);
		return $this;
	}

	/**
	 *
	 * @param string $dirname
	 * @param string $entry
	 * @return bool
	 */
	protected function performDir(string $dirname, string $entry): bool
	{
		$dir = parent::performDir($dirname, $entry);
		if (!$dir) {
			return false;
		}

		$name = $dirname.'/'.$entry;
		if ($this->debug) {
			print "chmod('$name', 0777);//dir".PHP_EOL;
		}
		return chmod($name, 0777);
	}

	/**
	 *
	 * @param string $dirname
	 * @param string $entry
	 * @return bool
	 */
	protected function performFile(string $dirname, string $entry): bool
	{
		$name = $dirname.'/'.$entry;
		if ($this->debug) {
			print "chmod('$name', 0666);//file".PHP_EOL;
		}

		return chmod($name, 0666);
	}
}
