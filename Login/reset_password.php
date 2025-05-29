<?php
// Connessione DB
$dbconn = pg_connect("host=localhost port=5432 dbname=FastWord user=postgres password=rootpassword");
if (!$dbconn) {
    $db_error = "Errore nella connessione al database.";
}

$email = '';
$new_password = '';
$confirm_password = '';

$email_error = '';
$password_error = '';
$confirm_error = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"], $_POST["new_password"], $_POST["confirm_password"])) {
    $email = trim($_POST["email"]);
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    $valid = true;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Email non valida.";
        $valid = false;
    }

    if (strlen($new_password) < 8) {
        $password_error = "La password deve essere di almeno 8 caratteri.";
        $valid = false;
    }

    if ($new_password !== $confirm_password) {
        $confirm_error = "Le password non corrispondono.";
        $valid = false;
    }

    if ($valid) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $result = pg_query_params(
            $dbconn,
            "UPDATE utenti SET password = $1 WHERE email = $2",
            array($hashed_password, $email)
        );

        if ($result) {
            $success_message = "Password aggiornata con successo!";
            $email = $new_password = $confirm_password = '';
        } else {
            $email_error = "Errore durante l'aggiornamento della password.";
        }
    }
} else {
    // Se si tenta di accedere senza POST, reindirizzo
    header("Location: ../Index/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <title>Aggiorna Password</title>
    <link rel="stylesheet" href="styleReset.css" />
</head>
<body>
    <div class="container">

    <?php if ($success_message): ?>
        <h2><?= $success_message ?></h2>
        <p>Aggiornamento della password avvenuto con successo, tornerai al login tra pochi secondi</p>
        <script>
            setTimeout(function() {
                window.location.href = "../Index/index.php";
            }, 3000);
        </script>
    <?php else: ?>
        <h2>Reset della password</h2>
        <form action="" method="post">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>" />

            <label for="new_password">Nuova Password:</label>
            <input type="password" id="new_password" name="new_password" value="<?= htmlspecialchars($new_password) ?>" required minlength="8" placeholder="Nuova password" />
            <?php if ($password_error): ?>
                <div class="error-message"><?= $password_error ?></div>
            <?php endif; ?>

            <label for="confirm_password">Conferma Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" value="<?= htmlspecialchars($confirm_password) ?>" required minlength="8" placeholder="Conferma nuova password" />
            <?php if ($confirm_error): ?>
                <div class="error-message"><?= $confirm_error ?></div>
            <?php endif; ?>

            <?php if ($email_error): ?>
                <div class="error-message"><?= $email_error ?></div>
            <?php endif; ?>

            <button type="submit">Aggiorna Password</button>
        </form>
        <a href="../Index/index.php">Torna al login</a>
    <?php endif; ?>

    </div>
</body>
</html>
