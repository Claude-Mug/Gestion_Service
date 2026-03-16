<!-- champ de recherche -->
                                    <div class="search-container d-flex align-items-center">
                                        <button class="btn btn-success" id="search-button" onclick="toggleSearch()">
                                            <i class="bi bi-search"></i> <!-- Icône pour afficher le champ de recherche -->
                                        </button>
                                        <input type="text" class="form-control search-input m-1" id="search-input" placeholder="Rechercher un service..." style="display: none;">
                                        <button class="btn btn-outline-danger" id="execute-search-button" style="display: none;" onclick="executeSearch()">
                                            <i class="bi bi-search"></i> <!-- Icône pour exécuter la recherche -->
                                        </button>
                                    </div>
                                    
                                    <script>
                                        function toggleSearch() {
                                            const searchInput = document.getElementById('search-input');
                                            const searchButton = document.getElementById('execute-search-button');
                                            
                                            if (searchInput.style.display === 'none') {
                                                searchInput.style.display = 'block'; // Afficher le champ de recherche
                                                searchButton.style.display = 'inline-block'; // Afficher le bouton de recherche
                                                searchInput.focus(); // Mettre le focus sur le champ
                                            } else {
                                                searchInput.style.display = 'none'; // Cacher le champ de recherche
                                                searchButton.style.display = 'none'; // Cacher le bouton de recherche
                                            }
                                        }
                                    
                                        function executeSearch() {
                                            const query = document.getElementById('search-input').value;
                                            // Logique de recherche ici (à implémenter plus tard)

                                            
                                            console.log(`Recherche pour : ${query}`); // Pour vérification dans la console
                                        }
                                    </script>