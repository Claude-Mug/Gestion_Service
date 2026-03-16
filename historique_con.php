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

// Récupérer l'historique des connexions des administrateurs
$sql_admins = "SELECT admin_id AS id, nom, email, date_connexion FROM historique_connexion WHERE admin_id IS NOT NULL ORDER BY date_connexion DESC";
$result_admins = $conn->query($sql_admins);

// Récupérer l'historique des connexions des clients
$sql_clients = "SELECT client_id AS id, nom, email, date_connexion FROM historique_connexion WHERE client_id IS NOT NULL ORDER BY date_connexion DESC";
$result_clients = $conn->query($sql_clients);

// Gérer l'historique des visiteurs avec des cookies
if (!isset($_COOKIE['historique_visites'])) {
    $historique_visites = [];
} else {
    $historique_visites = json_decode($_COOKIE['historique_visites'], true);
}

// Affichage de l'historique
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <title>Historique des Connexions</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table {
            margin-top: 20px;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
        }
        .table tbody tr:nth-child(even) {
            background-color: #e9ecef;
        }
        .table tbody tr:hover {
            background-color: #d1e7ff;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Historique des Connexions</h2>

    <!-- Historique des Administrateurs -->
    <div class="mb-4">
        <h3>Historique des Administrateurs</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID Admin</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Date de Connexion</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_admins->num_rows > 0): ?>
                    <?php while ($row = $result_admins->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['nom']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['date_connexion']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Aucun historique de connexion trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Historique des Clients -->
    <div class="mb-4">
        <h3>Historique des Clients</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID Client</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Date de Connexion</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_clients->num_rows > 0): ?>
                    <?php while ($row = $result_clients->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['nom']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['date_connexion']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Aucun historique de connexion trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Historique des Visiteurs -->
    <div>
        <h3>Historique des Visiteurs</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Page</th>
                    <th>Date de Visite</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($historique_visites)): ?>
                    <?php foreach ($historique_visites as $visite): ?>
                        <tr>
                            <td><?= htmlspecialchars($visite['page']) ?></td>
                            <td><?= htmlspecialchars($visite['date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center">Aucun historique de visites trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>