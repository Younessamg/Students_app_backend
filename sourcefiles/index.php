<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Étudiants</title>
</head>
<body>
    <?php
    include_once './racine.php';
    include_once RACINE . '/service/EtudiantService.php';
    $es = new EtudiantService();
    ?>

    <main>
        <section>
            <h2>Ajouter un nouveau étudiant</h2>
            <form method="POST" action="controller/addEtudiant.php" enctype="multipart/form-data">
                <fieldset>
                    <legend>Informations de l'étudiant</legend>
                    <p>
                        <label for="nom">Nom :</label>
                        <input type="text" id="nom" name="nom" required>
                    </p>
                    <p>
                        <label for="prenom">Prénom :</label>
                        <input type="text" id="prenom" name="prenom" required>
                    </p>
                    <p>
                        <label for="ville">Ville :</label>
                        <select id="ville" name="ville" required>
                            <option value="Marrakech">Marrakech</option>
                            <option value="Rabat">Rabat</option>
                            <option value="Agadir">Agadir</option>
                        </select>
                    </p>
                    <p>
                        <span>Sexe :</span>
                        <label><input type="radio" name="sexe" value="homme" required> M</label>
                        <label><input type="radio" name="sexe" value="femme" required> F</label>
                    </p>
                    <p>
                        <button type="submit">Envoyer</button>
                        <button type="reset">Effacer</button>
                    </p>
                </fieldset>
            </form>
        </section>

        <section>
            <h2>Liste des étudiants</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Ville</th>
                        <th>Sexe</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($es->findAll() as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e->getId()) ?></td>
                        <td><?= htmlspecialchars($e->getNom()) ?></td>
                        <td><?= htmlspecialchars($e->getPrenom()) ?></td>
                        <td><?= htmlspecialchars($e->getVille()) ?></td>
                        <td><?= htmlspecialchars($e->getSexe()) ?></td>
                        <td>
                            <a href="controller/deleteEtudiant.php?id=<?= urlencode($e->getId()) ?>">Supprimer</a>
                            <a href="updateEtudiant.php?id=<?= urlencode($e->getId()) ?>">Modifier</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>