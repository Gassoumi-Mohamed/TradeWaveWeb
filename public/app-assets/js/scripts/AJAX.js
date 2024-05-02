document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-input');
    const searchResultsContainer = document.getElementById('search-results-container');

    searchForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Empêcher la soumission du formulaire par défaut

        // Récupérer la valeur du champ de recherche
        const searchTerm = searchInput.value;

        // Afficher un message dans la console pour vérifier si l'événement est déclenché
        console.log('Événement de soumission du formulaire déclenché !');

        // Effectuer une requête AJAX vers votre contrôleur Symfony
        fetch('/user_list' + encodeURIComponent(searchTerm))
            .then(response => response.json())
            .then(data => {
                // Mettre à jour la page avec les résultats de la recherche
                // Par exemple, vous pouvez mettre à jour une section dédiée sur la page ou afficher les résultats dans une liste
                searchResultsContainer.innerHTML = ''; // Effacer les résultats précédents
                data.forEach(user => {
                    const userElement = document.createElement('div');
                    userElement.textContent = user.nom; // Afficher le nom de l'utilisateur, ajustez selon vos besoins
                    searchResultsContainer.appendChild(userElement);
                });
            })
            .catch(error => {
                console.error('Une erreur s\'est produite lors de la recherche :', error);
            });
    });
});
