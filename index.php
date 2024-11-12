
<?php
try {
    // connexion à la base de données
    $mysqlClient = new PDO("mysql:host=localhost;dbname=gaulois;charset=utf8",
	"root",
	"");
    

} catch (Exception $e) {
    // si erreur, on affiche un message et on stoppe (éviter aussi une faille de sécurité)
    die('Erreur : ' . $e->getMessage());
}

// fin de la partie connexion, on poursuit.



// on créé notre variable requête pour alléger un peu l'écriture (celle présente dans le fichier requetes.sql)
$sql = "SELECT personnage.nom_personnage, specialite.nom_specialite, lieu.nom_lieu
FROM personnage
LEFT JOIN specialite ON personnage.id_specialite = specialite.id_specialite
LEFT JOIN lieu ON personnage.id_lieu = lieu.id_lieu";

// $gauloisStatement = $mysqlClient->prepare($sql); (ne peut pas être exploité seul)

$gauloisStatement = $mysqlClient->prepare($sql);
$gauloisStatement->execute();
$gaulois = $gauloisStatement->fetchAll();

var_dump($gaulois);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des personnages Gaulois</title>
</head>
<body>
    <div id='wrapper'>
        <h1>Liste des Gaulois de ma BDD</h1>

<?php
function afficherTableGaulois (array $gaulois) : string {
    asort($gaulois); // on trie notre tableau alphabétiquement

    $result = "<table id='table1'>
            <thead>
                <tr>
                    <th>Nom du villageois</th>
                    <th>Spécialité</th>
                    <th>Lieu d'habitation</th>
                </tr>
            </thead>
            <tbody>"; // partie en dehors de la boucle

    foreach ($gaulois as $g) {
        $result .= "<tr>
                        <td><a href='personnage.php?id = $g['id_personnage']'>".$g['nom_personnage']."</a></td>
                        <td>".$g['nom_specialite']."</td>
                        <td>".$g['nom_lieu']."</td>
                    </tr>";
    }

    $result .= "</tbody>
            </table>";

    return $result;

}

echo afficherTableGaulois($gaulois);

// ?id = $g['id_personnage']  un paramètre GET, qui permet de transmettre des informations dans l’URL. Le code <?= $g['id_personnage'] ?> insère dynamiquement la valeur de id_personnage pour chaque Gaulois.

?>

    </div>
    
</body>
</html>
