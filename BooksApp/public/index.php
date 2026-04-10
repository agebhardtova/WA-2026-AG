<?php
// Nastartování relací pro ukládání dočasných dat (Flash zprávy) - PŘIDÁNO PODLE UČITELE
session_start();

// Pro účely výuky a ladění na lokálním serveru
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Dynamické zjištění základní adresy aplikace
$baseDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', $baseDir);

// Načtení třídy routeru
require_once '../core/App.php';

// Inicializace aplikace
$app = new App();