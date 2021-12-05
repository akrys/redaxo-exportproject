<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2018-01-28
 * @author        akrys
 */
namespace akrys\redaxo\addon\ProjectExport;

/**
 * Description of SaveContent
 *
 * @author akrys
 */
class SaveContent
{
	/**
	 *
	 * @var string
	 */
	private $url;

	/**
	 *
	 * @var string
	 */
	private $dir;

	/**
	 *
	 * @var string
	 */
	private $content;

	/**
	 *
	 * @param string $url
	 * @param string $url
	 * @param string $content
	 */
	public function __construct(string &$url, string &$dir, string &$content)
	{
		$this->url = $url;
		$this->dir = rtrim($dir);
		$this->content = $content;
	}

	/**
	 * @return void
	 */
	public function saveToFile()
	{
		$exportPath = \rex_path::frontend('export'.'/'.$this->dir);

		if (preg_match('#/$#', $this->url)) {
			$path = $exportPath.'/'.$this->url.'index.html';
		} else {
			$path = $exportPath.'/'.$this->url;
		}

		$dir = dirname($path);
		if (!file_exists($dir) || !is_dir($dir)) {
			mkdir($dir, 0777, true);
			chmod($dir, 0777);
		}

		file_put_contents($path, $this->content);
		chmod($path, 0777);
	}
}
