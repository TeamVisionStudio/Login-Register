<?php
require 'config.php';
require 'functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imie = trim($_POST['imie']);
    $nazwisko = trim($_POST['nazwisko']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $passwordconfirm = $_POST['passwordconfirm'];

    if (!$imie || !$nazwisko || !$email || !$password || !$passwordconfirm) {
        $message = "Wypełnij wszystkie pola.";
    } elseif ($password !== $passwordconfirm) {
        $message = "Hasła nie są identyczne.";
    } else {
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $message = "Email jest już w użyciu.";
        } else {
            $encryptedPassword = encryptPassword($password, ENCRYPTION_KEY);
            $stmt = $mysqli->prepare("INSERT INTO users (imie, nazwisko, email, haslo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $imie, $nazwisko, $email, $encryptedPassword);
            if ($stmt->execute()) {
                $message = "Rejestracja przebiegła pomyślnie. Możesz się teraz zalogować.";
            } else {
                $message = "Błąd podczas rejestracji: " . $mysqli->error;
            }
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
  <title>Rejestracja - TinderCKZIU</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="shortcut icon" href="favicon_tvs.png" type="image/x-icon" />
  <link rel="stylesheet" href="stylesignup.css" />
</head>
<body>
  <main>
    <section id="container">
      <h1>Sign Up</h1>
      <?php if ($message) echo '<p style="color:red; text-align:center;">' . htmlspecialchars($message) . '</p>'; ?>
      <form method="post" action="">
        <div class="name-row">
          <input type="text" id="imie" name="imie" placeholder="First Name" value="<?= isset($imie) ? htmlspecialchars($imie) : '' ?>" required />
          <input type="text" id="nazwisko" name="nazwisko" placeholder="Last Name" value="<?= isset($nazwisko) ? htmlspecialchars($nazwisko) : '' ?>" required />
        </div>

        <input type="email" id="email" name="email" placeholder="Email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required />
        <input type="password" id="password" name="password" placeholder="Password" required />
        <input type="password" id="passwordconfirm" name="passwordconfirm" placeholder="Confirm Password" required />

        <div class="checkbox-row">
          <input type="checkbox" class="naked-checkbox" id="accept" name="accept" required />
          <label for="accept">I agree with <a href="#">privacy</a> and <a href="#">policy</a></label>
        </div>

        <button name="dodaj" type="submit">Sign Up</button>
      </form>
      <p>Already have an account? <a href="login.php">Sign In</a></p>
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
