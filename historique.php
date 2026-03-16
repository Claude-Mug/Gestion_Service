<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'Services';
$username = 'root';
$passwordDB = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer toutes les entrées de l'historique
    $stmt = $pdo->prepare("SELECT * FROM Historique");
    $stmt->execute();

    echo "<div class='container mt-5'>";
    echo "<h2 class='text-center'>Historique des Commandes</h2>";

    if ($stmt->rowCount() > 0) {
        echo "<table class='table table-bordered mt-4'>";
        echo "<thead class='bg-warning opacity-50'>
                <tr>
                    <th>ID</th>
                    <th>Service</th>
                    <th>Prix (Fbu)</th>
                    <th>Nom Client</th>
                    <th>Email Client</th>
                    <th>Statut</th>
                    <th>Date de Création</th>
                </tr>
              </thead>
              <tbody>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['id']) . "</td>
                    <td>" . htmlspecialchars($row['service']) . "</td>
                    <td>" . htmlspecialchars($row['prix']) . "</td>
                    <td>" . htmlspecialchars($row['user_name']) . "</td>
                    <td>" . htmlspecialchars($row['user_email']) . "</td>
                    <td>" . htmlspecialchars($row['statut']) . "</td>
                    <td>" . htmlspecialchars($row['created_at']) . "</td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>Aucun enregistrement trouvé dans l'historique.</p>";
    }

} catch (PDOException $e) {
    echo "<p class='text-danger'>Erreur de connexion : " . $e->getMessage() . "</p>";
}
?>

<!-- Importer Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">