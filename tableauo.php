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

// Traitement de la connexion de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Requête pour vérifier l'email
    $stmt = $conn->prepare("SELECT id, nom FROM clients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Authentification réussie
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id']; // Stocker l'ID de l'utilisateur dans la session
        $_SESSION['user_name'] = $user['nom']; // Stocker le nom de l'utilisateur dans la session
    } else {
        echo "<p>Identifiant invalide.</p>";
    }
    $stmt->close();
}

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, afficher le formulaire de connexion
    echo '
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Se connecter</button>
    </form>';
    exit();
}

// Récupérer le nom de l'utilisateur
$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

// Vérifier si un destinataire a été sélectionné
$selected_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

// Récupération des messages
if ($selected_user_id) {
    // Afficher les messages privés du destinataire sélectionné
    $sql = "SELECT m.*, 
                   COALESCE(a.nom, p.nom, c.nom) AS nom_envoyeur 
            FROM messages m 
            LEFT JOIN admins a ON m.id_admin = a.id 
            LEFT JOIN prestataire p ON m.id_prestataire = p.id 
            LEFT JOIN clients c ON m.id_client = c.id 
            WHERE m.is_private = 1 AND (m.id_client = ? OR m.id_client = ?)
            ORDER BY m.timestamp DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $selected_user_id);
} else {
    // Afficher les messages du forum par défaut
    $sql = "SELECT m.*, 
                   COALESCE(a.nom, p.nom, c.nom) AS nom_envoyeur 
            FROM messages m 
            LEFT JOIN admins a ON m.id_admin = a.id 
            LEFT JOIN prestataire p ON m.id_prestataire = p.id 
            LEFT JOIN clients c ON m.id_client = c.id 
            WHERE m.is_private = 0
            ORDER BY m.timestamp DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
$allMessages = $result->fetch_all(MYSQLI_ASSOC);  

// Envoi d'un message  
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {  
    $message = $_POST['message'];  
    $file_name = $_FILES['file']['name'] ?? null;  
    $file_path = 'uploads/' . basename($file_name);  
    $destinataire_id = $_POST['destinataire_id'] ?? null; // ID du destinataire  
    $destinataire_type = $_POST['destinataire_type'] ?? null; // Type de destinataire
    $is_private = !empty($destinataire_id); // Déterminer si le message est privé  

    // Gérer le téléchargement de fichier
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            die("Erreur lors du téléchargement du fichier.");
        }
    } else {
        $file_name = null;
        $file_path = null;
    }

    if (!empty($message)) {  
        // Préparer la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO messages (message, file_name, file_path, is_private, timestamp, id_client, id_admin, id_prestataire) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)");  

        // Initialiser les ID des destinataires
        $id_admin = null;
        $id_prestataire = null;

        // Déterminer quel ID à utiliser
        if ($destinataire_type === 'admin') {
            $id_admin = $destinataire_id; // Utiliser l'ID de l'admin
        } elseif ($destinataire_type === 'prestataire') {
            $id_prestataire = $destinataire_id; // Utiliser l'ID du prestataire
        } else {
            $destinataire_id = null; // Pour les clients, id_client est déjà lié
        }

        // Lier les paramètres
        $stmt->bind_param("ssssiis", $message, $file_name, $file_path, $is_private, $user_id, $id_admin, $id_prestataire);

        // Exécuter la requête
        if (!$stmt->execute()) {
            die("Erreur lors de l'exécution de la requête : " . $stmt->error);
        }
        $stmt->close();

        // Redirection vers la même page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();  
    }  
}  

// Récupération des admins, prestataires et clients  
$admins = $conn->query("SELECT * FROM admins")->fetch_all(MYSQLI_ASSOC);  
$prestataires = $conn->query("SELECT * FROM prestataire")->fetch_all(MYSQLI_ASSOC);  
$clients = $conn->query("SELECT * FROM clients")->fetch_all(MYSQLI_ASSOC);  
?>  

<!DOCTYPE html>  
<html lang="fr">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <title>Messagerie</title>  
    <style>  
        .message-box {  
            margin-bottom: 15px;  
            padding: 10px;  
            border-radius: 5px;  
            background: #f8f9fa;  
            border: 1px solid #e9ecef;  
        }  
        .messages-container {  
            height: 400px;  
            overflow-y: auto;  
            margin-bottom: 20px;  
            border: 1px solid #dee2e6;  
            border-radius: 5px;  
            padding: 10px;  
            background: white;  
        }  
        .message-area {  
            display: flex;  
            align-items: center;  
            margin: auto;  
            padding: auto;  
        }  
        .input-group {  
            flex: 1;  
        }  
        .file-icon i {  
            font-size: 24px;  
            margin-right: 5px;  
        }  
        .send-button {  
            display: flex;  
            align-items: center;  
        }  
        .send-button button {  
            margin-left: 5px;  
        }  
    </style>  
</head>  
<body>  
<div class="container">  
    <h1 class="mt-5">Messagerie</h1>  
    <h5>Bienvenue, <?php echo htmlspecialchars($user_name); ?>!</h5> <!-- Affichage du nom de l'utilisateur -->

    <div class="row">  
        <div class="col-md-4 destinataires">  
            <h3>Envoyer à :</h3>  
            <button class="btn btn-info mb-2" id="sendToEveryone">Forum de Discussion</button>  
            <button class="btn btn-secondary mb-2" id="showAdmins">Admins</button>  
            <button class="btn btn-secondary mb-2" id="showPrestataires">Prestataires</button>  
            <button class="btn btn-secondary mb-2" id="showClients">Clients</button>  

            <div id="destinataireList" class="mt-2" style="display: none;">  
                <h5>Liste des Destinataires</h5>  
                <div id="adminsList" class="destinataireDetails" style="display: none;">  
                    <ul class="list-group">  
                        <?php foreach ($admins as $admin): ?>  
                            <li class="list-group-item">  
                                <?php echo htmlspecialchars($admin['nom'] . ' ' . $admin['prenom'] . ' - ' . $admin['role']); ?>  
                                <button class="btn btn-sm btn-primary selectDest" data-id="<?php echo $admin['id']; ?>" data-type="admin">Choisir</button>  
                            </li>  
                        <?php endforeach; ?>  
                    </ul>  
                </div>  
                <div id="prestatairesList" class="destinataireDetails" style="display: none;">  
                    <ul class="list-group">  
                        <?php foreach ($prestataires as $prestataire): ?>  
                            <li class="list-group-item">  
                                <?php echo htmlspecialchars($prestataire['nom'] . ' - ' . $prestataire['services']); ?>  
                                <button class="btn btn-sm btn-primary selectDest" data-id="<?php echo $prestataire['id']; ?>" data-type="prestataire">Choisir</button>  
                            </li>  
                        <?php endforeach; ?>  
                    </ul>  
                </div>  
                <div id="clientsList" class="destinataireDetails" style="display: none;">  
                    <ul class="list-group">  
                        <?php foreach ($clients as $client): ?>  
                            <li class="list-group-item">  
                                <?php echo htmlspecialchars($client['nom'] . ' ' . $client['prenom'] . ' - ' . $client['email']); ?>  
                                <button class="btn btn-sm btn-primary selectDest" data-id="<?php echo $client['id']; ?>" data-type="client">Choisir</button>  
                            </li>  
                        <?php endforeach; ?>  
                    </ul>  
                </div>  
            </div>  
        </div>  

        <div class="col-md-8">  
            <h3>Messages</h3>  
            <div class="messages-container mt-4">  
                <!-- Affichage des messages récupérés -->  
                <?php foreach ($allMessages as $msg): ?>  
                    <div class="message-box">  
                        <p><strong>
                            <?php if ($msg['is_private']): ?>
                                <a href="?user_id=<?php echo htmlspecialchars($msg['id_client']); ?>"><?php echo htmlspecialchars($msg['nom_envoyeur']); ?></a>
                            <?php else: ?>
                                <?php echo htmlspecialchars($msg['nom_envoyeur']); ?>
                            <?php endif; ?>
                        </strong>: <?php echo htmlspecialchars($msg['message']); ?></p>  
                        <?php if ($msg['file_path']): ?>  
                            <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $msg['file_name'])): ?>  
                                <img src="<?php echo htmlspecialchars($msg['file_path']); ?>" alt="<?php echo htmlspecialchars($msg['file_name']); ?>" style="max-width: 100%; height: auto;">  
                            <?php elseif (preg_match('/\.pdf$/i', $msg['file_name'])): ?>  
                                <p><a href="<?php echo htmlspecialchars($msg['file_path']); ?>" class="btn btn-link">Lire le PDF : <?php echo htmlspecialchars($msg['file_name']); ?></a></p>  
                            <?php else: ?>  
                                <p><a href="<?php echo htmlspecialchars($msg['file_path']); ?>" class="btn btn-link">Fichier joint : <?php echo htmlspecialchars($msg['file_name']); ?></a></p>  
                            <?php endif; ?>  
                        <?php endif; ?>  
                        <small>Posté le : <?php echo htmlspecialchars($msg['timestamp']); ?></small>  
                    </div>  
                <?php endforeach; ?>  
            </div>  
        </div>  
    </div>  

    <div class="message-area col-12 d-flex justify-content-center">  
        <form method="post" action="" enctype="multipart/form-data" id="message_form" style="width: 70%;">  
            <div class="input-group">  
                <input type="file" name="file" id="file" style="display: none;">  
                <label for="file" class="file-icon btn btn-outline-primary" title="Choisir un fichier">
                    <i class="bi bi-paperclip"></i> Choisir un fichier
                </label>  
                <textarea class="form-control" id="message" name="message" required placeholder="Écrire un message..."></textarea>  
                <input type="hidden" name="destinataire_id" id="destinataire_id_hidden">  
                <input type="hidden" name="destinataire_type" id="destinataire_type_hidden">  
                <div class="send-button">  
                    <button type="submit" class="btn btn-success">Envoyer</button>  
                </div>  
            </div>  
        </form>  
    </div>  
</div>  

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>  
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>  

<script>  
    $(document).ready(function() {  
        $('#sendToEveryone').on('click', function() {  
            $('#destinataireList').hide();  
            $('#destinataire_id_hidden').val('');  
            $('#destinataire_type_hidden').val('');  
        });  

        $('#showAdmins').on('click', function() {  
            $('#destinataireList').show();  
            $('.destinataireDetails').hide();  
            $('#adminsList').show();  
        });  

        $('#showPrestataires').on('click', function() {  
            $('#destinataireList').show();  
            $('.destinataireDetails').hide();  
            $('#prestatairesList').show();  
        });  

        $('#showClients').on('click', function() {  
            $('#destinataireList').show();  
            $('.destinataireDetails').hide();  
            $('#clientsList').show();  
        });  

        $('.selectDest').on('click', function() {  
            var id = $(this).data('id');  
            var type = $(this).data('type');  
            $('#destinataire_id_hidden').val(id);  
            $('#destinataire_type_hidden').val(type);  
            $('#destinataireList').hide();  
        });  
    });  
</script>  
</body>  
</html>