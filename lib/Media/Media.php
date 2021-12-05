<?php

/**
 * Datei für die Media-Erweiterung
 *
 * @version       1.0 / 2018-02-25
 * @package       new_package
 * @subpackage    new_subpackage
 * @author        akrys
 */
namespace akrys\redaxo\addon\ProjectExport\Media;

/**
 * Description of Media
 *
 * @author akrys
 */
class Media
	extends \rex_media
{
	/**
	 *
	 * @var \rex_article
	 */
	private $article;

	/**
	 *
	 * @var bool
	 */
	private $export = false;

	/**
	 * Konstruktor
	 */
	public function __construct()
	{
		if (rex_get('export')) {
			$this->export = true;
		}
	}

	/**
	 *
	 * @param bool $export
	 * @return string
	 */
	public function getUrl(bool $export = null): string
	{
		$url = parent::getUrl();

		if ($export === null) {
			$export = $this->export;
		}

		if ($export) {
			$destination = '/media';
			$url = $destination.'/'.$this->getFileName();
		}

		return $url;
	}

	/**
	 *
	 * @param \rex_article $article
	 */
	public function setArticle(\rex_article $article)
	{
		$this->article = $article;
	}

	/**
	 *
	 * @return bool
	 */
	public function export(): bool
	{
		$dir = rex_get('dir', 'string', '');

		$destination = \rex_path::frontend('export/'.$dir.'/media');

		if (!file_exists($destination)) {
			mkdir($destination, 0777, true);
		}

		if (copy(\rex_path::frontend('media').'/'.$this->getFileName(), $destination.'/'.$this->getFileName())) {
			return true;
		}
		return false;
	}

	/**
	 *
	 * @param string $name
	 * @throws \Exception
	 * @return \akrys\redaxo\addon\ProjectExport\Media\Media
	 */
	public static function get(string $name): Media
	{
		$obj = parent::get($name);
		if (!$obj) {
			throw new MediaException('no media object possible. Name: "'.$name.'"');
		}
		return $obj;
	}
}
