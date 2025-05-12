<?php
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
        
         
        $check_query = "SELECT * FROM utenti WHERE username = $1 ";
        $check_result = pg_query_params($dbconn, $check_query, array($username));

        if (pg_num_rows($check_result) > 0) {
        header("Location: ../Index/index.html?errore=duplicato_username");
        exit();
        }

        $check_query = "SELECT * FROM utenti WHERE email = $1";
        $check_result = pg_query_params($dbconn, $check_query, array($email));

        if (pg_num_rows($check_result) > 0) {
        header("Location: ../Index/index.html?errore=duplicato_email");
        exit();
        }
        
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $registrazione = "INSERT INTO utenti (username, email, nome, cognome, password, data) VALUES ($1, $2, $3, $4, $5, $6)";
        $result1 = pg_query_params($dbconn, $registrazione, array($username, $email, $nome, $cognome, $password_hash, $data));

        $registrazione_dati = "INSERT INTO utentedati (username, email, precisione, punteggio_medio, velocita_gennaio, velocita_febbraio, velocita_marzo, velocita_aprile, velocita_maggio,
        velocita_giugno, velocita_luglio, velocita_agosto, velocita_settembre, velocita_ottobre, velocita_novembre, velocita_dicembre) VALUES ($1, $2, $3, $4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16)";
        $result2 = pg_query_params($dbconn, $registrazione_dati, array($username, $email, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));

        // Reindirizza se entrambe le query hanno avuto successo
        if ($result1 && $result2) {
            header("Location: ../Profilo/profilo.php?username=" . urlencode($username));
            exit();
    
        }
    } else {
        echo "Errore di connessione al database.";
    }
  }
?>
