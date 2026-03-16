<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Générales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Statistiques Générales</h2>
        
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

            // Statistiques Générales
            echo '<div class="row">';

            // Statistiques pour la table Clients
            try {
                $stmt = $pdo->prepare("SELECT COUNT(*) AS total_clients, MAX(created_at) AS dernier_client, MIN(created_at) AS premier_client FROM clients");
                $stmt->execute();
                $clients_stats = $stmt->fetch(PDO::FETCH_ASSOC);

                echo '<div class="col-4">';
                echo '<h3>Statistiques Clients</h3>';
                echo '<div class="alert alert-info">';
                echo '<h4>Total Clients: ' . $clients_stats['total_clients'] . '</h4>';
                echo '<h5>Dernier Client: ' . $clients_stats['dernier_client'] . '</h5>';
                echo '<h5>Premier Client: ' . $clients_stats['premier_client'] . '</h5>';
                echo '</div></div>';
            } catch (PDOException $e) {
                echo '<div class="col-4">';
                echo '<h3>Statistiques Clients</h3>';
                echo '<div class="alert alert-danger">';
                echo '<h4>Erreur lors de la récupération des clients: ' . $e->getMessage() . '</h4>';
                echo '</div></div>';
            }

            // Statistiques pour la table Admins
            try {
                $stmt = $pdo->prepare("
                    SELECT 
                        COUNT(*) AS total_admins,
                        (SELECT nom FROM admins ORDER BY id ASC LIMIT 1) AS premier_admin_nom,
                        (SELECT role FROM admins ORDER BY id ASC LIMIT 1) AS premier_admin_role,
                        (SELECT nom FROM admins ORDER BY id DESC LIMIT 1) AS dernier_admin_nom,
                        (SELECT role FROM admins ORDER BY id DESC LIMIT 1) AS dernier_admin_role
                    FROM admins
                ");
                $stmt->execute();
                $admin_stats = $stmt->fetch(PDO::FETCH_ASSOC);

                echo '<div class="col-4">';
                echo '<h3>Statistiques Admins</h3>';
                echo '<div class="alert alert-info">';
                echo '<h4>Total Admins: ' . $admin_stats['total_admins'] . '</h4>';
                echo '<h5>Premier Admin: ' . $admin_stats['premier_admin_nom'] . ' (Role: ' . $admin_stats['premier_admin_role'] . ')</h5>';
                echo '<h5>Dernier Admin: ' . $admin_stats['dernier_admin_nom'] . ' (Role: ' . $admin_stats['dernier_admin_role'] . ')</h5>';
                echo '</div></div>';
            } catch (PDOException $e) {
                echo '<div class="col-4">';
                echo '<h3>Statistiques Admins</h3>';
                echo '<div class="alert alert-danger">';
                echo '<h4>Erreur lors de la récupération des admins: ' . $e->getMessage() . '</h4>';
                echo '</div></div>';
            }

            // Statistiques pour la table Prestataires
            try {
                $stmt = $pdo->prepare("
                    SELECT 
                        COUNT(*) AS total_prestataires,
                        (SELECT nom FROM prestataire ORDER BY id ASC LIMIT 1) AS premier_prestataire_nom,
                        (SELECT nom FROM prestataire ORDER BY id DESC LIMIT 1) AS dernier_prestataire_nom
                    FROM prestataire
                ");
                $stmt->execute();
                $prestataire_stats = $stmt->fetch(PDO::FETCH_ASSOC);

                echo '<div class="col-4">';
                echo '<h3>Statistiques Prestataires</h3>';
                echo '<div class="alert alert-info">';
                echo '<h4>Total Prestataires: ' . $prestataire_stats['total_prestataires'] . '</h4>';
                echo '<h5>Premier Prestataire: ' . $prestataire_stats['premier_prestataire_nom'] . '</h5>';
                echo '<h5>Dernier Prestataire: ' . $prestataire_stats['dernier_prestataire_nom'] . '</h5>';
                echo '</div></div>';
            } catch (PDOException $e) {
                echo '<div class="col-4">';
                echo '<h3>Statistiques Prestataires</h3>';
                echo '<div class="alert alert-danger">';
                echo '<h4>Erreur lors de la récupération des prestataires: ' . $e->getMessage() . '</h4>';
                echo '</div></div>';
            }
            

            // Statistiques pour la table NosServices
 try {
     $stmt = $pdo->prepare("
        SELECT 
            SUM(CASE WHEN service_domicile IS NOT NULL THEN 1 ELSE 0 END) AS total_service_domicile,
            SUM(CASE WHEN service_reparation IS NOT NULL THEN 1 ELSE 0 END) AS total_service_reparation,
            (SUM(CASE WHEN service_domicile IS NOT NULL THEN 1 ELSE 0 END) + 
             SUM(CASE WHEN service_reparation IS NOT NULL THEN 1 ELSE 0 END)) AS total_services
        FROM nosservices
    ");
    $stmt->execute();
    $nosservices_stats = $stmt->fetch(PDO::FETCH_ASSOC);

    echo '<div class="col-4">';
    echo '<h3>Statistiques Nos Services</h3>';
    echo '<div class="alert alert-info">';
    echo '<h4>Total Services: ' . $nosservices_stats['total_services'] . '</h4>';
    echo '<h5>Total Services Domicile: ' . $nosservices_stats['total_service_domicile'] . '</h5>';
    echo '<h5>Total Services Réparation: ' . $nosservices_stats['total_service_reparation'] . '</h5>';
    echo '</div></div>';
} catch (PDOException $e) {
    echo '<div class="col-4">';
    echo '<h3>Statistiques Nos Services</h3>';
    echo '<div class="alert alert-danger">';
    echo '<h4>Erreur lors de la récupération des nos services: ' . $e->getMessage() . '</h4>';
    echo '</div></div>';
}

            // Statistiques pour la table Services
            try {
                $stmt = $pdo->prepare("SELECT COUNT(*) AS total_services, MAX(prix) AS prix_max, MIN(prix) AS prix_min FROM services");
                $stmt->execute();
                $services_stats = $stmt->fetch(PDO::FETCH_ASSOC);

                echo '<div class="col-4">';
                echo '<h3>Statistiques Services</h3>';
                echo '<div class="alert alert-info">';
                echo '<h4>Total Services: ' . $services_stats['total_services'] . '</h4>';
                echo '<h5>Prix Max: $' . $services_stats['prix_max'] . '</h5>';
                echo '<h5>Prix Min: $' . $services_stats['prix_min'] . '</h5>';
                echo '</div></div>';
            } catch (PDOException $e) {
                echo '<div class="col-4">';
                echo '<h3>Statistiques Services</h3>';
                echo '<div class="alert alert-danger">';
                echo '<h4>Erreur lors de la récupération des services: ' . $e->getMessage() . '</h4>';
                echo '</div></div>';
            }

            // Statistiques pour la table Revenus
            try {
                $stmt = $pdo->prepare("SELECT COUNT(*) AS total_revenus, SUM(montant) AS montant_total, AVG(montant) AS montant_moyen FROM revenus");
                $stmt->execute();
                $revenus_stats = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Conversion en dollars
                $montant_total_usd = $revenus_stats['montant_total'] / 7000;
                $montant_moyen_usd = $revenus_stats['montant_moyen'] / 7000;

                echo '<div class="col-4">';
                echo '<h3>Statistiques Revenus</h3>';
                echo '<div class="alert alert-info">';
                echo '<h4>Total Revenus: ' . $revenus_stats['total_revenus'] . ' FB</h4>';
                echo '<h5>Montant Total: $' . $revenus_stats['montant_total'] . ' FB (' . number_format($montant_total_usd, 2) . ' $)</h5>';
                echo '<h5>Montant Moyen: $' . $revenus_stats['montant_moyen'] . ' FB (' . number_format($montant_moyen_usd, 2) . ' $)</h5>';
                echo '</div></div>';
            } catch (PDOException $e) {
                echo '<div class="col-4">';
                echo '<h3>Statistiques Revenus</h3>';
                echo '<div class="alert alert-danger">';
                echo '<h4>Erreur lors de la récupération des revenus: ' . $e->getMessage() . '</h4>';
                echo '</div></div>';
            }

            // Statistiques pour la table CommandDomicile
            try {
                $stmt = $pdo->prepare("SELECT COUNT(*) AS total_commandes, SUM(prix) AS montant_total, AVG(prix) AS montant_moyen FROM commanddomicile");
                $stmt->execute();
                $commandes_stats = $stmt->fetch(PDO::FETCH_ASSOC);

                // Conversion en dollars
                $montant_total_commandes_usd = $commandes_stats['montant_total'] / 7000;
                $montant_moyen_commandes_usd = $commandes_stats['montant_moyen'] / 7000;

                echo '<div class="col-4">';
                echo '<h3>Statistiques Commandes</h3>';
                echo '<div class="alert alert-info">';
                echo '<h4>Total Commandes: ' . $commandes_stats['total_commandes'] . '</h4>';
                echo '<h5>Montant Total: $' . $commandes_stats['montant_total'] . ' FB (' . number_format($montant_total_commandes_usd, 2) . ' $)</h5>';
                echo '<h5>Montant Moyen: $' . $commandes_stats['montant_moyen'] . ' FB (' . number_format($montant_moyen_commandes_usd, 2) . ' $)</h5>';
                echo '</div></div>';
            } catch (PDOException $e) {
                echo '<div class="col-4">';
                echo '<h3>Statistiques Commandes</h3>';
                echo '<div class="alert alert-danger">';
                echo '<h4>Erreur lors de la récupération des commandes: ' . $e->getMessage() . '</h4>';
                echo '</div></div>';
            }

            // Statistiques pour la table Historique
            try {
                $stmt = $pdo->prepare("SELECT COUNT(*) AS total_historique, SUM(prix) AS montant_total, AVG(prix) AS montant_moyen FROM Historique");
                $stmt->execute();
                $historique_stats = $stmt->fetch(PDO::FETCH_ASSOC);

                // Conversion en dollars
                $montant_total_historique_usd = $historique_stats['montant_total'] / 7000;
                $montant_moyen_historique_usd = $historique_stats['montant_moyen'] / 7000;

                echo '<div class="col-4">';
                echo '<h3>Statistiques Historique</h3>';
                echo '<div class="alert alert-info">';
                echo '<h4>Total Historique: ' . $historique_stats['total_historique'] . '</h4>';
                echo '<h5>Montant Total: $' . $historique_stats['montant_total'] . ' FB (' . number_format($montant_total_historique_usd, 2) . ' $)</h5>';
                echo '<h5>Montant Moyen: $' . $historique_stats['montant_moyen'] . ' FB (' . number_format($montant_moyen_historique_usd, 2) . ' $)</h5>';
                echo '</div></div>';
            } catch (PDOException $e) {
                echo '<div class="col-4">';
                echo '<h3>Statistiques Historique</h3>';
                echo '<div class="alert alert-danger">';
                echo '<h4>Erreur lors de la récupération de l\'historique: ' . $e->getMessage() . '</h4>';
                echo '</div></div>';
            }

            echo '</div>'; // Fin de la row

        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">';
            echo '<h4>Erreur de connexion à la base de données: ' . $e->getMessage() . '</h4>';
            echo '</div>';
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>