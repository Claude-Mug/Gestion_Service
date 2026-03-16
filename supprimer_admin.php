<?php
session_start();

// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Services";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Vérification de l'authentification
if (!isset($_SESSION['authenticated'])) {
    header("Location: index.php"); // Rediriger vers la page d'authentification
    exit;
}

// Récupérer l'ID de l'administrateur à supprimer
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    // Supprimer l'administrateur de la base de données
    $sql = "DELETE FROM admins WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        $success = "Administrateur supprimé avec succès.";
    } else {
        $error = "Erreur: " . $conn->error;
    }
}

// Rediriger vers la page d'ajout d'administrateur après la suppression
header("Location: ajouter_admin.php");
exit;

$conn->close();
?>