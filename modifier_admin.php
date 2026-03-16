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

// Vérification de l'authentification
if (!isset($_SESSION['authenticated'])) {
    header("Location: index.php"); // Rediriger vers la page d'authentification
    exit;
}

// Récupérer l'ID de l'administrateur à modifier
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    // Récupérer les informations de l'administrateur
    $result = $conn->query("SELECT * FROM admins WHERE id = '$id'");
    $admin = $result->fetch_assoc();

    if (!$admin) {
        die("Administrateur non trouvé.");
    }
}

// Mise à jour des informations de l'administrateur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adminNom'])) {
    $nom = $conn->real_escape_string($_POST['adminNom']);
    $prenom = $conn->real_escape_string($_POST['adminPrenom']);
    $email = $conn->real_escape_string($_POST['adminEmail']);
    $role = $conn->real_escape_string($_POST['adminRole']);
    $mot_de_passe = $_POST['adminPassword'];

    // Hash le mot de passe si fourni
    if (!empty($mot_de_passe)) {
        $mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $sql = "UPDATE admins SET nom = '$nom', prenom = '$prenom', email = '$email', role = '$role', mot_de_passe = '$mot_de_passe' WHERE id = '$id'";
    } else {
        $sql = "UPDATE admins SET nom = '$nom', prenom = '$prenom', email = '$email', role = '$role' WHERE id = '$id'";
    }

    if ($conn->query($sql) === TRUE) {
        // Rediriger vers la page d'ajout d'administrateur après la mise à jour
        header("Location: ajouter_admin.php");
        exit;
    } else {
        $error = "Erreur: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5 col-6 bg-danger-subtle">
        <h2>Modifier Administrateur</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="adminNom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="adminNom" name="adminNom" value="<?php echo $admin['nom']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="adminPrenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="adminPrenom" name="adminPrenom" value="<?php echo $admin['prenom']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="adminEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="adminEmail" name="adminEmail" value="<?php echo $admin['email']; ?>" required>
            </div>
            <div class="mb-3">
          <label for="adminPassword" class="form-label">Nouveau Mot de passe (laisser vide pour ne pas changer)</label>
           <div class="input-group">
             <input type="password" class="form-control" id="adminPassword" name="adminPassword">
           <span class="input-group-text" onclick="togglePasswordVisibility('adminPassword')">👁️</span>
         </div>
      </div>
            <div class="mb-3">
                <label for="adminRole" class="form-label">Rôle</label>
                <select class="form-select" id="adminRole" name="adminRole" required>
                    <option value="manager" <?php if ($admin['role'] == 'manager') echo 'selected'; ?>>Manager</option>
                    <option value="editor" <?php if ($admin['role'] == 'editor') echo 'selected'; ?>>Éditeur</option>
                    <option value="viewer" <?php if ($admin['role'] == 'viewer') echo 'selected'; ?>>Visionneur</option>
                    <option value="service_reparation" <?php if ($admin['role'] == 'service_reparation') echo 'selected'; ?>>Service Réparation</option>
                    <option value="services_domicile" <?php if ($admin['role'] == 'services_domicile') echo 'selected'; ?>>Services Domicile</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <?php if (isset($error)) { echo "<div class='text-danger mt-2'>$error</div>"; } ?>
        </form>
    </div>

    <script>
    function togglePasswordVisibility(passwordFieldId) {
        const passwordInput = document.getElementById(passwordFieldId);
        passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
    }
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>