<?php

namespace akrys\redaxo\addon\ProjectExport\Export;

use rex_user;
use function mb_strtolower;

/**
 * Datei für ...
 *
 * @version       1.0 / 2018-01-28
 * @author        akrys
 */

/**
 * Description of Base
 *
 * @author akrys
 */
abstract class Base
{

	/**
	 *
	 * @var string
	 */
	protected $dir;

	/**
	 *
	 * @var string
	 */
	protected $debug;

	/**
	 *
	 * @param rex_user $user
	 */
	public function __construct()
	{
		$this->dir = '';
		$this->debug = false;
	}


	/**
	 *
	 * @param string $dir
	 * @return Base
	 */
	public function setDir(string $dir): Base
	{
		$this->dir = $dir;
		return $this;
	}

	/**
	 *
	 * @param bool $bool
	 * @return Base
	 */
	public function setDebug(bool $bool): Base
	{
		$this->debug = $bool;
		return $this;
	}

	/**
	 * @return Base
	 */
	abstract function perform(): Base;

	/**
	 *
	 * @param string $dirname
	 * @param string $entry
	 * @return boolean
	 */
	protected function performDir(string $dirname, string $entry): bool
	{
		switch ($entry) {
			case '.':
			case '..':
				return false;
				break;
		}
		return $this->walkDir($dirname.'/'.$entry);
	}

	/**
	 * @param string $dirname
	 * @param string $entry
	 * @return boolean
	 */
	protected abstract function performFile(string $dirname, string $entry): bool;

	/**
	 *
	 * @param string $dirname
	 * @param string $filename
	 * @return boolean
	 */
	protected function walkDir(string $dirname, string $filename = null): bool
	{
		if (!is_dir($dirname)) {
			return false;
		}

		$dir = dir($dirname);
		if (!$dir) {
			return false;
		}

		while ($entry = $dir->read()) {
			$filename = $dirname.'/'.$entry;
			if (is_dir($filename)) {
				$this->performDir($dirname, $entry);
			} else {
				$this->performFile($dirname, $entry);
			}
		}
		return true;
	}
}
