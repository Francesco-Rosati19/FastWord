<?php
session_start();
$dbconn = pg_connect("host=localhost port=5432 dbname=FastWord user=postgres password=rootpassword");

// Recupera username dalla sessione
$username = $_SESSION['username'] ?? null;
if (!$username) {
    http_response_code(401);
    echo "Utente non autenticato";
    exit;
}

// Ricevi i dati JSON dalla richiesta POST
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['wpm']) || !isset($input['accuracy'])) {
    http_response_code(400);
    echo "Dati mancanti";
    exit;
}

$wpm = floatval($input['wpm']);
$accuracy = floatval($input['accuracy']);

// Mappa dei mesi
$mesi = [
    '01' => 'gennaio', '02' => 'febbraio', '03' => 'marzo', '04' => 'aprile',
    '05' => 'maggio', '06' => 'giugno', '07' => 'luglio', '08' => 'agosto',
    '09' => 'settembre', '10' => 'ottobre', '11' => 'novembre', '12' => 'dicembre'
];

$meseNumero = date('m');
$meseNome = $mesi[$meseNumero];
$colVelocita = "velocita_" . $meseNome;

$tabella = "utentedati";

// Preleva dati utente
$query = "SELECT giocate, mese_corrente, $colVelocita, precisione FROM $tabella WHERE username = $1";
$result = pg_query_params($dbconn, $query, [$username]);

if (!$result || pg_num_rows($result) === 0) {
    http_response_code(500);
    echo "Errore nel recupero dati utente";
    exit;
}

$row = pg_fetch_assoc($result);
$giocate = (int)$row['giocate'];
$meseCorrente = $row['mese_corrente'];
$velocitaAttuale = (float)$row[$colVelocita];
$precisioneAttuale = (float)$row['precisione'];

if ($meseCorrente !== $meseNumero) {
    // Nuovo mese: reset giocate e precisione
    $giocate = 1;
    $nuovaVelocita = $wpm;
    $nuovaPrecisione = $accuracy;

    $updateQuery = "UPDATE $tabella SET $colVelocita = $1, giocate = $2, mese_corrente = $3, precisione = $4 WHERE username = $5";
    $updateResult = pg_query_params($dbconn, $updateQuery, [$nuovaVelocita, $giocate, $meseNumero, $nuovaPrecisione, $username]);
} else {
    // Stesso mese: calcola media ponderata
    $giocate++;
    $nuovaVelocita = ($velocitaAttuale * ($giocate - 1) + $wpm) / $giocate;
    $nuovaPrecisione = ($precisioneAttuale * ($giocate - 1) + $accuracy) / $giocate;

    $updateQuery = "UPDATE $tabella SET $colVelocita = $1, giocate = $2, precisione = $3 WHERE username = $4";
    $updateResult = pg_query_params($dbconn, $updateQuery, [$nuovaVelocita, $giocate, $nuovaPrecisione, $username]);
}

if ($updateResult) {
    echo "✅ Statistiche aggiornate per $meseNome";
} else {
    http_response_code(500);
    echo "❌ Errore nell'aggiornamento";
}
?>
