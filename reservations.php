<?php
session_start(); // Démarrer la session

$host = 'localhost';
$dbname = 'Services';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les réservations de la table commanddomicile
    $query = "SELECT id, nom_service, domaine, service, other_service, request_type, request_date, user_name, user_email, user_phone, user_address, user_comments, prix, user_id, statut FROM commanddomicile";
    $stmt = $pdo->query($query);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styles personnalisés */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color:rgb(152, 209, 98); /* Couleur de fond clair pour les lignes impaires */
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #e9ecef; /* Couleur de fond légèrement plus foncée pour les lignes paires */
        }
        .table-success {
            background-color: #d4edda; /* Vert clair pour les succès */
        }
        .table-danger {
            background-color: #f8d7da; /* Rouge clair pour les erreurs */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Liste des Réservations</h2>
        <table class="table table-striped table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nom du Service</th>
                    <th>Domaine</th>
                    <th>Service</th>
                    <th>Autre Service</th>
                    <th>Type de Demande</th>
                    <th>Date de Demande</th>
                    <th>Nom de l'Utilisateur</th>
                    <th>Email de l'Utilisateur</th>
                    <th>Téléphone de l'Utilisateur</th>
                    <th>Adresse de l'Utilisateur</th>
                    <th>Commentaires de l'Utilisateur</th>
                    <th>Prix</th>
                    <th>ID de l'Utilisateur</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($reservations) > 0): ?>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr class="<?php echo ($reservation['statut'] === 'success') ? 'table-success' : (($reservation['statut'] === 'error') ? 'table-danger' : ''); ?>">
                            <td><?php echo htmlspecialchars($reservation['id']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['nom_service']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['domaine']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['service']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['other_service']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['request_type']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['request_date']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['user_email']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['user_phone']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['user_address']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['user_comments']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['prix']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['statut']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="15" class="text-center">Aucune réservation trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>