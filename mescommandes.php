<?php
session_start(); // Start the session

// Database connection
$host = 'localhost';
$dbname = 'Services';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if user ID is set
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Retrieve user ID from session

        // Fetch user information from Clients table
        $stmtUser = $pdo->prepare("SELECT nom, email FROM Clients WHERE id = :user_id");
        $stmtUser->bindParam(':user_id', $user_id);
        $stmtUser->execute();

        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        // Check if user exists
        if ($user) {
            echo "<div class='container mt-5'>";
            echo "<h2 class='text-center'>Bienvenue, " . htmlspecialchars($user['nom']) . "!</h2>";
            echo "<h1 class='text-center text-success'>Tous tes commandes s'affichent ici.</h1>";
            echo "<p>Nous sommes ravis de vous avoir parmi nous. Votre satisfaction est notre priorité.</p>";
            echo "<p>Les prix sont par defaut sont null en raisons des stanadrt du marche et de l'emplaire de ton tes demandes nous te conseillons de nous contacter ou discuter avec les admins enfin d'en savoir plus, ils serons mise a jour apres cette petite discution.</p>";

            // Fetch user orders
            $stmtOrders = $pdo->prepare("SELECT * FROM CommandDomicile WHERE user_id = :user_id");
            $stmtOrders->bindParam(':user_id', $user_id);
            $stmtOrders->execute();

            $totalFbu = 0; // Initialize total in Fbu
            $totalDollars = 0; // Initialize total in dollars
            $exchangeRate = 7000; // Exchange rate Fbu to dollar (example)

            // Check if there are results
            if ($stmtOrders->rowCount() > 0) {
                echo "<table class='table table-bordered mt-4'>";
                echo "<thead>
                        <tr>
                            <th>Nom du Service</th>
                            <th>Domaine</th>
                            <th>Type de Demande</th>
                            <th>Statut</th>
                            <th>Date de la Prestation</th>
                            <th>Commentaires</th>
                            <th>Prix (Fbu)</th>
                            <th>Prix (USD)</th>
                            <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>";

                // Display each order
                while ($row = $stmtOrders->fetch(PDO::FETCH_ASSOC)) {
                    $prixFbu = (float)$row['prix']; // Get price in Fbu
                    $prixDollars = $prixFbu / $exchangeRate; // Convert price to dollars

                    // Add to totals
                    $totalFbu += $prixFbu;
                    $totalDollars += $prixDollars;

                    echo "<tr>
                            <td>" . htmlspecialchars($row['service']) . "</td>
                            <td>" . htmlspecialchars($row['domaine']) . "</td>
                            <td>" . htmlspecialchars($row['request_type']) . "</td>
                            <td>" . htmlspecialchars($row['statut']) . "</td>
                            <td>" . htmlspecialchars($row['request_date']) . "</td>
                            <td>" . htmlspecialchars($row['user_comments']) . "</td>
                            <td>" . number_format($prixFbu, 2) . " Fbu</td>
                            <td>" . number_format($prixDollars, 2) . " USD</td>
                            <td>
                                <a href='modicommande.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Modifier</a>
                                <a href='supcommande.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cette commande ?\");'>Supprimer</a>
                            </td>
                          </tr>";
                }

                echo "</tbody></table>";

                // Display totals
                echo "<h5 class='text-right'>Total : " . number_format($totalFbu, 2) . " Fbu</h5>";
                echo "<h5 class='text-right'>Total : " . number_format($totalDollars, 2) . " USD</h5>";
            } else {
                echo "<p>Aucune commande trouvée pour cet utilisateur.</p>";
            }
            echo "</div>"; // End of .container
        } else {
            echo "<p>Utilisateur non trouvé.</p>";
        }
    } else {
        echo "<p>Veuillez vous connecter pour voir vos commandes.</p>";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!-- Import Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container mt-4">
        <div class="row mb-4">
            <h3 class="text-center text-decoration-underline">Choix du moyen de payement</h3>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <button class="btn btn-outline-primary btn-block" onclick="loadPage('carte.php')">
                    <i class="bi bi-credit-card"></i> Payer par carte bancaire
                </button>
            </div>
            <div class="col-md-4 mb-3">
                <button class="btn btn-outline-info btn-block" onclick="loadPage('paypal.php')">
                    <i class="bi bi-paypal"></i> Payer avec PayPal
                </button>
            </div>
            <div class="col-md-4 mb-3">
                <button class="btn btn-outline-success btn-block" onclick="loadPage('payerMobile.php')">
                    <i class="bi bi-phone"></i> Payer par mobile (FBU, $, €)
                </button>
            </div>
        </div>
        <div class="col text-center">
            <a href="index.php" class="btn btn-info btn-lg mx-2">Retour à la page d'accueil</a>
        </div>
        <br><br><br>

        <!-- Section pour l'iframe -->
        <div class="row">
            <div class="col">
                <iframe id="payment-iframe" src="" style="width: 100%; height: 500px; border: none; display: none;"></iframe>
            </div>
        </div>
        <br><br><br><br><br><br>
    </div>

    <script>
        function loadPage(page) {
            const iframe = document.getElementById('payment-iframe');
            iframe.src = page; // Définir la source de l'iframe
            iframe.style.display = 'block'; // Afficher l'iframe

            // Défilement automatique vers l'iframe
            iframe.scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>