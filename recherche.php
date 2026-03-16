<?php
header('Content-Type: application/json');

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

try {
    // 2. Vérification de la requête
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée');
    }

    // 3. Récupération du terme de recherche
    $searchTerm = $_POST['search_query'] ?? '';
    if (empty($searchTerm)) {
        throw new Exception('Terme de recherche vide');
    }

    // 4. Configuration des tables et colonnes à rechercher
    $tables = [
        'admins' => ['nom', 'email'],
        'avis' => ['commentaire', 'client_nom'],
        // Ajoutez toutes vos tables ici...
        'services' => ['nom', 'description']
    ];

    $results = [];
    $searchPattern = '%' . $searchTerm . '%';

    // 5. Exécution des requêtes
    foreach ($tables as $table => $columns) {
        $conditions = implode(' LIKE ? OR ', $columns) . ' LIKE ?';
        $params = array_fill(0, count($columns), $searchPattern);
        
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE $conditions LIMIT 5");
        $stmt->execute($params);
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = [
                'table' => $table,
                'id' => $row['id'],
                'data' => $row,
                'context' => "Trouvé dans $table"
            ];
        }
    }

    // 6. Retour des résultats
    echo json_encode([
        'success' => true,
        'results' => $results
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}