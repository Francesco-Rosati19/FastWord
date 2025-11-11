<?php
// Connessione DB
$dbconn = pg_connect("host=localhost port=5432 dbname=FastWord user=postgres password=123rosati");
$db_error="";
if (!$dbconn) {
    $db_error = "Errore nella connessione al database.";
}


$email = '';
$email_error = '';
$nome = '';
$cognome = '';
$show_form = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"])) {
    $email = trim($_POST["email"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Email non valida.";
    } else {
        $result = pg_query_params($dbconn, "SELECT nome, cognome FROM utenti WHERE email = $1", array($email));
        if ($result && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            $nome = htmlspecialchars($row['nome']);
            $cognome = htmlspecialchars($row['cognome']);
            $show_form = true;
        } else {
            $email_error = "Se l'email è registrata, riceverai un messaggio di recupero.";
        }
    }
} else {
    // se l'utente arriva su questa pagina senza POST, puoi mostrare un semplice form con solo email
    // oppure reindirizzarlo al login
    header("Location: ../Index/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <title>Reset Password</title>
    <link rel="stylesheet" href="styleDimenticata.css" />
</head>
<body>
    <div class="container">

    <?php if ($db_error): ?>
        <div class="message"><?= $db_error ?></div>
    <?php elseif ($show_form): ?>
        <h2>Reset della password</h2>
        <p>Ciao <strong><?= $nome . " " . $cognome ?></strong>, inserisci la nuova password qui sotto:</p>

        <form action="reset_password.php" method="post">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>" />

            <label for="new_password">Nuova Password:</label>
            <input type="password" id="new_password" name="new_password" required minlength="8" placeholder="Nuova password" />

            <label for="confirm_password">Conferma Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="8" placeholder="Conferma nuova password" />

            <button type="submit">Reset Password</button>
        </form>

        <a href="../Index/index.php">Torna al login</a>

    <?php else: ?>
        <form action="" method="post">
            <label for="email">Inserisci la tua email per il reset:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required />
            <br><br>
            <?php if ($email_error): ?>
                <div class="error-message"><?= $email_error ?></div>
            <?php endif; ?>
            <button type="submit">Invia</button>
        </form>
        <a href="../Index/index.php">Torna al login</a>
    <?php endif; ?>

    </div>
</body>
</html>
