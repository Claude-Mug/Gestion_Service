<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Connexion à la base de données
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "services";       

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Vérifier si l'ID de la commande est passé en GET
if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']);

    // Récupérer les détails de la commande
    $sql = "SELECT * FROM commanddomicile WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
    } else {
        die("Commande non trouvée.");
    }
} else {
    die("ID de commande non spécifié.");
}

// Traitement de la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les valeurs du formulaire
    $nom_service = $_POST['nom_service'];
    $domaine = $_POST['domaine'];
    $service = $_POST['service'];
    $other_service = $_POST['other_service'];
    $request_type = $_POST['request_type'];
    $request_date = $_POST['request_date'];
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_phone = $_POST['user_phone'];
    $user_address = $_POST['user_address'];
    $user_comments = $_POST['user_comments'];

    // Mettre à jour la commande
    $sql = "UPDATE commanddomicile SET nom_service=?, domaine=?, service=?, other_service=?, request_type=?, request_date=?, user_name=?, user_email=?, user_phone=?, user_address=?, user_comments=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssi", $nom_service, $domaine, $service, $other_service, $request_type, $request_date, $user_name, $user_email, $user_phone, $user_address, $user_comments, $order_id);

    if ($stmt->execute()) {
        header("Location: mescommandes.php?message=Commande mise à jour avec succès.");
        exit;
    } else {
        echo "Erreur lors de la mise à jour de la commande : " . $stmt->error;
    }
}

// Affichage du formulaire de modification
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Commande</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5 col-7 bg-danger-subtle">
    <h1>Modifier Commande</h1>
    <form action="" method="POST">
    <div class="form-group">
            <label for="service">Type de Service</label>
            <select class="form-control" name="service" id="service" required>
                <option value="domicile" <?php echo ($order['service'] == 'domicile') ? 'selected' : ''; ?>>À Domicile</option>
                <option value="reparation" <?php echo ($order['service'] == 'reparation') ? 'selected' : ''; ?>>Réparation</option>
            </select>
        </div>
        <div class="form-group">
            <label for="domaine">Domaine</label>
            <input type="text" class="form-control" name="domaine" id="domaine" value="<?php echo htmlspecialchars($order['domaine']); ?>" required>
        </div>
        <div class="form-group">
            <label for="service">Service</label>
            <input type="text" class="form-control" name="service" id="service" value="<?php echo htmlspecialchars($order['service']); ?>" required>
        </div>
        <div class="form-group">
            <label for="other_service">Autre Service</label>
            <input type="text" class="form-control" name="other_service" id="other_service" value="<?php echo htmlspecialchars($order['other_service']); ?>">
        </div>
        <div class="form-group">
            <label for="request_type">Type de Demande</label>
            <select class="form-control" name="request_type" id="request_type" required>
                <option value="urgent" <?php echo ($order['request_type'] == 'urgent') ? 'selected' : ''; ?>>Urgent</option>
                <option value="normal" <?php echo ($order['request_type'] == 'normal') ? 'selected' : ''; ?>>Normal</option>
            </select>
        </div>
        <div class="form-group">
            <label for="request_date">Date de Demande</label>
            <input type="date" class="form-control" name="request_date" id="request_date" value="<?php echo htmlspecialchars($order['request_date']); ?>">
        </div>
        <div class="form-group">
            <label for="user_name">Nom de l'utilisateur</label>
            <input type="text" class="form-control" name="user_name" id="user_name" value="<?php echo htmlspecialchars($order['user_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="user_email">Email de l'utilisateur</label>
            <input type="email" class="form-control" name="user_email" id="user_email" value="<?php echo htmlspecialchars($order['user_email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="user_phone">Téléphone de l'utilisateur</label>
            <input type="tel" class="form-control" name="user_phone" id="user_phone" value="<?php echo htmlspecialchars($order['user_phone']); ?>" required>
        </div>
        <div class="form-group">
            <label for="user_address">Adresse de l'utilisateur</label>
            <input type="text" class="form-control" name="user_address" id="user_address" value="<?php echo htmlspecialchars($order['user_address']); ?>" required>
        </div>
        <div class="form-group">
            <label for="user_comments">Commentaires</label>
            <textarea class="form-control" name="user_comments" id="user_comments"><?php echo htmlspecialchars($order['user_comments']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>
    <br><br><br><br><br>
</body>
</html>