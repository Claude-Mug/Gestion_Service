<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "services"; // Nom de votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Vérifier si l'ID du prestataire est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Supprimer le prestataire de la base de données
    $stmt = $conn->prepare("DELETE FROM prestataire WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Prestataire supprimé avec succès.";
    } else {
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Aucun ID fourni.";
}

$conn->close();
?>

<!-- Redirection vers la liste des prestataires après suppression -->
<meta http-equiv="refresh" content="2;url=prestatairBase.php">