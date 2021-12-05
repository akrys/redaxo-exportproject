<?php
$additionalStyle = \rex::isBackend() ? 'max-width:500px;' : 'max-width:100%;';
?>


<div class="image">
	<img style="<?= $additionalStyle ?>" src="<?= $this->imgUrl ?>" alt="<?= $this->imgTitle; ?>"<?= $this->classes == '' ? '' : ' class="'.$this->classes.'"' ?> />

	<?php
	if ($this->text != '') {
		?>

		<p><?= $this->text ?></p>

		<?php
	}
	?>
</div>
