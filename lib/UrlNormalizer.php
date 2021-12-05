<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2018-02-03
 * @author        akrys
 */
namespace akrys\redaxo\addon\ProjectExport;

use Exception;
use rex;
use rex_article;
use rex_clang;
use function rex_get;

/**
 * Description of UrlNormalizer
 *
 * @author akrys
 */
class UrlNormalizer
{

	/**
	 *
	 * @param string $url
	 * @return string
	 */
	public static function normalize(string $url): string
	{
		if (rex::isBackend()) {
			$count = -1;
		} else {
			$currentArticle = rex_article::getCurrent(rex_clang::getCurrentId());
			$count = count($currentArticle->getParentTree());

			//Für die Links im Submenü haben eine Ebene mehr. (Die aktuelle Kategorie)
			if ($count > 0 && !$currentArticle->isStartArticle()) {
				print 'ok';
				$count++;
			}
		}

		$urlCounter = 0;
		$parsedServer = parse_url(rex::getServer());

		//falsche hosts filtern (wenn via IP aufgerufen)
		$url = str_replace($parsedServer['scheme'].'://'.$parsedServer['host'], '', $url);
		if (rex_get('export') == true) {
			//wichtig für den Export: Der Pfad zum System muss entfernt werden, damit das Projekt nicht in
			//bestimmten Unterordnern laufen muss.
			print '<pre>';
			var_dump($url, __LINE__, $parsedServer['path']);
			$url = preg_replace('#^'.preg_quote($parsedServer['path'], '#').'#', '', $url);
			var_dump($url, __LINE__);
			$url = preg_replace('#/$#', '/index.html', $url);
			var_dump($url, __LINE__);
			print '</pre>';
			$parsedServer['path'] = '';
		}

		str_replace('/', '', ltrim($parsedServer['path'], '/'), $urlCounter);
		$result = str_repeat('../', $count + $urlCounter).ltrim($url, '/');
		return $result;
	}

	/**
	 *
	 * @param string $absolutePath
	 * @return string
	 * @throws Exception
	 */
	public function getCacheKiller(string $absolutePath): string
	{
		if (!file_exists($absolutePath)) {
			throw new Exception('File '.$absolutePath.' not found');
		}
		return filemtime($absolutePath);
	}
}
