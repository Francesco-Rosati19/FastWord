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

        $query = "SELECT username, email, password FROM utenti WHERE email = $1";
        $result = pg_query_params($dbconn, $query, array($email));

        if (pg_num_rows($result) > 0) {
            $user = pg_fetch_assoc($result);
            $hashed_password = $user['password'];

            if (password_verify($password, $hashed_password)) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                header("Location: ../Profilo/profilo.php");
                exit();
            } else {
                header("Location: ../Index/index.html?errore=password");
                exit();
            }
        } else {
            header("Location: ../Index/index.html?errore=email");
            exit();
        }
    } else {
        echo "Errore di connessione al database.";
    }
}
?>


