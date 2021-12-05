<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2018-07-01
 * @author        akrys
 */
namespace akrys\redaxo\addon\ProjectExport\PageController;

/**
 * Description of BackendPageController
 *
 * @author akrys
 */
class BackendPageController
{
	const REGEX_DELIMETER = '#';
	const REGEX_ADMIN_START = '<span class="admin">';
	const REGEX_ADMIN_END = '</span>';
	const REGEX_FLAGS = 'msi';

	/**
	 * Daten ausgabe
	 * @param string $title
	 * @param string $body
	 * @return string
	 */
	public function getBox(string $title, string $body): string
	{
		$fragment = new \rex_fragment();
		$fragment->setVar('heading', $title, false);

		if (!\rex::getUser()->isAdmin()) {
			$regex = self::REGEX_DELIMETER.
				preg_quote(self::REGEX_ADMIN_START, self::REGEX_DELIMETER).
				'.*?'.
				preg_quote(self::REGEX_ADMIN_END, self::REGEX_DELIMETER).
				self::REGEX_DELIMETER.
				self::REGEX_FLAGS;
			$body = preg_replace($regex, '', $body);
		}

		$fragment->setVar('body', $body, false);
		return $fragment->parse('core/page/section.php');
	}

}
