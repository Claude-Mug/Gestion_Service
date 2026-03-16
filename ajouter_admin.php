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

// Enregistrement des administrateurs
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adminNom'])) {
    $nom = $conn->real_escape_string($_POST['adminNom']);
    $prenom = $conn->real_escape_string($_POST['adminPrenom']);
    $email = $conn->real_escape_string($_POST['adminEmail']);
    $mot_de_passe = password_hash($_POST['adminPassword'], PASSWORD_DEFAULT);
    $role = $conn->real_escape_string($_POST['adminRole']);

    $sql = "INSERT INTO admins (nom, prenom, email, mot_de_passe, role) VALUES ('$nom', '$prenom', '$email', '$mot_de_passe', '$role')";

    if ($conn->query($sql) === TRUE) {
        $success = "Administrateur ajouté avec succès.";
    } else {
        $error = "Erreur: " . $conn->error;
    }
}

// Récupérer les administrateurs
$result = $conn->query("SELECT * FROM admins");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #mainContent {
            display: none; /* Cacher le contenu principal par défaut */
        }
    </style>
</head>
<body>
    <div class="container mt-5 col-4">
        <h2>Entrez le nom et le mot de passe pour accéder à la page des administrateurs</h2>
        <form id="authForm">
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Valider</button>
            <div id="error" class="text-danger mt-2"></div>
        </form>
    </div>

    <div id="mainContent" class="container mt-5 text-center">
        <h2>Liste des Administrateurs</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
            Ajouter un Administrateur
        </button>

        <!-- Modal pour Ajouter un Admin -->
        <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAdminModalLabel">Ajouter un Administrateur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="adminNom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="adminNom" name="adminNom" required>
                            </div>
                            <div class="mb-3">
                                <label for="adminPrenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="adminPrenom" name="adminPrenom" required>
                            </div>
                            <div class="mb-3">
                                <label for="adminEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="adminEmail" name="adminEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="adminPassword" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="adminRole" class="form-label">Rôle</label>
                                <select class="form-select" id="adminRole" name="adminRole" required>
                                    <option value="manager">Manager</option>
                                    <option value="editor">Éditeur</option>
                                    <option value="viewer">Visionneur</option>
                                    <option value="service_reparation">Service Réparation</option>
                                    <option value="services_domicile">Services Domicile</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Créer Admin</button>
                            <?php if (isset($success)) { echo "<div class='text-success mt-2'>$success</div>"; } ?>
                            <?php if (isset($error)) { echo "<div class='text-danger mt-2'>$error</div>"; } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nom']; ?></td>
                    <td><?php echo $row['prenom']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <a href="modifier_admin.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="supprimer_admin.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('authForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche l'envoi du formulaire
            const name = document.getElementById('name').value;
            const password = document.getElementById('password').value;
            const errorDiv = document.getElementById('error');

            // Vérifiez le nom et le mot de passe
            if (name === 'Claude' && password === 'Moscouw03') {
                // Affiche le contenu principal et cache le formulaire
                document.getElementById('mainContent').style.display = 'block';
                document.querySelector('.container').style.display = 'none'; // Cache le formulaire
            } else {
                errorDiv.textContent = 'Nom ou mot de passe incorrect.';
            }
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>