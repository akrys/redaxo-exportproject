<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2018-03-17
 * @author        akrys
 */
namespace akrys\redaxo\addon\ProjectExport\Export;

use Exception;
use rex_path;
use rex_response;
use ZipArchive;

/**
 * Description of Zip
 *
 * @author akrys
 */
class Zip
	extends Base
{
	/**
	 *
	 * @var ZipFile
	 */
	protected $zip;

	/**
	 *
	 * @var string
	 */
	protected $zipfile;

	/**
	 *
	 * @var string
	 */
	protected $baseDir;

	/**
	 *
	 * @param string $dirname
	 * @param string $entry
	 * @return bool
	 */
	protected function performFile(string $dirname, string $entry): bool
	{
		$filename = $dirname.'/'.$entry;
		return $this->zip->addFile($filename, str_replace($this->baseDir, '', $filename));
	}

	/**
	 *
	 * @return Base
	 * @throws Exception
	 */
	public function perform(): Base
	{
		$this->zipfile = rex_path::frontend('export').'/export.zip';
		// 5. Schritt: Zip-Datei erstellen.
		if ($this->debug) {
			print PHP_EOL.PHP_EOL;
			print 'Step 5: zip folder: '.PHP_EOL;
			print 'Filename: '.$this->zipfile.PHP_EOL;
		}

		if (!self::zipInstalled()) {
			throw new Exception('php zip extension not installed. perform `sudo apt-get install php-zip` to install');
		}
		if (file_exists($this->zipfile)) {
			unlink($this->zipfile);
		}

		$this->baseDir = rex_path::frontend().'export/';

		$this->zip = new ZipArchive();
		$this->zip->open($this->zipfile, ZipArchive::CREATE);
		$this->walkDir(rex_path::frontend('export').'/'.$this->dir);
		$this->zip->close();
		return $this;
	}

	/**
	 *
	 * @param bool $download
	 * @return Zip
	 */
	public function download(bool $download): Zip
	{
		if (!$download) {
			return $this;
		}

		rex_response::cleanOutputBuffers();
		header('content-type:application/zip');
		header('Content-Disposition: attachment; filename="export.zip"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');

		readfile($this->zipfile);
		exit(0);
	}

	/**
	 *
	 * @return bool
	 */
	public static function zipInstalled(): bool
	{
		return class_exists('\\ZipArchive');
	}
}
