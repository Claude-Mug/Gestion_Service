<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Services";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

$sql = "SELECT id, nom, prenom, ville, pays, sexe, email FROM clients";
$result = $conn->query($sql);

$clients = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}
echo json_encode($clients);

$conn->close();
?>