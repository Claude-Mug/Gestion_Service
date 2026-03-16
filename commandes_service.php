<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Services";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Récupérer toutes les commandes
$sql = "SELECT * FROM Commanddomicile";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        table {
            border-collapse: collapse; /* Évite l'espacement entre les cellules */
        }
        th, td {
            border: 1px solid #dee2e6; /* Bordure pour les cellules */
            padding: 8px; /* Espacement interne */
            text-align: left; /* Alignement à gauche */
        }
        th {
            background-color: #f8f9fa; /* Couleur de fond pour les en-têtes */
        }
        tbody tr:hover {
            background-color: #f1f1f1; /* Couleur de fond au survol */
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Liste des Commandes</h1>
    
    <table class="table">
        <thead class="bg-warning-subtle">
            <tr>
                <th>ID</th>
                <th>Nom Service</th>
                <th>Domaine</th>
                <th>Service</th>
                <th>Statut</th> <!-- Ajout de la colonne Statut -->
                <th>Type de Demande</th>
                <th>Date de Demande</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>Commentaires</th>
                <th>Prix</th>
                <th>Date de commande</th>
                <th>Autre Service</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($commande = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $commande['id']; ?></td>
                    <td><?php echo $commande['nom_service']; ?></td>
                    <td><?php echo $commande['domaine']; ?></td>
                    <td><?php echo $commande['service']; ?></td>
                    <td><?php echo $commande['statut']; ?></td> <!-- Affichage du statut -->
                    <td><?php echo $commande['request_type']; ?></td>
                    <td><?php echo $commande['request_date']; ?></td>
                    <td><?php echo $commande['user_name']; ?></td>
                    <td><?php echo $commande['user_email']; ?></td>
                    <td><?php echo $commande['user_phone']; ?></td>
                    <td><?php echo $commande['user_address']; ?></td>
                    <td><?php echo $commande['user_comments']; ?></td>
                    <td><?php echo $commande['prix']; ?></td>
                    <td><?php echo $commande['created_at']; ?></td>
                    <td><?php echo $commande['other_service']; ?></td>
                    
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="15" class="text-center">Aucune commande trouvée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>