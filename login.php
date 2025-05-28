<?php
session_start();
if (isset($_SESSION['user_email'])) {
    header('Location: main.php');
    exit();
}

require 'config.php';
require 'functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$email || !$password) {
        $message = "Wypełnij wszystkie pola.";
    } else {
        $stmt = $mysqli->prepare("SELECT haslo FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($encryptedPasswordFromDB);

        if ($stmt->fetch()) {
            $decryptedPassword = decryptPassword($encryptedPasswordFromDB, ENCRYPTION_KEY);

            if ($password === $decryptedPassword) {
                // Poprawne logowanie - ustawiamy sesję i przekierowujemy
                $_SESSION['user_email'] = $email;
                header('Location: main.php');
                exit();
            } else {
                $message = "Nieprawidłowe hasło.";
            }
        } else {
            $message = "Nie znaleziono użytkownika o takim emailu.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Logowanie - TinderCKZIU</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="shortcut icon" href="favicon_tvs.png" type="image/x-icon" />
  <link rel="stylesheet" href="stylesignup.css" />
</head>
<body>
  <main>
    <section id="container">
      <h1>Sign In</h1>
      <?php if ($message): ?>
        <p style="color:red; text-align:center;"><?= htmlspecialchars($message) ?></p>
      <?php endif; ?>
      <form method="post" action="">
        <input type="email" id="email" name="email" placeholder="Email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required />
        <input type="password" id="password" name="password" placeholder="Password" required />
        <button type="submit">Log In</button>
      </form>
      <p>Don't have an account? <a href="register.php">Sign Up</a></p>
    </section>
  </main>

  <footer>
    <section id="social">
      <p>
        <a href="https://www.instagram.com/team_vision_studio" target="_blank"><i class="fab fa-instagram fa-2x"></i></a>
        <a href="https://www.tiktok.com/@team_vision_sudio" target="_blank"><i class="fab fa-tiktok fa-2x"></i></a>
        <a href="https://www.facebook.com/profile.php?id=61556092873561" target="_blank"><i class="fab fa-facebook fa-2x"></i></a>
        <a href="https://www.youtube.com/@Team_Vision_Studio" target="_blank"><i class="fab fa-youtube fa-2x"></i></a>
      </p>
    </section>
    <section>
      <p>© 2025 Team Vision Studio. Wszelkie prawa zastrzeżone.</p>
    </section>
  </footer>
</body>
</html>
