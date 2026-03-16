<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "services"; // Nom de votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Vérifiez si l'ID du prestataire est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les informations du prestataire
    $stmt = $conn->prepare("SELECT * FROM prestataire WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $prestataire = $result->fetch_assoc();
    } else {
        echo "Prestataire non trouvé.";
        exit;
    }
} else {
    echo "Aucun ID fourni.";
    exit;
}

// Mettre à jour le prestataire si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];
    $services = $_POST['services'];
    $experience = $_POST['experience'];
    $disponibilite = $_POST['disponibilite'];
    $lettre_motivation = $_POST['lettreMotivation'];

    // Vérification et gestion du fichier CV
    $cv = $prestataire['cv']; // Conserver le CV existant par défaut
    if (!empty($_FILES['cv']['name'])) { // Si un nouveau fichier est téléchargé
        $cv = $_FILES['cv']['name'];
        move_uploaded_file($_FILES['cv']['tmp_name'], "uploads/cv/" . $cv);
    }

    // Vérification et gestion du portfolio
    $portfolioPaths = [];
    if (isset($_FILES['portfolio']) && !empty($_FILES['portfolio']['name'][0])) {
        foreach ($_FILES['portfolio']['tmp_name'] as $key => $tmp_name) {
            $portfolioName = $_FILES['portfolio']['name'][$key];
            move_uploaded_file($tmp_name, "uploads/portfolio/" . $portfolioName);
            $portfolioPaths[] = $portfolioName;
        }
    }
    $portfolio = !empty($portfolioPaths) ? implode(',', $portfolioPaths) : $prestataire['portfolio'];

    // Mise à jour dans la base de données
    $stmt = $conn->prepare("UPDATE prestataire SET nom = ?, email = ?, telephone = ?, adresse = ?, services = ?, experience = ?, disponibilite = ?, lettre_motivation = ?, cv = ?, portfolio = ? WHERE id = ?");
    $stmt->bind_param("ssssssssssi", $nom, $email, $telephone, $adresse, $services, $experience, $disponibilite, $lettre_motivation, $cv, $portfolio, $id);

    if ($stmt->execute()) {
        echo "Prestataire mis à jour avec succès.";
        header("Location: prestatairBase.php"); // Redirection après succès
        exit();
    } else {
        echo "Erreur lors de la mise à jour: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!-- Formulaire de modification du prestataire -->
<div class="container mt-4 col-7 bg-danger-subtle">
    <h2>Modifier le Prestataire</h2>
    <form action="modifierP.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($prestataire['nom']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($prestataire['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" class="form-control" id="telephone" name="telephone" value="<?php echo htmlspecialchars($prestataire['telephone']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo htmlspecialchars($prestataire['adresse']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="services" class="form-label">Services</label>
            <input type="text" class="form-control" id="services" name="services" value="<?php echo htmlspecialchars($prestataire['services']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="experience" class="form-label">Expérience</label>
            <textarea class="form-control" id="experience" name="experience" required><?php echo htmlspecialchars($prestataire['experience']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="disponibilite" class="form-label">Disponibilité</label>
            <input type="text" class="form-control" id="disponibilite" name="disponibilite" value="<?php echo htmlspecialchars($prestataire['disponibilite']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="lettreMotivation" class="form-label">Lettre de Motivation</label>
            <textarea class="form-control" id="lettreMotivation" name="lettreMotivation" required><?php echo htmlspecialchars($prestataire['lettre_motivation']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="cv" class="form-label">CV (optionnel)</label>
            <input type="file" class="form-control" id="cv" name="cv">
            <small class="form-text text-muted">Laissez vide si vous ne souhaitez pas changer le CV.</small>
        </div>
        <div class="mb-3">
            <label for="portfolio" class="form-label">Portfolio (optionnel)</label>
            <input type="file" class="form-control" id="portfolio" name="portfolio[]" multiple>
            <small class="form-text text-muted">Laissez vide si vous ne souhaitez pas changer le portfolio.</small>
        </div>
        <button type="submit" class="btn btn-success">Mettre à Jour</button>
        <a href="prestatairBase.php" class="btn btn-secondary m-2">Retour</a>
    </form>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">