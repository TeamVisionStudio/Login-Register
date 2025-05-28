<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

require 'config.php'; // połączenie z bazą

$email = $_SESSION['user_email'];

$stmt = $mysqli->prepare("SELECT imie FROM users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->bind_result($imie);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Witamy - TinderCKZIU</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <main>
    <h1>Witaj, <?= htmlspecialchars($imie) ?>!</h1>
    <!--<p>Jesteś zalogowany na adres: <?= htmlspecialchars($email) ?></p>-->
    <p><a href="logout.php">Wyloguj się</a></p>
  </main>
</body>
</html>
