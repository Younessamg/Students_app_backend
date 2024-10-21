<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once '../racine.php';
    include_once RACINE . '/service/EtudiantService.php';
    updateEtudiant();
}

function updateEtudiant() {
    extract($_POST);
    $es = new EtudiantService();

    // Récupérer les informations existantes de l'étudiant
    $etudiantExistant = $es->findById($id);

    // Si une nouvelle image est fournie, l'utiliser. Sinon, passer 'null' pour ne pas modifier l'image
    $image = isset($_POST['image']) && !empty($_POST['image']) ? $_POST['image'] : null;

    // Mise à jour de l'étudiant
    $es->update(new Etudiant($id, $nom, $prenom, $ville, $sexe, $image));

    // Réponse JSON pour les besoins d'une API
    header('Content-type: application/json');
    echo json_encode($es->findAllApi());
}
