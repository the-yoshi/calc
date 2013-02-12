<!DOCTYPE html>
<HTML>
	<head>
		<title>Kopfrechnen Online</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="style.css" type="text/css" media="screen, projection" />
		<!-- Sichtbarkeit eines Elements umschalten  -->
		<script type="text/javascript">
			<!--
				function visible(element) {
					document.getElementById(element).style.display = '';
				}
				function invisible(element) {
					document.getElementById(element).style.display = 'none';
				}
		//-->
		</script>
	</head>
	<body OnLoad="document.f1.ergebnis.focus();">
		<div id="page">
			<div id="header">
				<!-- Willkommen zum Kopfrechnen! -->
				<?php echo $calc_header; ?>
			</div>
			<div id="main">
				<div id="leftmenu">
					<?php echo $calc_menu ?>
				</div> 
				<div id="content">
					<?php echo $calc_content; ?>
				</div>
			</div>
			<div id="footer">
				<?php echo $calc_footer; ?>
			</div>
		</div>
	</body>
</HTML>

