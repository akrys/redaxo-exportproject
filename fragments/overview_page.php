
<style type="text/css">
	.admin{
		background-color:rgba(200,200,200,0.25);
	}
</style>

<?php
$bc = new akrys\redaxo\addon\ProjectExport\PageController\BackendPageController();

ob_start();
?>

<h3>Datenexport</h3>

<p>
	Hier kann das Projekt als HTML exportiert werden.
</p>

<p>
	<strong>Hinweis:</strong>
	Der Export kann je nach Größe des Projekts durchaus eine längere Zeit in Anspruch nehmen.
</p>

<div>
	Es gibt 2 Bereiche:
	<ul>
		<li>
			<div>
				export<br />

				<ol>
					<li>Es wird geprüft, ob noch ein Export auf dem Server liegt. Wenn ja, so wird er gelöscht</li>
					<li>Es wird exportiert</li>
					<li>Dabei wird bei jedem Schritt ausgegeben, was gemacht wurde. Dient im Falle des Falles der Fehlersuche</li>
					<li>Am Ende sind die exportierten Dateien unter <tt><em>[htdocs]</em>/export</tt> zu finden.</li>
				</ol>
			</div>

		</li>
		<li>
			<div>
				Export & Zip<br />

				<ol>
					<li>Es passiert grundlegend dasselbe, wie beim Export mit debug-Ausgaben. Allerdings finden hier keine Ausgaben statt.</li>
					<li>Zusätzlich werden die generierten Dateien in ein <tt>zip</tt>-Archiv gelegt und zum download angeboten.</li>
				</ol>
			</div>
		</li>
	</ul>
</div>

<p class="admin">
	<strong>Hinweis:</strong> Um die *.zip-Datei zu erstellen, muss die PHP-Extension
	"<a href="http://php.net/manual/de/book.zip.php" target="_blank">zip</a>" installiert sein.<br />
	Ubuntu/Debian call to install: <tt>sudo apt-get install php-zip</tt>
</p>



<?php
$body = ob_get_clean();
echo $bc->getBox('Export', $body);
