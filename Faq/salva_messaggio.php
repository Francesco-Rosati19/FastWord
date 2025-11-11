<?php
session_start();
$dbconn = pg_connect("host=localhost port=5432 dbname=FastWord user=postgres password=123rosati");

if (isset($_POST['userQuestion']) && trim($_POST['userQuestion']) !== '') {
    $userQuestion = trim($_POST['userQuestion']);
    $dataCorrente = date("d/m/Y H:i:s");
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    $query = "INSERT INTO richiesta (suggerimento, data,email) VALUES ($1, $2, $3)";
    $params = array($userQuestion, $dataCorrente ,$email);
    $result = pg_query_params($dbconn, $query, $params);

    if ($result) {
        $_SESSION['message'] = "✅ Domanda inviata con successo.";
        $_SESSION['message_color'] = "green";
    } else {
        $_SESSION['message'] = "❌ Errore durante il salvataggio.";
        $_SESSION['message_color'] = "red";
    }
} else {
    $_SESSION['message'] = "⚠️ Devi prima scrivere una domanda.";
    $_SESSION['message_color'] = "red";
}

// Torna alla pagina FAQ per mostrare il messaggio
header('Location: faq.php');
exit();
