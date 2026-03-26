<?php

// Pro účely výuky a ladění na lokálním serveru (např. XAMPP)
// je vhodné zapnout kompletní zobrazování chyb.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Načtení třídy routeru, která se postará o zpracování URL
require_once '../core/App.php';

// Inicializace aplikace a spuštění procesu routování
$app = new App();