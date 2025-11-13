<?php
session_start();
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false]);
    exit();
}

$dbconn = pg_connect("host=localhost port=5432 dbname=FastWord user=postgres password=123rosati");
if (!$dbconn) {
    echo json_encode(['success' => false]);
    exit();
}

$email = $_SESSION['email'];

// Elimina dal database
$query = "DELETE FROM utenti WHERE email = $1";
$result = pg_query_params($dbconn, $query, [$email]);

if ($result) {
    session_destroy();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
pg_close($dbconn);
?>
