<?php  
session_start(); // Démarrer la session

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

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: loginAdmin.php"); // Rediriger vers la page de connexion
    exit();
}

$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['admin_name'];

// Traitement de la modification du mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ancien_mot_de_passe'])) {
    $ancien_mot_de_passe = $_POST['ancien_mot_de_passe'];
    $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];
    $confirmation_mot_de_passe = $_POST['confirmation_mot_de_passe'];

    // Vérifier l'ancien mot de passe
    $stmt = $conn->prepare("SELECT password FROM admins WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if ($admin['password'] === $ancien_mot_de_passe) {
            if ($nouveau_mot_de_passe === $confirmation_mot_de_passe) {
                // Mettre à jour le mot de passe
                $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
                $stmt->bind_param("si", $nouveau_mot_de_passe, $user_id);
                if ($stmt->execute()) {
                    echo "<p>Mot de passe modifié avec succès.</p>";
                } else {
                    echo "<p>Erreur lors de la modification du mot de passe.</p>";
                }
            } else {
                echo "<p>Les nouveaux mots de passe ne correspondent pas.</p>";
            }
        } else {
            echo "<p>Ancien mot de passe incorrect.</p>";
        }
    }

    $stmt->close();
}
?>  

<!DOCTYPE html>  
<html lang="fr">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">  
    <title>Modifier Mot de Passe</title>  
    <style>  
        .form-group { margin-bottom: 15px; }  
        .input-group { position: relative; }  
        .toggle-password { position: absolute; right: 10px; top: 10px; cursor: pointer; }  
    </style>  
</head>  
<body>  
<div class="container mt-5 text-center col-6 bg-success-subtle">  
    <h1>Modifier le Mot de Passe</h1>  
    <h5>Bienvenue, <?php echo htmlspecialchars($user_name); ?>!</h5>  

    <form method="POST" action="">  
        <div class="form-group">  
            <label for="ancien_mot_de_passe">Ancien Mot de Passe</label>  
            <div class="input-group">  
                <input type="password" name="ancien_mot_de_passe" class="form-control" required>  
                <span class="input-group-addon toggle-password" onclick="togglePasswordVisibility('ancien_mot_de_passe')">👁️</span>
            </div>  
        </div>  
        <div class="form-group">  
            <label for="nouveau_mot_de_passe">Nouveau Mot de Passe</label>  
            <div class="input-group">  
                <input type="password" name="nouveau_mot_de_passe" class="form-control" required>  
                <span class="input-group-addon toggle-password" onclick="togglePasswordVisibility('nouveau_mot_de_passe')">👁️</span>
            </div>  
        </div>  
        <div class="form-group">  
            <label for="confirmation_mot_de_passe">Confirmer Nouveau Mot de Passe</label>  
            <div class="input-group">  
                <input type="password" name="confirmation_mot_de_passe" class="form-control" required>  
                <span class="input-group-addon toggle-password" onclick="togglePasswordVisibility('confirmation_mot_de_passe')">👁️</span>
            </div>  
        </div>  
        <button type="submit" class="btn btn-primary">Modifier le Mot de Passe</button>  
    </form>  
</div>  

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>  
<script>  
    function togglePasswordVisibility(inputId) {  
        const input = document.querySelector(`input[name="${inputId}"]`);  
        input.type = input.type === "password" ? "text" : "password";  
    }  
</script>  
</body>  
</html>