<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once '../racine.php';
    include_once RACINE . '/service/EtudiantService.php';
    delete();
}

function delete() {
    extract($_POST);
    $es = new EtudiantService();
    $es->delete(new Etudiant($id, null, null, null, null, null));
    header('Content-type: application/json');
    echo json_encode($es->findAllApi());
}
?>
