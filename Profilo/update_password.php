<?php
session_start();

// Connessione al database PostgreSQL
$dbconn = pg_connect("host=localhost port=5432 dbname=FastWord user=postgres password=rootpassword");

if (!isset($_SESSION['username'])) {
    $_SESSION['login_error'] = 'Devi essere loggato per cambiare la password.';
    header('Location: profilo.php');
    exit;
}

if ($dbconn) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];
    

    $username = $_SESSION['username'];
    // Ottengo la password attuale
    $query = "SELECT password FROM utenti WHERE username = $1";
    $result = pg_query_params($dbconn, $query, array($username));

    if ($result && pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);
        $storedPassword = $user['password'];

        if (!password_verify($currentPassword, $storedPassword)) {
            $_SESSION['error_message'] = 'current';
            header('Location: profilo.php');
            exit;
        }
        if (strlen($newPassword)<8){
            $_SESSION['error_message'] = 'length';
            header('Location: profilo.php');
            exit;
         }

        if ($newPassword !== $confirmNewPassword) {
            $_SESSION['error_message'] = 'mismatch';
            header('Location: profilo.php');
            exit;
        }
       
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $updateQuery = "UPDATE utenti SET password = $1 WHERE username = $2";
        $updateResult = pg_query_params($dbconn, $updateQuery, array($newPasswordHash, $username));

        if ($updateResult) {
            $_SESSION['success_message'] = 'success';
            header('Location: profilo.php');
            exit;
        } else {
            $_SESSION['error_message'] = 'Errore durante l\'aggiornamento della password. Riprova.';
            header('Location: profilo.php');
            exit;
        }
    } else {
        $_SESSION['error_message'] = 'Utente non trovato.';
        header('Location: profilo.php');
        exit;
    }
} else {
    $_SESSION['error_message'] = 'Errore di connessione al database.';
    header('Location: profilo.php');
    exit;
}
