<?php
session_start(); // Démarrer la session

// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Remplacez par votre nom d'utilisateur
$password = ""; // Remplacez par votre mot de passe
$dbname = "Services"; // Nom de la base de données

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

// Récupérer tous les utilisateurs connectés
$clients_query = "SELECT id, prenom, nom FROM clients"; // Adapter cette requête selon votre logique de connexion
$clients_result = $conn->query($clients_query);

// Gestion des déconnexions individuelles
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($clients_result as $client) {
        if (isset($_POST['logout_' . $client['id']])) {
            // Logique pour déconnecter l'utilisateur spécifique
            // Ici, vous pouvez gérer l'état de connexion dans la base de données si nécessaire

            // Pour cet exemple, nous allons simplement rediriger vers la même page
            header("Location: " . $_SERVER['PHP_SELF']); // Rediriger vers la même page
            exit();
        }
    }
}

// Fermeture de la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs Connectés</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Utilisateurs Connectés</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($clients_result->num_rows > 0): ?>
                    <?php while ($client = $clients_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($client['id']); ?></td>
                            <td><?php echo htmlspecialchars($client['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($client['nom']); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <button type="submit" name="logout_<?php echo $client['id']; ?>" class="btn btn-outline-danger">Déconnecter</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aucun utilisateur connecté.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>