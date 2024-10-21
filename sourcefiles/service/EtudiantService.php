<?php
include_once RACINE . '/classes/Etudiant.php';
include_once RACINE . '/connexion/Connexion.php';
include_once RACINE . '/dao/IDao.php';

class EtudiantService implements IDao {
    private $connexion;

    function __construct() {
        $this->connexion = new Connexion();
    }

    public function create($o) {
        $image = $o->getImage();
        $nom_image = time() . '.jpg';  // Générer un nom unique pour l'image
        
        // Correction du chemin d'upload
        $chemin_upload = __DIR__ . '/../uploads/' . $nom_image;
        
        // Assurez-vous que le dossier uploads existe
        if (!file_exists(__DIR__ . '/../uploads/')) {
            mkdir(__DIR__ . '/../uploads/', 0777, true);
        }
     
        // Décoder l'image base64
        $donnees_image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
     
        // Sauvegarder le fichier image
        if (file_put_contents($chemin_upload, $donnees_image)) {
            $nom_image_bdd = 'uploads/' . $nom_image; // Chemin relatif pour la BDD
        } else {
            $nom_image_bdd = '';
        }
     
        $query = "INSERT INTO Etudiant (`id`, `nom`, `prenom`, `ville`, `sexe`, `image`) "
               . "VALUES (NULL, '" . $o->getNom() . "', '" . $o->getPrenom() . "', '"
               . $o->getVille() . "', '" . $o->getSexe() . "', '" . $nom_image_bdd . "');";
     
        $req = $this->connexion->getConnexion()->prepare($query);
        $req->execute() or die('Erreur SQL');
     }
    
     public function update($o) {
        // Vérifier si une nouvelle image est fournie
        if ($o->getImage() !== null && !empty($o->getImage())) {
            // Si une image est présente, on met à jour tous les champs y compris l'image
            $query = "UPDATE Etudiant SET `nom` = ?, `prenom` = ?, `ville` = ?, `sexe` = ?, `image` = ? WHERE `id` = ?";
            $stmt = $this->connexion->getConnexion()->prepare($query);
            $stmt->execute([$o->getNom(), $o->getPrenom(), $o->getVille(), $o->getSexe(), $o->getImage(), $o->getId()]);
        } else {
            // Si aucune nouvelle image n'est fournie, on met à jour tous les champs sauf l'image
            $query = "UPDATE Etudiant SET `nom` = ?, `prenom` = ?, `ville` = ?, `sexe` = ? WHERE `id` = ?";
            $stmt = $this->connexion->getConnexion()->prepare($query);
            $stmt->execute([$o->getNom(), $o->getPrenom(), $o->getVille(), $o->getSexe(), $o->getId()]);
        }
    }    

    

    // Delete a student by ID
    public function delete($o) {
        $query = "DELETE FROM Etudiant WHERE id = " . $o->getId();
        $req = $this->connexion->getConnexion()->prepare($query);
        $req->execute() or die('Erreur SQL');
    }

    // Get all students
    public function findAll() {
        $etds = array();
        $query = "SELECT * FROM Etudiant";
        $req = $this->connexion->getConnexion()->prepare($query);
        $req->execute();
        while ($e = $req->fetch(PDO::FETCH_OBJ)) {
            $etds[] = new Etudiant($e->id, $e->nom, $e->prenom, $e->ville, $e->sexe, $e->image);
        }
        return $etds;
    }

    // Find student by ID
    public function findById($id) {
        $query = "SELECT * FROM Etudiant WHERE id = " . $id;
        $req = $this->connexion->getConnexion()->prepare($query);
        $req->execute();
        if ($e = $req->fetch(PDO::FETCH_OBJ)) {
            $etd = new Etudiant($e->id, $e->nom, $e->prenom, $e->ville, $e->sexe, $e->image);
        }
        return $etd;
    }

    // API: Get all students (for API usage)
    public function findAllApi() {
        $query = "SELECT * FROM Etudiant";
        $req = $this->connexion->getConnexion()->prepare($query);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
