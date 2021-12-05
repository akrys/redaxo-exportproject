<?php

namespace akrys\redaxo\addon\ProjectExport\Export;

use akrys\redaxo\addon\ProjectExport\Export\Assets;
use akrys\redaxo\addon\ProjectExport\Export\Delete;
use akrys\redaxo\addon\ProjectExport\Export\Export;
use akrys\redaxo\addon\ProjectExport\Export\Rights;
use akrys\redaxo\addon\ProjectExport\Export\Sitemap;
use akrys\redaxo\addon\ProjectExport\Export\Zip;
use Exception;
use rex_user;

/**
 * Datei für ...
 *
 * @version       1.0 / 2018-01-28
 * @author        akrys
 */

/**
 * Description of Export
 *
 * @author akrys
 */
class Export
{
	/**
	 *
	 * @var bool
	 */
	protected $debug = false;


	/**
	 *
	 * @param bool $bool
	 * @return Export
	 */
	public function setDebug(bool $bool): Export
	{
		$this->debug = $bool;
		return $this;
	}

	/**
	 *
	 * @return $this
	 */
	public function delete(): Export
	{
		$obj = new Delete();
		$obj->setDebug($this->debug)
			->perform();
		return $this;
	}

	/**
	 *
	 * @return $this
	 */
	public function assets(): Export
	{
		$obj = new Assets();
		$obj->setDebug($this->debug)
			->perform();
		return $this;
	}

	/**
	 *
	 * @return $this
	 */
	public function sitemap(): Export
	{
		$obj = new Sitemap();
		$obj->setDebug($this->debug)
			->perform();
		return $this;
	}

	/**
	 *
	 * @return $this
	 */
	public function rights(): Export
	{
		$obj = new Rights();
		$obj->setDebug($this->debug)
			->perform();
		return $this;
	}

	/**
	 *
	 * @return $this
	 */
	public function zip(bool $downloadZip): Export
	{
		try {
			$obj = new Zip();
			$obj->setDebug($this->debug)
				->perform()
				->download($downloadZip);
		} catch (Exception $e) {
			print 'Error: '.$e->getMessage();
		}
		return $this;
	}

	/**
	 *
	 * @return $this
	 */
	public function export($downloadZip = false): Export
	{
		return $this->delete()
				->assets()
				->sitemap()
				->rights()
				->zip($downloadZip);
	}
}
