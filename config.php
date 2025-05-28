<?php
$mysqli = new mysqli('localhost', 'root', '', 'tinderckziu');
if ($mysqli->connect_error) {
    die("Błąd połączenia: " . $mysqli->connect_error);
}
define('ENCRYPTION_KEY', '12345678901234567890123456789012');