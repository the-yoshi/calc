<?php

//TODO: was soll die sonderbehandlung? das menü ist eine klasse wie jede andere

# �berpr�fen des Logins oder bereitstellen des Logindialogs
# 1. Wenn Logindaten �bergeben wurden und der Benutzer noch nicht
# angemeldet ist, melde ihn an
$ort = $_SERVER["PHP_SELF"];


if (isset($_POST['logindaten']) && !isset($_SESSION["user"])) {
	$logindaten = $_POST['logindaten'];

	$login = new Login();
	$login->einloggen($logindaten["user"], $logindaten["password"]);
	header("location: {$ort}");

	# Logoutvorgang
} elseif (isset($_GET['logout'])) {
	session_destroy();
	header("location: {$ort}");

	# Wenn eingeloggt
} elseif (isset($_SESSION["user"])) {

	echo 'MoinMoin ';
	if (isset($_SESSION["user"]["vorname"])) {
		echo $_SESSION["user"]["vorname"] . " <br />alias ";
	}
	echo '<a href="'.$ort.'?site=eigeneraccount">'.$_SESSION["user"]["username"].'</a><br />';

	#Erstellen des Men�s via Klasse
	$menu1 = new Menu($_SERVER["PHP_SELF"], "vertikal", $_SESSION["user"]["rolle"]);
	echo $menu1->anzeigen();
	
	# Falls nicht eingeloggt und Form noch nicht aufgerufen wurde, zeige
	# Formular an
} else {
	echo '<center><form action="'. $ort . '" method="post">';
	if (isset($_SESSION['error'])) {
		echo $_SESSION['error'] . "<br />";
	}
	echo '<input type="text" name="logindaten[user]" /> <br />';
	echo '<input type="password" name="logindaten[password]" /> <br />';
	echo '<input type="submit" value="Anmelden" />';
	echo '</form></center>';
	$menu2 = new Menu($_SERVER["PHP_SELF"], "vertikal");
	echo $menu2->anzeigen();
	#session_destroy();
}
?>