<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Gestion des Clients</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#creerCompteModal">Créer un Client</button>

    <!-- Affichage des clients dans un tableau -->
    <h5 class="mt-4">Liste des Clients</h5>
    <table class="table table-bordered" id="clientsTable">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Pays</th>
                <th>Ville</th>
                <th>Sexe</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="clientsList"></tbody>
    </table>

    <!-- Modal pour le bouton créer un compte -->
    <div class="modal fade" id="creerCompteModal" tabindex="-1" aria-labelledby="creerCompteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="creerCompteModalLabel">Créer un Compte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registerForm" method="POST">
                        <div class="mb-3">
                            <label for="registerLastName" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="registerLastName" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerFirstName" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="registerFirstName" name="prenom" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="registerEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="registerPassword" name="mot_de_passe" required>
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">Pays</label>
                            <input type="text" class="form-control" id="country" name="pays" required>
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">Ville</label>
                            <input type="text" class="form-control" id="city" name="ville" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sexe</label>
                            <select class="form-select" id="gender" name="sexe" required>
                                <option value="">Sélectionnez un sexe</option>
                                <option value="Homme">Homme</option>
                                <option value="Femme">Femme</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Soumettre</button>
                        <div id="registerError" class="alert alert-danger mt-3" style="display:none;"></div>
                        <div id="registerSuccess" class="alert alert-success mt-3" style="display:none;"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche l'envoi du formulaire par défaut

    const formData = new FormData(this);
    fetch('Client.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const errorDiv = document.getElementById('registerError');
        const successDiv = document.getElementById('registerSuccess');
        
        if (data.success) {
            successDiv.textContent = 'Client ajouté avec succès!';
            successDiv.style.display = 'block';
            errorDiv.style.display = 'none';
            loadClients(); // Recharger la liste des clients
            this.reset(); // Réinitialiser le formulaire
        } else {
            errorDiv.textContent = data.message;
            errorDiv.style.display = 'block';
            successDiv.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
});

// Fonction pour charger les clients directement
function loadClients() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'listerClients.php', true); // Remplacer par le fichier qui retourne les clients
    xhr.onload = function() {
        if (this.status === 200) {
            const clients = JSON.parse(this.responseText);
            const clientsList = document.getElementById('clientsList');
            clientsList.innerHTML = ''; // Réinitialiser la liste
            clients.forEach(client => {
                const clientRow = document.createElement('tr');
                clientRow.innerHTML = `
                    <td>${client.id}</td>
                    <td>${client.nom}</td>
                    <td>${client.prenom}</td>
                    <td>${client.email}</td>
                    <td>${client.pays}</td>
                    <td>${client.ville}</td>
                    <td>${client.sexe}</td>
                    <td>
                        <a href="modifierClient.php?id=${client.id}" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="supprimerClient.php?id=${client.id}" class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                `;
                clientsList.appendChild(clientRow);
            });
        }
    };
    xhr.send();
}

// Charger la liste des clients lors du chargement de la page
window.onload = loadClients;
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>