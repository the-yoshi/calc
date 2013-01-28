<?php if (isset($_SESSION["user"]) && $_SESSION["user"]["rolle"] != "guest"): ?>
<?php $ort = $_SERVER["PHP_SELF"]."?site=aufgabenliste"; $mysql = new MySQL(); ?>

<?php echo $mysql->makeSchuelerTaskList($_SESSION["user"]["id"], $ort); ?>












<?php else: ?>
	Zugriff verweigert!
<?php endif; ?>