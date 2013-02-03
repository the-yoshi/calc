<?php
#Alte Version aus Testzeiten
### Konfiguration der Aufgaben - in Klasse Auslagern!
## und einen Defaultwert abhängig von der Klassenstufe definieren
$_SESSION['aufgaben'] = array();

$_SESSION["klassen"] = array("runden", "term", "vergleich", "schaetzwert");
#$_SESSION["klassen"] = array("term");
$_SESSION['schemata'] = array("a * b", "b * a", "a*b - g", "d + h * i", "a^3");
$_SESSION['operatoren'] = array("*","/","+","-");
$_SESSION['konstanten'] = array(array("a", 6));
$_SESSION['abweichung'] = 10;
$_SESSION['von'] = 0;
$_SESSION['bis'] = 30;
$_SESSION['kommazahlen'] = false;
$_SESSION['nr'] = 0;
$_SESSION['anzahl'] = 6;
####
?>