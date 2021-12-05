##Hinweis
Das ist nur eine Möglichkeit des Projekt-Exports. Es keine "Best-Practise", es hat für mich und meine Bedürfnisse funktioniert. Das muss nicht für alle gelten :-)

## Outputfilter im Template

Als letztes im Template steht ein Codeblock, der sofern exportiert werden soll, das HTML abgreift und wegspeichert.

Es ist ziemlich sicher, dass nach der Ausführung des Templates keine weiteren Output-Filter greifen. Diese Änderungen wären sonst im Export nicht enthalten.

(Insgesamt könnte man das noch weiter verfeinern, so dass im Outputfilter nur ein Funktionsaufruf passiert.)

```php
rex_extension::register('OUTPUT_FILTER', function (rex_extension_point $ep) {

	/* @var $this rex_article_content */
	$subject = $ep->getSubject();

	//convert to ISO?
//	$subject = mb_convert_encoding($subject, 'iso-8859-1', 'utf-8');
//	header('content-type:text/html;charset=iso-8859-1');

	$articleId = $this->getArticleId();
	$clangId = $this->getClang();

	$export = rex_get('export', 'string', '');
	$dir = rex_get('dir', 'string', '');

	if ($export !== '') {
		$article = rex_article::get($articleId, $clangId);
		$url = $article->getUrl();
		$url = preg_replace('#^https?'.preg_quote($_SERVER['HTTP_HOST'].'/').'#msi', '/', $url);
		$url = preg_replace('#^'.preg_quote(rex_url::base()).'#msi', '/', $url);

		require_once rex_path::addon('exportproject').'/lib/SaveContent.php';
		$export = new \akrys\redaxo\addon\ProjectExport\SaveContent($url, $dir, $subject);
		$export->saveToFile();
	}
	return $subject;
}, rex_extension::LATE);

```

## URLs

URLs sollten nicht absoult angegben werden. Sonst würden Links, Styles oder Bilder im Export nicth funktionieren.

Darum gibt es den ```UrlNormalizer```. Der geht hin und bildet die URL relativ zum aktuellen Standort ab. Damit ist es egal, ob ```http://example.com/media/test.jpg``` oder ```file:///Users/akrys/Documents/SiteExport/media/test.jpg``` aufgerufen wird. Es wird relativ vom aktuellen Aritkel eingesetzt. z.B. ```../../media/test.jpg```

Beispiel:
```html
	<link rel="stylesheet" type="text/css" href="<?= \akrys\redaxo\addon\ProjectExport\UrlNormalizer::normalize(rex_url::frontend('/css/styles.css')); ?>?v=<?= UrlNormalizer::getCacheKiller(rex_path::absolute('/css/styles.css')); ?>">
	<script type="text/javascript" src="<?= \akrys\redaxo\addon\ProjectExport\UrlNormalizer::normalize(rex_url::frontend('/js/head.js')); ?>?v=<?= UrlNormalizer::getCacheKiller(rex_path::absolute('/js/head.js')); ?>"></script>
```

### Bilder

Es gibt eine Klasse, die von ```rex_media``` ableitet. Sie wird benötigt, um den Export bei Aufruf ausführen zu können.


```php
try {
	$media = \akrys\redaxo\addon\ProjectExport\Media\Media::get('REX_MEDIA[1]');
} catch (Exception $e) {
	$media = null;
}

$export = false;
$exportParam = rex_get('export', 'string', '');
if ($exportParam !== '') {
	$export = true;
	if ($media->export()) {
		//ok
	}
}

if (isset($media) && is_a($media, 'rex_media')) {
	$imgUrl = \akrys\redaxo\addon\ProjectExport\UrlNormalizer::normalize($media->getUrl($export));
	$imgTitle = $media->getTitle();

	/*
	  //Die Ausgabe: einfach
	  ?>
	  <img src="<?= $imgUrl ?>" title="<?= $imgTitle ?>" />
	  <?php
	 */

	// Oder via Fragment
	$fragment = new rex_fragment([
		'imgUrl' => $imgUrl,
		'imgTitle' => $imgTitle,
		'classes' => $classes,
		'text' => $text,
	]);
	echo $fragment->parse('module_content_image.php');
}
```


