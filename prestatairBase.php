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

// Enregistrement d'un nouveau prestataire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];
    $services = $_POST['services'];
    $experience = $_POST['experience'];
    $disponibilite = $_POST['disponibilite'];
    $lettre_motivation = $_POST['lettreMotivation'];

    // Vérification et gestion du fichier CV
    $cv = isset($_FILES['cv']) ? $_FILES['cv']['name'] : null;
    if ($cv) {
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
    $portfolio = !empty($portfolioPaths) ? implode(',', $portfolioPaths) : null;

    // Insertion dans la base de données avec des requêtes préparées
    $stmt = $conn->prepare("INSERT INTO prestataire (nom, email, telephone, adresse, services, experience, disponibilite, lettre_motivation, cv, portfolio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $nom, $email, $telephone, $adresse, $services, $experience, $disponibilite, $lettre_motivation, $cv, $portfolio);

    if ($stmt->execute()) {
        echo "Nouveau prestataire enregistré avec succès.";
    } else {
        echo "Erreur: " . $stmt->error;
    }
    $stmt->close();
}

// Affichage des prestataires
$sql = "SELECT * FROM prestataire";
$result = $conn->query($sql);
?>

<!-- Affichage de la liste des prestataires -->
<div class="container mt-4">
    <h2>Liste des Prestataires</h2>
    <a href="ajouter.php" class="btn btn-outline-primary mb-3">Ajouter un Prestataire</a> <!-- Bouton pour ajouter un prestataire -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nom']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['telephone']; ?></td>
                        <td>
                            <a href="modifierP.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-warning">Modifier</a>
                            <a href="supprimerP.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Aucun prestataire trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$conn->close();
?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">