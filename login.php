<?php
session_start(); // Démarrer la session

$host = 'localhost';
$dbname = 'Services';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];

        // Vérifier si l'utilisateur existe
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Vérifier le mot de passe
            if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];

                // Vérifier s'il y a déjà une entrée dans l'historique de connexion dans les 10 dernières minutes
                $stmt_historique_check = $pdo->prepare("SELECT * FROM historique_connexion WHERE client_id = ? AND date_connexion > NOW() - INTERVAL 10 MINUTE");
                $stmt_historique_check->execute([$user['id']]);
                
                if ($stmt_historique_check->rowCount() === 0) {
                    // Enregistrer l'historique de connexion
                    $date_connexion = date('Y-m-d H:i:s');
                    $stmt_historique = $pdo->prepare("INSERT INTO historique_connexion (client_id, date_connexion, nom, email) VALUES (?, ?, ?, ?)");
                    $stmt_historique->execute([$user['id'], $date_connexion, $user['nom'], $user['email']]);
                }

                // Définir un cookie
                setcookie("user_id", $user['id'], time() + (30 * 24 * 60 * 60), "/");

                // Réponse JSON pour la connexion réussie
                echo json_encode(['success' => true, 'message' => 'Connexion réussie !', 'redirect' => 'Prix.php']);
                exit; // Terminer le script
            } else {
                // Mot de passe incorrect
                echo json_encode(['success' => false, 'message' => 'Le mot de passe est incorrect.']);
            }
        } else {
            // Email non trouvé
            echo json_encode(['success' => false, 'message' => 'L\'email n\'est pas enregistré.']);
        }
        exit; // Terminer le script
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => "Erreur de connexion : " . $e->getMessage()]);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <?php if (isset($error)): ?>
        <div style="color:red;"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <label for="email">Email :</label>
        <input type="email" name="email" required>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>