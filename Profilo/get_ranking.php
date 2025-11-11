<?php
session_start();

// Connessione al database
$dbconn = pg_connect("host=localhost port=5432 dbname=FastWord user=postgres password=123rosati");

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

// Array mesi (per controllo e indice)
$mesi = ['gennaio','febbraio','marzo','aprile','maggio','giugno','luglio','agosto','settembre','ottobre','novembre','dicembre'];

$month = strtolower($month);

// Verifica che il mese sia valido
if (!in_array($month, $mesi)) {
    $_SESSION['ranking_error'] = "Mese non valido.";
    header("Location: profilo.php");
    exit();
}

// Controlla che il mese non sia nel futuro
$monthIndex = array_search($month, $mesi);          // indice 0-based del mese richiesto
$currentMonthIndex = date('n') - 1;                 // indice 0-based mese attuale

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

$field = $monthFieldMap[$month];

$query = "SELECT username, $field as punteggio_medio,giocate FROM utentedati ORDER BY $field DESC LIMIT 100";
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
        'score' => $row['punteggio_medio'] ?? 0,
        'partite' => $row['giocate'] ?? 0
    ];
}

// Salva nella sessione
$_SESSION['ranking_data'] = $ranking;
$_SESSION['ranking_month'] = ucfirst($month);
unset($_SESSION['ranking_error']);

pg_close($dbconn);

// Redirect a profilo.php con ancoraggio
header("Location: profilo.php#classifiche");
exit();
