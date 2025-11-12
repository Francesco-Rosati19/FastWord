<?php
session_start();

// Verifica che sia stato ricevuto il token 'credential'
if (isset($_POST['credential'])) {
    $credential = $_POST['credential'];

    // Decodifica il token JWT (senza verifica firma, per sviluppo)
    $parts = explode(".", $credential);
    if (count($parts) !== 3) {
        alert("Token JWT malformato.");
    }

    // Decodifica la parte payload (base64)
    $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);

    if (!$payload || !isset($payload['email'])) {
        alert("Dati utente non presenti nel token.");
    }
    
    $dbconn = pg_connect("host=localhost port=5432 dbname=FastWord user=postgres password=123rosati");
    if (!$dbconn) {
        alert("Connessione al DB fallita.");
    }
    $email = $payload['email'];
    $check_user = pg_query_params($dbconn, "SELECT * FROM utenti WHERE email = $1", [$email]);

    if (pg_num_rows($check_user) === 0){  //se l'utente non è presente nel database 
        $nome = $payload['given_name'] ?? '';
        $cognome = $payload['family_name'] ?? '';
        $username = explode('@', $email)[0];
        $data_reg = date('Y-m-d');
        $data_nascita = $data_reg;
        $password="1default";
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $insert_user = pg_query_params($dbconn,"INSERT INTO utenti (username, email, nome, cognome, password, data)  VALUES ($1, $2, $3, $4, $5, $6)",
            [$username, $email, $nome, $cognome, $password_hash, $data_reg]
        );
        if (!$insert_user) {
            echo "Errore inserimento in utenti: " . pg_last_error($dbconn);
            exit;
        }
        $registrazione_dati = "INSERT INTO utentedati (username, email, precisione, punteggio_medio, velocita_gennaio, velocita_febbraio, velocita_marzo, velocita_aprile, velocita_maggio, velocita_giugno, velocita_luglio, velocita_agosto, velocita_settembre, velocita_ottobre, velocita_novembre, velocita_dicembre, giocate, mese_corrente) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16, $17, $18)";

        $result2 = pg_query_params($dbconn, $registrazione_dati, [
            $username, $email, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '00'
        ]);
        if (!$result2) {
            echo "Errore inserimento in utentedati: " . pg_last_error($dbconn);
            exit;
        }

        // Verifica dati utentedati
        $check_utentedati = pg_query_params($dbconn, "SELECT * FROM utentedati WHERE username = $1", [$username]);
        if (pg_num_rows($check_utentedati) === 0) {
            echo "⚠️ Dati utente non trovatiiiiiiiiiii. Controlla che l'utente esista in 'utentedati'.";
            exit;
        } else {
            echo "Dati utente trovati in utentedati!";
        }
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['nome'] = $nome;
        $_SESSION['cognome'] = $cognome;
        $_SESSION['data'] = $data_nascita;
        $_SESSION['message']=true;
    }
    else{
         if ($dbconn) {
                    
                    $query = "SELECT username, email, password, nome, cognome, data FROM utenti WHERE email = $1";
                    $result = pg_query_params($dbconn, $query, array($email));
                    
                    $user = pg_fetch_assoc($result);
                    $hashed_password = $user['password'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['nome'] = $user['nome'];
                    $_SESSION['cognome'] = $user['cognome'];
                    $_SESSION['data'] = $user['data'];
                    $_SESSION['message']=false;
                }
     }
    
    header("Location: ../Profilo/profilo.php");
    exit;

} else {
    // Mostra i parametri POST per debug se il token non è stato ricevuto
    echo "Token non ricevuto. Controlla che il login con Google sia configurato correttamente.";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    exit;
   }
