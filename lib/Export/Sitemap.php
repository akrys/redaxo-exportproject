<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2018-01-28
 * @author        akrys
 */
namespace akrys\redaxo\addon\ProjectExport\Export;

use rex;
use rex_article;
use rex_sql;

/**
 * Description of Sitemap
 *
 * @author akrys
 */
class Sitemap
	extends Base
{

	/**
	 *
	 * @return Base
	 */
	public function perform(): Base
	{
		//2. Schritt
		if ($this->debug) {
			print PHP_EOL.PHP_EOL;
			print 'Step 2: generate new export files: '.PHP_EOL;
		}

		$server = preg_replace('#(https?://[^/]+)/.*$#msi', '\1', rex::getServer());
		var_dump(rex::getServer());

		$rexSql = rex_sql::factory();

		$notFound = [rex_article::getNotfoundArticleId()];
		$notFoundString = implode(',', array_map([$rexSql, 'escape'], $notFound));
		$sql = <<<SQL
select id,clang_id
from rex_article
where status = 1 and id not in ($notFoundString);
SQL;

		$items = $rexSql->getArray($sql);
		foreach ($items as $item) {
			$article = rex_article::get($item['id'], $item['clang_id']);

			$url = $article->getUrl(['export' => 'true', 'dir' => $this->dir], '&');
			if (!preg_match('#^https?://#', $url)) {
				$url = $server.$url;
			}

			$curl = \curl_init($url);
			\curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$data = \curl_exec($curl);
			if ($this->debug) {
				$info = \curl_getinfo($curl);

				print 'URL: '.$info['url'].PHP_EOL;
				print 'HTTP-Stauts: '.$info['http_code'].PHP_EOL;
				print 'Content-Type: '.$info['content_type'].PHP_EOL;
				print 'Length: '.$info['download_content_length'].PHP_EOL;
				print PHP_EOL;
			}
			\curl_close($curl);
		}
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
		if ($this->debug) {
			print __METHOD__.$dirname.' | '.$entry.PHP_EOL;
		}
		return true;
	}
}
