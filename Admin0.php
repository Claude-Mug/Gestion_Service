<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Services"; // Nom de votre base de données

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container m-3">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header ">
                    Gestion des Utilisateurs
                </div>
                <div class="card-body">
                    <a href="deconnect.php" class="btn btn-outline-primary m-1">Utilisateurs connecter</a>
                    <a href="client.php" class="btn btn-outline-secondary">Ajouter un Utilisateur</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Gestion des Prestataires
                </div>
                <div class="card-body">
                    <a href="prestateur.php" class="btn btn-outline-primary m-1">Voir les Prestataires</a>
                    <a href="prestatairBase.php" class="btn btn-outline-secondary">Ajouter un Prestataire</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Gestion des Services
                </div>
                <div class="card-body">
                    <a href="services.php" class="btn btn-outline-primary m-1">Voir les Services</a>
                    <a href="commandes_service.php" class="btn btn-outline-secondary">Services commander</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Gestion des Réservations
                </div>
                <div class="card-body">
                    <a href="reservations.php" class="btn btn-outline-primary m-1">Voir les Réservations</a>
                    <a href="services.php" class="btn btn-outline-secondary">Voir les services</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Gestion des Paiements
                </div>
                <div class="card-body">
                    <a href="LirePayement.php" class="btn btn-outline-primary m-1">Voir les Paiements</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Feedback et Évaluations
                </div>
                <div class="card-body">
                    <a href="revenus.php" class="btn btn-outline-primary">Voir les Évaluations</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Rapports
                </div>
                <div class="card-body">
                    <a href="rapports.php" class="btn btn-outline-primary">Générer des Rapports</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Paramètres
                </div>
                <div class="card-body">
                    <a href="themes.php" class="btn btn-outline-primary">Configurer la Plateforme</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Support Technique
                </div>
                <div class="card-body">
                    <a href="support.php" class="btn btn-outline-primary">Gérer le Support</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>