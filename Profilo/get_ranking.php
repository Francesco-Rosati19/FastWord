<?php
session_start();

// Connessione al database
$dbconn = pg_connect("host=localhost port=5432 dbname=FastWord user=postgres password=rootpassword");

if (!$dbconn) {
    $_SESSION['ranking_error'] = "Errore di connessione al database.";
    header("Location: profilo.php");
    exit();
}

// Controlla che il mese sia stato inviato
$month = $_POST['month'] ?? null;
if (!$month) {
    $_SESSION['ranking_error'] = "Mese non specificato.";
    header("Location: profilo.php");
    exit();
}

// Mappa mese → campo DB
$monthFieldMap = [
    'gennaio' => 'velocita_gennaio',
    'febbraio' => 'velocita_febbraio',
    'marzo' => 'velocita_marzo',
    'aprile' => 'velocita_aprile',
    'maggio' => 'velocita_maggio',
    'giugno' => 'velocita_giugno',
    'luglio' => 'velocita_luglio',
    'agosto' => 'velocita_agosto',
    'settembre' => 'velocita_settembre',
    'ottobre' => 'velocita_ottobre',
    'novembre' => 'velocita_novembre',
    'dicembre' => 'velocita_dicembre'
];

if (!array_key_exists($month, $monthFieldMap)) {
    $_SESSION['ranking_error'] = "Mese non valido.";
    header("Location: profilo.php");
    exit();
}

$field = $monthFieldMap[$month];

// Esegui la query
$query = "SELECT username, $field as punteggio_medio FROM utentedati ORDER BY $field DESC LIMIT 100";
$result = pg_query($dbconn, $query);

if (!$result) {
    $_SESSION['ranking_error'] = "Errore nella query del database.";
    header("Location: profilo.php");
    exit();
}

// Costruisci classifica
$ranking = [];
$pos = 1;
while ($row = pg_fetch_assoc($result)) {
    $ranking[] = [
        'position' => $pos++,
        'username' => $row['username'],
        'score' => $row['punteggio_medio']
    ];
}

// Salva nella sessione
$_SESSION['ranking_data'] = $ranking;
$_SESSION['ranking_month'] = ucfirst($month);  // Assicurati che venga visualizzato il mese in formato maiuscolo
unset($_SESSION['ranking_error']);  // Se non ci sono errori

pg_close($dbconn);

// Redirect a profilo.php
header("Location: profilo.php#classifiche");
exit();


