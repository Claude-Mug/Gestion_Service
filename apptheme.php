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

// Récupérer tous les thèmes
$sql = "SELECT * FROM themes";
$result = $conn->query($sql);

// Vérifier si des thèmes existent
if ($result->num_rows > 0) {
    $themes = $result->fetch_all(MYSQLI_ASSOC); // Récupérer tous les thèmes dans un tableau
} else {
    echo '<p>Aucun thème disponible.</p>';
    exit();
}

// Appliquer le thème sélectionné ou le premier thème par défaut
$current_theme = null;
if (isset($_POST['theme_id'])) {
    $selected_theme_id = $_POST['theme_id'];
    $_SESSION['theme_id'] = $selected_theme_id; // Stocker le thème dans la session
}

// Vérifier si un thème a été sélectionné dans la session
if (isset($_SESSION['theme_id'])) {
    $stmt = $conn->prepare("SELECT * FROM themes WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['theme_id']);
    $stmt->execute();
    $theme_result = $stmt->get_result();
    $current_theme = $theme_result->fetch_assoc();
} else {
    // Appliquer le premier thème par défaut
    $current_theme = $themes[0];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Gestion des Thèmes</title>
    <style>
        body {
            background-color: <?php echo htmlspecialchars($current_theme['couleur_secondaire']); ?>;
            color: <?php echo htmlspecialchars($current_theme['couleur_primaire']); ?>;
            font-family: <?php echo htmlspecialchars($current_theme['police']); ?>;
            font-size: <?php echo htmlspecialchars($current_theme['taille_police']); ?>px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Gestion des Thèmes</h1>

        <form method="POST" action="">
            <div class="form-group">
                <label for="theme_id">Choisissez un thème :</label>
                <select name="theme_id" id="theme_id" class="form-control" required>
                    <?php foreach ($themes as $theme): ?>
                        <option value="<?php echo htmlspecialchars($theme['id']); ?>"
                            <?php echo (isset($current_theme) && $current_theme['id'] == $theme['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($theme['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Appliquer le Thème</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close(); // Fermer la connexion à la base de données
?>