<?php  
session_start();  

// Connexion à la base de données  
$host = 'localhost';  
$db = 'Services';  
$user = 'root';  
$pass = '';  

try {  
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);  
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
} catch (PDOException $e) {  
    echo "Erreur de connexion : " . $e->getMessage();  
}  

// Traitement de la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Vérifiez si l'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Enregistrez l'ID et le nom de l'utilisateur dans la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nom']; // Assurez-vous d'avoir cette colonne dans votre table
        header("Location: discuter.php"); // Redirigez vers la page de messagerie
        exit();
    } else {
        $error = "L'utilisateur n'existe pas.";
    }
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