<?php
session_start();
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

// Traitement de l'ajout ou de la modification d'un thème
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $couleur_primaire = $_POST['couleur_primaire'];
    $couleur_secondaire = $_POST['couleur_secondaire'];
    $police = $_POST['police'];
    $taille_police = $_POST['taille_police'];
    $fond_image = $_POST['fond_image'] ?? null;
    $bordure_style = $_POST['bordure_style'] ?? null;
    $bordure_couleur = $_POST['bordure_couleur'] ?? null;

    // Insertion ou mise à jour du thème
    $stmt = $conn->prepare("INSERT INTO themes (nom, couleur_primaire, couleur_secondaire, police, taille_police, fond_image, bordure_style, bordure_couleur) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssisss", $nom, $couleur_primaire, $couleur_secondaire, $police, $taille_police, $fond_image, $bordure_style, $bordure_couleur);

    if ($stmt->execute()) {
        echo "<p>Thème ajouté avec succès.</p>";
    } else {
        echo "<p>Erreur lors de l'ajout du thème.</p>";
    }
    $stmt->close();
}

// Récupérer les thèmes existants
$result = $conn->query("SELECT * FROM themes");
$themes = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Lato&family=Open+Sans&family=Montserrat&family=Raleway&family=Poppins&family=Oswald&family=Source+Sans+Pro&family=Noto+Sans&family=Merriweather&family=Playfair+Display&family=Quicksand&family=Cabin&display=swap" rel="stylesheet">
    <title>Gestion des Thèmes</title>
</head>
<body>
<div class="container mt-5 col-6 text-center">
    <h1>Gestion des Thèmes</h1>

    <form method="POST" action="">
        <div class="form-group">
            <label for="nom">Nom du Thème</label>
            <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="couleur_primaire">Couleur Primaire</label>
            <input type="color" name="couleur_primaire" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="couleur_secondaire">Couleur Secondaire</label>
            <input type="color" name="couleur_secondaire" class="form-control" required>
        </div>
        <div class="form-group">
    <label for="police">Police</label>
    <select name="police" class="form-control" required>
        <option value="Arial">Arial</option>
        <option value="Verdana">Verdana</option>
        <option value="Tahoma">Tahoma</option>
        <option value="Trebuchet MS">Trebuchet MS</option>
        <option value="Times New Roman">Times New Roman</option>
        <option value="Georgia">Georgia</option>
        <option value="Garamond">Garamond</option>
        <option value="Comic Sans MS">Comic Sans MS</option>
        <option value="Impact">Impact</option>
        <option value="Courier New">Courier New</option>
        <option value="Lucida Console">Lucida Console</option>
        <option value="Palatino Linotype">Palatino Linotype</option>
        <option value="Arial Black">Arial Black</option>
        <option value="Century Gothic">Century Gothic</option>
        <option value="Franklin Gothic Medium">Franklin Gothic Medium</option>
        <option value="Calibri">Calibri</option>
        <option value="Segoe UI">Segoe UI</option>
        <option value="Roboto">Roboto</option>
        <option value="Open Sans">Open Sans</option>
        <option value="Lato">Lato</option>
        <option value="Montserrat">Montserrat</option>
        <option value="Raleway">Raleway</option>
        <option value="Poppins">Poppins</option>
        <option value="Oswald">Oswald</option>
        <option value="Source Sans Pro">Source Sans Pro</option>
        <option value="Noto Sans">Noto Sans</option>
        <option value="Merriweather">Merriweather</option>
        <option value="Playfair Display">Playfair Display</option>
        <option value="Quicksand">Quicksand</option>
        <option value="Cabin">Cabin</option>
     </select>
     </div>
        <div class="form-group">
            <label for="taille_police">Taille de la Police</label>
            <input type="number" name="taille_police" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="fond_image">Image de Fond (URL)</label>
            <input type="text" name="fond_image" class="form-control">
        </div>
        <div class="form-group">
            <label for="bordure_style">Style de Bordure</label>
            <select name="bordure_style" class="form-control">
                <option value="none">Aucune</option>
                <option value="solid">Solide</option>
                <option value="dashed">Tirets</option>
                <option value="dotted">Pointillés</option>
            </select>
        </div>
        <div class="form-group">
            <label for="bordure_couleur">Couleur de Bordure</label>
            <input type="color" name="bordure_couleur" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Ajouter Thème</button>
    </form>

    <h3 class="mt-5">Thèmes Existants</h3>
    <ul class="list-group">
        <?php foreach ($themes as $theme): ?>
            <li class="list-group-item">
                <?php echo htmlspecialchars($theme['nom']); ?> - Couleur Primaire: <?php echo htmlspecialchars($theme['couleur_primaire']); ?>
                <br>Couleur Secondaire: <?php echo htmlspecialchars($theme['couleur_secondaire']); ?>
                <br>Police: <?php echo htmlspecialchars($theme['police']); ?>, Taille: <?php echo htmlspecialchars($theme['taille_police']); ?>
                <br><small>Créé le: <?php echo htmlspecialchars($theme['created_at']); ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="text-center mb-3">
<h2>Appliquer le themes existant</h2>
<a class="btn btn-secondary" href="apptheme.php">mes themes</a>
</div>
<br><br>
</body>
</html>

