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

// Récupérer tous les services
$sql = "SELECT * FROM NosServices";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Gestion des Services</h1>
    <a href="ajouter_service.php" class="btn btn-primary mb-3">Ajouter un Service</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Service Domicile</th>
                <th>Service Réparation</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($service = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $service['id']; ?></td>
                <td><?php echo $service['service_domicile']; ?></td>
                <td><?php echo $service['service_reparation']; ?></td>
                <td>
                    <a href="modifier_service.php?id=<?php echo $service['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                    <a href="commandes_service.php?id=<?php echo $service['id']; ?>" class="btn btn-info btn-sm">Afficher Commandes</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
