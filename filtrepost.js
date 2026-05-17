function filtrepost(){
    const positionFaite = document.getElementById('filter-position').value;
    const contratFait = document.getElementById('filter-contrat').value;

    const cartes = document.querySelectorAll('.job-card');
    cartes.forEach(carte => {
        const typeMatch = (positionFaite === 'all' || carte.getAttribute('data-type') === positionFaite);
        const contratMatch = (contratFait === 'all' || carte.getAttribute('data-contrat') === contratFait);

        if (typeMatch && contratMatch) {
            carte.style.display = "block";
        } else {
            carte.style.display = "none";
        }
    });
}