<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Connexion à la base de données
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "services";       

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Vérifier si l'ID de la commande est passé en GET
if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']);

    // Supprimer la commande
    $sql = "DELETE FROM commanddomicile WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        header("Location: mescommandes.php?message=Commande supprimée avec succès.");
        exit;
    } else {
        echo "Erreur lors de la suppression de la commande : " . $stmt->error;
    }
} else {
    die("ID de commande non spécifié.");
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer Commande</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Confirmation de la Suppression</h1>
    <p>Êtes-vous sûr de vouloir supprimer cette commande ?</p>
    <form action="" method="POST">
        <button type="submit" class="btn btn-danger">Confirmer la Suppression</button>
        <a href="mescommandes.php" class="btn btn-secondary">Annuler</a>
    </form>
</body>
</html>