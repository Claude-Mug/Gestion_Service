<?php
session_start(); // Démarrer la session

// Mot de passe requis pour accéder à la page
$requiredPassword = "Moscouw03";

// Vérifier si le mot de passe a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['password']) && $_POST['password'] === $requiredPassword) {
        $_SESSION['authenticated'] = true; // Authentifier l'utilisateur
    } else {
        $error = "Mot de passe incorrect.";
    }
}

// Vérification de l'authentification
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Afficher le formulaire de mot de passe
    ?>
    <div class="container mt-5">
        <h2>Accès Administratif</h2>
        <form action="" method="POST">
            <label for="password">Entrez le mot de passe :</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" value="Accéder" class="btn btn-primary">
        </form>
        <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
    </div>
    <?php
    exit; // Terminer l'exécution du script
}

// Connexion à la base de données
$host = 'localhost';
$dbname = 'Services';
$username = 'root';
$passwordDB = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer toutes les commandes
    $stmtOrders = $pdo->prepare("SELECT * FROM CommandDomicile");
    $stmtOrders->execute();
    
    echo "<div class='container mt-5'>";
    echo "<h2>Toutes les Commandes</h2>";
    
    if ($stmtOrders->rowCount() > 0) {
        echo "<form action='' method='POST'>";
        echo "<table class='table table-bordered mt-4'>";
        echo "<thead class='bg-warning opacity-50'>
                <tr>
                    <th>Service</th>
                    <th>Prix (Fbu)</th>
                    <th>Nom Client</th>
                    <th>Email client</th>
                    <th>Statut</th>
                    <th>Action</th>                   
                </tr>
              </thead>
              <tbody>";
        
        while ($row = $stmtOrders->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['service']) . "</td>
                    <td><input type='number' name='prix[" . $row['id'] . "]' value='" . htmlspecialchars($row['prix']) . "' step='0.01'></td> <!-- Champ modifiable pour le prix -->
                    <td>" . htmlspecialchars($row['user_name']) . "</td>
                    <td>" . htmlspecialchars($row['user_email']) . "</td>
                    <td>
                        <select name='statut[" . $row['id'] . "]'> <!-- Sélecteur pour le statut -->
                            <option value='En attente'" . ($row['statut'] === 'En attente' ? ' selected' : '') . ">En attente</option>
                            <option value='En cours'" . ($row['statut'] === 'En cours' ? ' selected' : '') . ">En cours</option>
                            <option value='Terminée'" . ($row['statut'] === 'Terminée' ? ' selected' : '') . ">Terminée</option>
                        </select>
                    </td>
                    <td>
                        <button type='submit' name='ajuster' value='" . $row['id'] . "' class='btn btn-warning'>Ajuster</button>
                    </td>
                  </tr>";
        }
        
        echo "</tbody></table>";
        echo "</form>";
    } else {
        echo "<p>Aucune commande trouvée.</p>";
    }

    // Traitement du formulaire lors de la soumission pour ajuster le prix et le statut
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajuster'])) {
        $orderId = $_POST['ajuster'];
        $newPrice = isset($_POST['prix'][$orderId]) ? $_POST['prix'][$orderId] : null; // Récupérer le nouveau prix
        $newStatus = $_POST['statut'][$orderId]; // Récupérer le nouveau statut

        // Mise à jour des données dans la base de données
        try {
            if ($newStatus === 'Terminée') {
                // Récupérer les détails de la commande
                $stmtDetails = $pdo->prepare("SELECT * FROM CommandDomicile WHERE id = :id");
                $stmtDetails->bindParam(':id', $orderId);
                $stmtDetails->execute();
                $orderDetails = $stmtDetails->fetch(PDO::FETCH_ASSOC);

                // Insérer dans la table Historique
                $stmtInsert = $pdo->prepare("INSERT INTO Historique (service, prix, user_name, user_email, statut, created_at) VALUES (:service, :prix, :user_name, :user_email, :statut, NOW())");
                $stmtInsert->bindParam(':service', $orderDetails['service']);
                $stmtInsert->bindParam(':prix', $orderDetails['prix']);
                $stmtInsert->bindParam(':user_name', $orderDetails['user_name']);
                $stmtInsert->bindParam(':user_email', $orderDetails['user_email']);
                $stmtInsert->bindParam(':statut', $newStatus);
                $stmtInsert->execute();

                // Supprimer la commande de la table CommandDomicile
                $stmtDelete = $pdo->prepare("DELETE FROM CommandDomicile WHERE id = :id");
                $stmtDelete->bindParam(':id', $orderId);
                $stmtDelete->execute();

                echo "<p class='text-success'>Commande terminée, déplacée vers l'historique avec succès!</p>";
            } else {
                // Mise à jour des données pour les autres statuts
                $stmtUpdate = $pdo->prepare("UPDATE CommandDomicile SET prix = :prix, statut = :statut WHERE id = :id");
                $stmtUpdate->bindParam(':prix', $newPrice);
                $stmtUpdate->bindParam(':statut', $newStatus); // Lier le nouveau statut
                $stmtUpdate->bindParam(':id', $orderId);
                $stmtUpdate->execute();

                echo "<p class='text-success'>Prix et statut ajustés avec succès!</p>";
            }
            // Rafraîchir la page pour voir les changements
            header("Refresh:0");
        } catch (PDOException $e) {
            echo "<p class='text-danger'>Erreur lors de la mise à jour : " . $e->getMessage() . "</p>";
        }
    }
} catch (PDOException $e) {
    echo "<p class='text-danger'>Erreur de connexion : " . $e->getMessage() . "</p>";
}
?>

<!-- Importer Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">