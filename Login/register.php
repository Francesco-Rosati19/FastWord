<?php
if($_SERVER["REQUEST_METHOD"]!="POST"){
    header("Content-Type: application/json");
}
else{
    $dbconn=pg_connect("host=localhost port=5432 dbname=FastWord user=postgres
                        password=rootpassword");
}
?>
<!DOCTYPE html>
<html>
    <head></head>
    <body>
    <?php
    if($dbconn){
        $nome=$_POST['nome'];
        $cognome=$_POST['cognome'];
        $email=$_POST['email'];
        $password=$_POST['password'];
        $data=$_POST['data'];

        $registrazione="insert into utenti values ($1,$2,$3,$4,$5)";
        $connessione=pg_query_params($dbconn,$registrazione,array($nome,$cognome,$email,$password,$data));
    }
    ?>
    </body>
</html>