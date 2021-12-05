<?php

echo rex_view::title('Export', null);

$subpage = rex_be_controller::getCurrentPagePart(2);
$debug = true;
$zip = false;

switch ($subpage) {
	case 'overview':
		$fragment = new rex_fragment([]);
		echo $fragment->parse('fragments/overview_page.php');

		break;

	case 'zip':
		$zip = true;
	//no break ( OR Condition )
	case 'export':
		try {
			if ($debug) {
				print '<pre>';
			}

			$export = new \akrys\redaxo\addon\ProjectExport\Export\Export();

			$export->setDebug($debug)
				->export($zip);

			if ($debug) {
				print '</pre>';
			}
		} catch (\Exception $e) {
			var_dump($e);
			die();
		}
		break;
}
