<?php

namespace akrys\redaxo\addon\ProjectExport\Export;

use akrys\redaxo\addon\ProjectExport\Media\Media;
use Exception;
use rex_path;

/**
 * Datei für ...
 *
 * @version       1.0 / 2018-01-28
 * @author        akrys
 */

/**
 * Description of Assets
 *
 * @author akrys
 */
class Assets
	extends Base
{
	/**
	 *
	 * @var string
	 */
	private $type = '';

	/**
	 * @return Base
	 */
	public function perform(): Base
	{

// 3. Step copy JS / CSS
		if ($this->debug) {
			print PHP_EOL.PHP_EOL;
			print 'Step 3: copy JS / CSS files: '.PHP_EOL;
		}

//		$this->type = 'js';
//		$this->walkDir(\rex_path::frontend($this->type));
//		$this->type = 'css';
//		$this->walkDir(\rex_path::frontend($this->type));

		$this->type = 'media';
		$this->walkDir(rex_path::frontend($this->type));

//		$this->type = 'lib';
//		$this->walkDir(\rex_path::frontend($this->type));

		return $this;
	}

	/**
	 *
	 * @param string $dirname
	 * @param string $entry
	 * @return bool
	 */
	protected function performFile(string $dirname, string $entry): bool
	{
		if (substr($entry, 0, 1) == '.') {
			return false;
		}

		$dir = str_replace(rex_path::frontend(), '', $dirname);
		if ($this->debug) {
			print $dirname.' | '.$entry.PHP_EOL;
		}

		if (!preg_match('/^media/msi', $dir)) {
			$destentry = $entry;
		} else {
			try {
				$media = Media::get($entry);
				$url = $media->getUrl(true);
				$dir = dirname($url);
				$destentry = basename($url);
			} catch (Exception $e) {
				return false;
			}
		}

		$destination = rex_path::frontend('export/'.$this->dir.'/'.$dir);
		if (!file_exists($destination)) {
			mkdir($destination, 0777, true);
		}
		return copy($dirname.'/'.$entry, $destination.'/'.$destentry);
	}
}
