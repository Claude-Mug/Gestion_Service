<?php
// Vérifiez si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des valeurs du formulaire
    $email = $_POST['email'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    // Vérification des mots de passe
    if ($password1 === $password2) {
        // Connexion à la base de données Services
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

        // Vérifiez si l'utilisateur existe dans la table clients
        $stmt = $conn->prepare("SELECT * FROM clients WHERE email = ? AND nom = ? AND prenom = ?");
        $stmt->bind_param("sss", $email, $nom, $prenom);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Mise à jour du mot de passe
            $hashed_password = password_hash($password1, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE clients SET password = ? WHERE email = ?");
            $update_stmt->bind_param("ss", $hashed_password, $email);
            $update_stmt->execute();

            echo "Mot de passe réinitialisé avec succès !";
        } else {
            echo "Aucun utilisateur trouvé avec ces informations.";
        }

        // Fermeture de la connexion
        $conn->close();
    } else {
        echo "Les mots de passe ne correspondent pas.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le Mot de Passe</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script>
        function togglePasswordVisibility(id) {
            var passwordField = document.getElementById(id);
            passwordField.type = (passwordField.type === 'password') ? 'text' : 'password';
        }
    </script>
</head>
<body>
    <div class="container mt-5 col-5 bg-danger-subtle">
        <h2>Réinitialiser le Mot de Passe</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom:</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            <div class="form-group position-relative">
                <label for="password1">Nouveau Mot de Passe:</label>
                <input type="password" class="form-control" id="password1" name="password1" required>
                <span class="position-absolute" style="right: 10px; top: 35%; cursor: pointer;" onclick="togglePasswordVisibility('password1')">
                    <i class="bi bi-eye" id="eye1"></i>
                </span>
            </div>
            <div class="form-group position-relative">
                <label for="password2">Confirmer le Mot de Passe:</label>
                <input type="password" class="form-control" id="password2" name="password2" required>
                <span class="position-absolute" style="right: 10px; top: 35%; cursor: pointer;" onclick="togglePasswordVisibility('password2')">
                    <i class="bi bi-eye" id="eye2"></i>
                </span>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Réinitialiser</button>
        </form>
    </div>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
</body>
</html>