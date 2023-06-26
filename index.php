<?php

define("DB_SERVERNAME", "localhost");
define("DB_USERNAME","root");
define("DB_PASSWORD", "root");
define("DB_NAME", "livecoding_mysqli");

// Connect
$conn = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn && $conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
}

$nome = "luca";
$pass = "password-lunga";

if(isset($_GET["nome"]) && isset($_GET["pass"]) ) {
    $nome = $_GET["nome"];
    $pass = $_GET["pass"];
}
    
// VULNERABILE A SQL INJECTION
// $sql = "SELECT id, nome, password FROM utenti WHERE nome = '$nome' AND password = '$pass'";
// var_dump($sql);
// $result = $conn->query($sql);

// VERSIONE CORRETTA
$stmt = $conn->prepare("SELECT id, nome, password FROM utenti WHERE nome = (?) AND password = (?)");
$stmt->bind_param("ss", $nome, $pass);
$stmt->execute();
$result = $stmt->get_result();

$risultati = [];
if ($result && $result->num_rows > 0) {
    // output data of each row

    while($row = $result->fetch_assoc()) {
        $risultati[] = $row;
    }
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Coding MySQLi</title>
</head>
<body>

    <?php
    if( count($risultati) ) {
        foreach ($result as $riga) {
    ?>
        <div><?= $riga["id"] ?>: <?= $riga["nome"] ?></div>
    <?php
        }
    } else {
    ?>
        <div>Nessun risultato</div>
    <?php
    }
    ?>
    
</body>
</html>