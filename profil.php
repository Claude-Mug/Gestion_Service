<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Administrateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Liste des Administrateurs</h2>
        
        <?php
        session_start(); // Démarrer la session

        // Connexion à la base de données
        $host = 'localhost';
        $dbname = 'Services';
        $username = 'root';
        $password = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Récupération de tous les administrateurs
            $stmt = $pdo->prepare("SELECT * FROM admins");
            $stmt->execute();
            $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo '<table class="table table-bordered">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Nom</th>';
            echo '<th>Prénom</th>';
            echo '<th>Email</th>';
            echo '<th>Rôle</th>';
            if (isset($_SESSION['admin_id'])) { // Vérifie si un administrateur est connecté
                echo '<th>Actions</th>';
            }
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($admins as $admin) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($admin['id']) . '</td>';
                echo '<td>' . htmlspecialchars($admin['nom']) . '</td>';
                echo '<td>' . htmlspecialchars($admin['prenom']) . '</td>';
                echo '<td>' . htmlspecialchars($admin['email']) . '</td>';
                echo '<td>' . htmlspecialchars($admin['role']) . '</td>';
                
                if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $admin['id']) {
                    // Boutons Modifier et Supprimer uniquement pour l'administrateur connecté
                    echo '<td>';
                    echo '<a href="modifier_admin.php?id=' . $admin['id'] . '" class="btn btn-warning btn-sm">Modifier</a>';
                    echo ' ';
                    echo '<a href="supprimer_admin.php?id=' . $admin['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cet administrateur ?\');">Supprimer</a>';
                    echo '</td>';
                } else {
                    echo '<td></td>'; // Cellule vide si l'administrateur n'est pas connecté
                }
                
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';

        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">';
            echo '<h4>Erreur de connexion à la base de données: ' . htmlspecialchars($e->getMessage()) . '</h4>';
            echo '</div>';
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>