<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Content-Type: application/json");
    exit();
} else {
    $dbconn = pg_connect("host=localhost port=5432 dbname=FastWord user=postgres password=rootpassword");

    if ($dbconn) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $query = "SELECT username, email, password, nome, cognome, data FROM utenti WHERE email = $1";
        $result = pg_query_params($dbconn, $query, array($email));
        
        //controlla che l'email inserita si trovi nel database
        if (pg_num_rows($result) > 0) {
            $user = pg_fetch_assoc($result);
            $hashed_password = $user['password'];
            //controlla che la password inserita sia uguale a quella salvata nel database
            if (password_verify($password, $hashed_password)) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['nome'] = $user['nome'];
                $_SESSION['cognome'] = $user['cognome'];
                $_SESSION['data'] = $user['data'];
                header("Location: ../Profilo/profilo.php");
                exit();
            } else {
                //altrimenti genera errore
                $_SESSION['login_error']='password';
                header('Location: ../Index/index.php');
                exit();
            }
        } else {
            //altrimenti genera errore
            $_SESSION['login_error']='email';
            header('Location: ../Index/index.php');
            exit();
        }
    } else {
        echo "Errore di connessione al database.";
    }
}
?>


