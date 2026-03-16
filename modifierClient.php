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

// Vérifiez si l'ID est passé en paramètre
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Récupérer les informations du client
    $sql = "SELECT * FROM clients WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
    } else {
        die("Client non trouvé.");
    }
} else {
    die("ID du client manquant.");
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $conn->real_escape_string($_POST['nom']);
    $prenom = $conn->real_escape_string($_POST['prenom']);
    $email = $conn->real_escape_string($_POST['email']);
    $pays = $conn->real_escape_string($_POST['pays']);
    $ville = $conn->real_escape_string($_POST['ville']);
    $sexe = $conn->real_escape_string($_POST['sexe']);
    
    // Vérifiez si un mot de passe a été fourni
    $motdepasse = $_POST['mot_de_passe'] ? password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT) : null;

    // Construire la requête de mise à jour
    $sql = "UPDATE clients SET nom='$nom', prenom='$prenom', email='$email', pays='$pays', ville='$ville', sexe='$sexe'";
    
    // Ajoutez la mise à jour du mot de passe si fourni
    if ($motdepasse) {
        $sql .= ", mot_de_passe='$motdepasse'";
    }
    
    $sql .= " WHERE id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // Rediriger vers la page principale après la modification
        exit();
    } else {
        echo "Erreur lors de la modification du client.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5 col-md-6 bg-danger-subtle">
    <h1 class="text-center">Modifier Client</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="registerLastName" class="form-label">Nom</label>
            <input type="text" class="form-control" id="registerLastName" name="nom" value="<?php echo $client['nom']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="registerFirstName" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="registerFirstName" name="prenom" value="<?php echo $client['prenom']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="registerEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="registerEmail" name="email" value="<?php echo $client['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="country" class="form-label">Pays</label>
            <input type="text" class="form-control" id="country" name="pays" value="<?php echo $client['pays']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">Ville</label>
            <input type="text" class="form-control" id="city" name="ville" value="<?php echo $client['ville']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Sexe</label>
            <select class="form-select" id="gender" name="sexe" required>
                <option value="Homme" <?php echo ($client['sexe'] == 'Homme') ? 'selected' : ''; ?>>Homme</option>
                <option value="Femme" <?php echo ($client['sexe'] == 'Femme') ? 'selected' : ''; ?>>Femme</option>
            </select>
        </div>
        <div class="mb-3">
      <label for="motdepasse" class="form-label">Nouveau Mot de Passe (laisser vide pour ne pas changer)</label>
     <div class="input-group">
          <input type="password" class="form-control" id="motdepasse" name="mot_de_passe">
          <span class="input-group-text" onclick="togglePasswordVisibility('motdepasse')">👁️</span>
     </div>
      </div>
        <button type="submit" class="btn btn-primary mt-3">Modifier</button>
        <a href="index.php" class="btn btn-secondary mt-3">Annuler</a>
    </form>
</div>

<script>
    function togglePasswordVisibility(passwordFieldId) {
        const passwordInput = document.getElementById(passwordFieldId);
        passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
    }
</script>
<br><br>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>