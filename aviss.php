<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'Services';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement des données
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['note'])) {
        $stmt = $pdo->prepare("INSERT INTO avis (service_nom, service_categorie, note) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['service_nom'], $_POST['service_categorie'], (int)$_POST['note']]);
    } 
    elseif (isset($_POST['commentaire'])) {
        $stmt = $pdo->prepare("UPDATE avis SET client_nom = ?, commentaire = ? WHERE id = ?");
        $stmt->execute([
            htmlspecialchars($_POST['client_nom']),
            htmlspecialchars($_POST['commentaire']),
            (int)$_POST['avis_id']
        ]);
    }
    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit();
}

// Services et données
$services = [
    'Services à Domicile' => [
        'Systèmes Électroménagers', 'Sécurité à Domicile', 'Déménagement',
        'Garde d\'Enfants', 'Livraison', 'Organisation d\'Événements',
        'Coaching Personnel', 'Conseil Financier', 'Peinture et Nettoyage',
        'Soins d\'hygiène et infirmiers', 'Entretien Ménager et Jardinage'
    ],
    'Services de Réparation' => [
        'Électricité', 'Réparation d\'Appareils Éléctronique', 'Plomberie',
        'Mécanique', 'Soudure', 'Réparation de Caméras',
        'Réparation de Montres et Accessoires', 'Réparation de Vélo',
        'Réparation de Climatisation et Micro-ondes',
        'Réparation de Systèmes Audio et Mixeurs', 'Réparation d\'Instruments de Musique'
    ]
];

$notes_moyennes = [];
$dernier_avis_id = [];
$tous_commentaires = [];

foreach ($services as $categorie => $noms) {
    foreach ($noms as $nom) {
        // Moyennes
        $stmt = $pdo->prepare("SELECT AVG(note) as moyenne, COUNT(*) as total FROM avis WHERE service_categorie = ? AND service_nom = ?");
        $stmt->execute([$categorie, $nom]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $notes_moyennes[$categorie][$nom] = [
            'moyenne' => round($result['moyenne'], 1),
            'total' => $result['total'] ?? 0
        ];
        
        // Dernier avis ID
        $stmt = $pdo->prepare("SELECT id FROM avis WHERE service_categorie = ? AND service_nom = ? ORDER BY date_creation DESC LIMIT 1");
        $stmt->execute([$categorie, $nom]);
        $dernier_avis_id[$categorie][$nom] = $stmt->fetchColumn();
        
        // Commentaires
        $stmt = $pdo->prepare("SELECT * FROM avis WHERE service_categorie = ? AND service_nom = ? AND commentaire IS NOT NULL ORDER BY date_creation DESC");
        $stmt->execute([$categorie, $nom]);
        $tous_commentaires[$categorie][$nom] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluation des Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rating-star { font-size: 1.5rem; color: #ddd; cursor: pointer; transition: transform 0.2s; }
        .rating-star:hover { transform: scale(1.2); }
        .star-1 { color: #ff6b6b !important; }
        .star-2 { color: #ffa502 !important; }
        .star-3 { color: #ffd32a !important; }
        .star-4 { color: #7bed9f !important; }
        .star-5 { color: #1dd1a1 !important; }
        .service-category { background-color: #f8f9fa; }
        .comment-toggle { cursor: pointer; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4">Évaluation des Services</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success mb-4">Merci pour votre contribution !</div>
        <?php endif; ?>
        
        <div class="row g-4 mb-5">
            <?php foreach ($services as $categorie => $noms): ?>
                <div class="col-md-6">
                    <div class="service-category p-3 rounded-3 h-100">
                        <h2 class="h4 border-bottom pb-2"><?= htmlspecialchars($categorie) ?></h2>
                        
                        <?php foreach ($noms as $nom): ?>
                            <div class="service-item bg-white p-3 rounded-2 mb-3 shadow-sm" id="service-<?= md5($categorie.$nom) ?>">
                                <h3 class="h5 mb-2"><?= htmlspecialchars($nom) ?></h3>
                                
                                <form method="POST" class="rating-form mb-2">
                                    <input type="hidden" name="service_nom" value="<?= htmlspecialchars($nom) ?>">
                                    <input type="hidden" name="service_categorie" value="<?= htmlspecialchars($categorie) ?>">
                                    
                                    <div class="d-flex align-items-center">
                                        <div class="rating-stars me-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php
                                                $note_moyenne = $notes_moyennes[$categorie][$nom]['moyenne'] ?? 0;
                                                $star_class = $i <= round($note_moyenne) ? 'star-'.round($note_moyenne) : '';
                                                ?>
                                                <span class="rating-star <?= $star_class ?>" 
                                                      data-value="<?= $i ?>"
                                                      onclick="this.parentNode.querySelector('input[name=note]').value=<?= $i ?>; this.closest('form').submit()">★</span>
                                            <?php endfor; ?>
                                            <input type="hidden" name="note">
                                        </div>
                                        
                                        <?php $note_info = $notes_moyennes[$categorie][$nom] ?? ['moyenne' => 0, 'total' => 0]; ?>
                                        <small class="text-muted">
                                            <?= number_format($note_info['moyenne'], 1) ?> (<?= $note_info['total'] ?> avis)
                                        </small>
                                    </div>
                                </form>
                                
                                <div class="comment-toggle text-primary mb-2" onclick="toggleCommentForm('<?= md5($categorie.$nom) ?>')">
                                    <small><i class="bi bi-chat-left-text"></i> Ajouter un commentaire</small>
                                </div>
                                
                                <form method="POST" class="comment-form d-none" id="comment-form-<?= md5($categorie.$nom) ?>">
                                    <input type="hidden" name="avis_id" value="<?= $dernier_avis_id[$categorie][$nom] ?? '' ?>">
                                    
                                    <div class="mb-2">
                                        <input type="text" name="client_nom" class="form-control form-control-sm" placeholder="Votre nom (optionnel)">
                                    </div>
                                    
                                    <div class="mb-2">
                                        <textarea name="commentaire" class="form-control form-control-sm" rows="2" placeholder="Votre commentaire"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-sm btn-success">Envoyer</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Section Commentaires -->
        <div class="comments-section bg-white p-4 rounded-3 shadow-sm">
            <h2 class="h4 mb-4">Commentaires des clients</h2>
            
            <?php foreach ($services as $categorie => $noms): ?>
                <h3 class="h5 mt-4 mb-3"><?= htmlspecialchars($categorie) ?></h3>
                
                <?php foreach ($noms as $nom): ?>
                    <?php if (!empty($tous_commentaires[$categorie][$nom])): ?>
                        <h4 class="h6 text-muted mb-2"><?= htmlspecialchars($nom) ?></h4>
                        
                        <div class="row g-3">
                            <?php foreach ($tous_commentaires[$categorie][$nom] as $commentaire): ?>
                                <div class="col-md-6">
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="fw-bold">
                                                    <?= htmlspecialchars($commentaire['client_nom'] ?? 'Anonyme') ?>
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <span class="<?= $i <= $commentaire['note'] ? 'star-'.$commentaire['note'] : '' ?>" style="color: <?= $i <= $commentaire['note'] ? 'inherit' : '#ddd' ?>">★</span>
                                                    <?php endfor; ?>
                                                </span>
                                                <small class="text-muted">
                                                    <?= date('d/m/Y H:i', strtotime($commentaire['date_creation'])) ?>
                                                </small>
                                            </div>
                                            <p class="mb-0"><?= nl2br(htmlspecialchars($commentaire['commentaire'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleCommentForm(serviceId) {
            const form = document.getElementById(`comment-form-${serviceId}`);
            form.classList.toggle('d-none');
        }
    </script>
</body>
</html>