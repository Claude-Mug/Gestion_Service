<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Services";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Vérifiez si l'ID est passé en paramètre
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Supprimer le client de la base de données
    $sql = "DELETE FROM clients WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        // Rediriger vers GestionClient.php après la suppression
        header("Location: GestionClient.php");
        exit();
    } else {
        echo "Erreur lors de la suppression du client.";
    }
} else {
    echo "ID du client manquant.";
}

$conn->close();
?>