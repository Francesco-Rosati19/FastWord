<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Content-Type: application/json");
    exit();
} else {
    $dbconn = pg_connect("host=localhost port=5432 dbname=FastWord user=postgres password=rootpassword");

    if ($dbconn) {
        $username = $_POST['username'];
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $data = $_POST['data'];

        //salvataggio dei dati inseriti nel caso ci sia un errore di email o password così da non dover riscrivere nuovamente
        //i dati che sono assolutamente giusti come nome,cognome,data ecc
        $_SESSION['old_register_data'] = [
            'username' => $username,
            'nome' => $nome,
            'cognome' => $cognome,
            'email' => $email,
            'data' => $data
        ];

        // Controllo username duplicato
        $check_query = "SELECT * FROM utenti WHERE username = $1";
        $check_result = pg_query_params($dbconn, $check_query, array($username));

        if (pg_num_rows($check_result) > 0) {
            $_SESSION['register_error'] = 'duplicato_username';
            header('Location: ../Index/index.php');
            exit();
        }

        // Controllo email duplicata
        $check_query = "SELECT * FROM utenti WHERE email = $1";
        $check_result = pg_query_params($dbconn, $check_query, array($email));

        if (pg_num_rows($check_result) > 0) {
            $_SESSION['register_error'] = 'duplicato_email';
            header('Location: ../Index/index.php');
            exit();
        }

        // Hash della password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Inserimento nelle tabelle
        $registrazione = "INSERT INTO utenti (username, email, nome, cognome, password, data) VALUES ($1, $2, $3, $4, $5, $6)";
        $result1 = pg_query_params($dbconn, $registrazione, array($username, $email, $nome, $cognome, $password_hash, $data));

        $registrazione_dati = "INSERT INTO utentedati (username, email, precisione, punteggio_medio, velocita_gennaio, velocita_febbraio, velocita_marzo, velocita_aprile, velocita_maggio,
        velocita_giugno, velocita_luglio, velocita_agosto, velocita_settembre, velocita_ottobre, velocita_novembre, velocita_dicembre) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16)";
        $result2 = pg_query_params($dbconn, $registrazione_dati, array($username, $email, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));

        // Entrembe le query hanno avuto successo
        if ($result1 && $result2) {
            // Pulisce vecchi dati dalla sessione
            unset($_SESSION['register_error'], $_SESSION['old_register_data']);
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            header("Location: ../Profilo/profilo.php");
            exit();
        } else {
            echo "Errore durante la registrazione.";
        }
    } else {
        echo "Errore di connessione al database.";
    }
}
?>
