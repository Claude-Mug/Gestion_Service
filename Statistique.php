<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques de Paiement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <style>
        .chart-container {
            position: relative;
            margin: auto;
            height: 50vh; /* Hauteur augmentée */
            width: 90vw;  /* Largeur augmentée */
        }
        .chart-title {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Statistiques de Paiement</h2>

        <?php
        session_start(); // Démarrer la session

        // Connexion à la base de données
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "Services";

        // Créer la connexion
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Vérifier la connexion
        if ($conn->connect_error) {
            echo '<div class="alert alert-danger">';
            echo '<h4>Erreur de connexion à la base de données: ' . htmlspecialchars($conn->connect_error) . '</h4>';
            echo '</div>';
            exit; // Terminer le script si la connexion échoue
        }

        // Récupération des données par jour
        $stmt = $conn->prepare("SELECT DATE(date_paiement) AS date, SUM(montant) AS montant_total FROM revenus GROUP BY DATE(date_paiement)");
        $stmt->execute();
        $revenus_data = $stmt->get_result();

        $dates_revenus_jour = [];
        $montants_revenus_fb_jour = [];
        while ($row = $revenus_data->fetch_assoc()) {
            $dates_revenus_jour[] = $row['date'];
            $montants_revenus_fb_jour[] = $row['montant_total'];
        }

        // Récupération des données par mois
        $stmt = $conn->prepare("SELECT DATE_FORMAT(date_paiement, '%Y-%m') AS date, SUM(montant) AS montant_total FROM revenus GROUP BY DATE_FORMAT(date_paiement, '%Y-%m')");
        $stmt->execute();
        $revenus_data_mois = $stmt->get_result();

        $dates_revenus_mois = [];
        $montants_revenus_fb_mois = [];
        while ($row = $revenus_data_mois->fetch_assoc()) {
            $dates_revenus_mois[] = $row['date'];
            $montants_revenus_fb_mois[] = $row['montant_total'];
        }

        // Récupération des données par année
        $stmt = $conn->prepare("SELECT YEAR(date_paiement) AS date, SUM(montant) AS montant_total FROM revenus GROUP BY YEAR(date_paiement)");
        $stmt->execute();
        $revenus_data_annee = $stmt->get_result();

        $dates_revenus_annee = [];
        $montants_revenus_fb_annee = [];
        while ($row = $revenus_data_annee->fetch_assoc()) {
            $dates_revenus_annee[] = $row['date'];
            $montants_revenus_fb_annee[] = $row['montant_total'];
        }

        // Récupération des données pour Historique (similaire)
        // Récupération des données par jour
        $stmt = $conn->prepare("SELECT DATE(created_at) AS date, SUM(prix) AS montant_total FROM historique GROUP BY DATE(created_at)");
        $stmt->execute();
        $historique_data = $stmt->get_result();

        $dates_historique_jour = [];
        $montants_historique_fb_jour = [];
        while ($row = $historique_data->fetch_assoc()) {
            $dates_historique_jour[] = $row['date'];
            $montants_historique_fb_jour[] = $row['montant_total'];
        }

        // Récupération des données par mois
        $stmt = $conn->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m') AS date, SUM(prix) AS montant_total FROM historique GROUP BY DATE_FORMAT(created_at, '%Y-%m')");
        $stmt->execute();
        $historique_data_mois = $stmt->get_result();

        $dates_historique_mois = [];
        $montants_historique_fb_mois = [];
        while ($row = $historique_data_mois->fetch_assoc()) {
            $dates_historique_mois[] = $row['date'];
            $montants_historique_fb_mois[] = $row['montant_total'];
        }

        // Récupération des données par année
        $stmt = $conn->prepare("SELECT YEAR(created_at) AS date, SUM(prix) AS montant_total FROM historique GROUP BY YEAR(created_at)");
        $stmt->execute();
        $historique_data_annee = $stmt->get_result();

        $dates_historique_annee = [];
        $montants_historique_fb_annee = [];
        while ($row = $historique_data_annee->fetch_assoc()) {
            $dates_historique_annee[] = $row['date'];
            $montants_historique_fb_annee[] = $row['montant_total'];
        }

        // Récupération des données pour CommandDomicile (similaire)
        // Récupération des données par jour
        $stmt = $conn->prepare("SELECT DATE(created_at) AS date, SUM(prix) AS montant_total FROM commanddomicile GROUP BY DATE(created_at)");
        $stmt->execute();
        $commandes_data = $stmt->get_result();

        $dates_commandes_jour = [];
        $montants_commandes_fb_jour = [];
        while ($row = $commandes_data->fetch_assoc()) {
            $dates_commandes_jour[] = $row['date'];
            $montants_commandes_fb_jour[] = $row['montant_total'];
        }

        // Récupération des données par mois
        $stmt = $conn->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m') AS date, SUM(prix) AS montant_total FROM commanddomicile GROUP BY DATE_FORMAT(created_at, '%Y-%m')");
        $stmt->execute();
        $commandes_data_mois = $stmt->get_result();

        $dates_commandes_mois = [];
        $montants_commandes_fb_mois = [];
        while ($row = $commandes_data_mois->fetch_assoc()) {
            $dates_commandes_mois[] = $row['date'];
            $montants_commandes_fb_mois[] = $row['montant_total'];
        }

        // Récupération des données par année
        $stmt = $conn->prepare("SELECT YEAR(created_at) AS date, SUM(prix) AS montant_total FROM commanddomicile GROUP BY YEAR(created_at)");
        $stmt->execute();
        $commandes_data_annee = $stmt->get_result();

        $dates_commandes_annee = [];
        $montants_commandes_fb_annee = [];
        while ($row = $commandes_data_annee->fetch_assoc()) {
            $dates_commandes_annee[] = $row['date'];
            $montants_commandes_fb_annee[] = $row['montant_total'];
        }

        // Conversion des montants en dollars
        $montants_revenus_usd_jour = array_map(fn($montant) => $montant / 7000, $montants_revenus_fb_jour);
        $montants_revenus_usd_mois = array_map(fn($montant) => $montant / 7000, $montants_revenus_fb_mois);
        $montants_revenus_usd_annee = array_map(fn($montant) => $montant / 7000, $montants_revenus_fb_annee);

        // Répéter pour Historique
        $montants_historique_usd_jour = array_map(fn($montant) => $montant / 7000, $montants_historique_fb_jour);
        $montants_historique_usd_mois = array_map(fn($montant) => $montant / 7000, $montants_historique_fb_mois);
        $montants_historique_usd_annee = array_map(fn($montant) => $montant / 7000, $montants_historique_fb_annee);

        // Répéter pour CommandDomicile
        $montants_commandes_usd_jour = array_map(fn($montant) => $montant / 7000, $montants_commandes_fb_jour);
        $montants_commandes_usd_mois = array_map(fn($montant) => $montant / 7000, $montants_commandes_fb_mois);
        $montants_commandes_usd_annee = array_map(fn($montant) => $montant / 7000, $montants_commandes_fb_annee);
        ?>

        <div class="row">
            <div class="col-4 chart-container">
                <h3 class="chart-title">Revenus</h3>
                <canvas id="revenusChart"></canvas>
            </div>
            <div class="col-4 chart-container">
                <h3 class="chart-title">Historique</h3>
                <canvas id="historiqueChart"></canvas>
            </div>
            <div class="col-4 chart-container">
                <h3 class="chart-title">Commandes Domicile</h3>
                <canvas id="commandesChart"></canvas>
            </div>
        </div>

        <script>
            function createChart(ctx, labels, fbDataJour, fbDataMois, fbDataAnnee, usdDataJour, usdDataMois, usdDataAnnee) {
                return new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Montant Total (FB) - Journée',
                                data: fbDataJour,
                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            },
                            {
                                label: 'Montant Total (FB) - Mensuel',
                                data: fbDataMois,
                                backgroundColor: 'rgba(153, 102, 255, 0.6)',
                            },
                            {
                                label: 'Montant Total (FB) - Année',
                                data: fbDataAnnee,
                                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            },
                            {
                                label: 'Montant Total ($) - Journée',
                                data: usdDataJour,
                                backgroundColor: 'rgba(255, 159, 64, 0.6)',
                            },
                            {
                                label: 'Montant Total ($) - Mensuel',
                                data: usdDataMois,
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            },
                            {
                                label: 'Montant Total ($) - Année',
                                data: usdDataAnnee,
                                backgroundColor: 'rgba(255, 206, 86, 0.6)',
                            },
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Montant'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return value + ' FB / ' + (value / 7000).toFixed(2) + ' $';
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.dataset.label + ': ' + tooltipItem.raw + ' FB (' + (tooltipItem.raw / 7000).toFixed(2) + ' $)';
                                    }
                                }
                            },
                            legend: {
                                display: true,
                                labels: {
                                    boxHeight: 10,
                                    padding: 20,
                                }
                            },
                            datalabels: {
                                anchor: 'end',
                                align: 'end',
                                formatter: (value, context) => {
                                    return value + ' FB';
                                }
                            }
                        }
                    }
                });
            }

            // Créer les graphiques
            const ctxRevenus = document.getElementById('revenusChart').getContext('2d');
            createChart(ctxRevenus, 
                <?php echo json_encode($dates_revenus_jour); ?>, 
                <?php echo json_encode($montants_revenus_fb_jour); ?>, 
                <?php echo json_encode($montants_revenus_fb_mois); ?>, 
                <?php echo json_encode($montants_revenus_fb_annee); ?>, 
                <?php echo json_encode($montants_revenus_usd_jour); ?>, 
                <?php echo json_encode($montants_revenus_usd_mois); ?>, 
                <?php echo json_encode($montants_revenus_usd_annee); ?>
            );

            const ctxHistorique = document.getElementById('historiqueChart').getContext('2d');
            createChart(ctxHistorique, 
                <?php echo json_encode($dates_historique_jour); ?>, 
                <?php echo json_encode($montants_historique_fb_jour); ?>, 
                <?php echo json_encode($montants_historique_fb_mois); ?>, 
                <?php echo json_encode($montants_historique_fb_annee); ?>, 
                <?php echo json_encode($montants_historique_usd_jour); ?>, 
                <?php echo json_encode($montants_historique_usd_mois); ?>, 
                <?php echo json_encode($montants_historique_usd_annee); ?>
            );

            const ctxCommandes = document.getElementById('commandesChart').getContext('2d');
            createChart(ctxCommandes, 
                <?php echo json_encode($dates_commandes_jour); ?>, 
                <?php echo json_encode($montants_commandes_fb_jour); ?>, 
                <?php echo json_encode($montants_commandes_fb_mois); ?>, 
                <?php echo json_encode($montants_commandes_fb_annee); ?>, 
                <?php echo json_encode($montants_commandes_usd_jour); ?>, 
                <?php echo json_encode($montants_commandes_usd_mois); ?>, 
                <?php echo json_encode($montants_commandes_usd_annee); ?>
            );
        </script>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>