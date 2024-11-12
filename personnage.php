
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



// vérification de l'id du personnage
if (!isset($_GET['id'])) {
    die("erreur : l'id du Gaulois est manquant");
}

// on récupère l'id du perso choisi dans l'index
$id_persoChoisi = $_GET['id'];


// on créé nos 2 variables requêtes pour alléger un peu l'écriture (celles présentes dans le fichier requetes.sql)
$sql1 = "SELECT personnage.nom_personnage, specialite.nom_specialite, lieu.nom_lieu
FROM personnage
LEFT JOIN specialite ON personnage.id_specialite = specialite.id_specialite
LEFT JOIN lieu ON personnage.id_lieu = lieu.id_lieu
WHERE personnage.id_personnage = $id_persoChoisi";


$sql2 = "SELECT bataille.nom_bataille, DATE_FORMAT(bataille.date_bataille, '%d-%m-%Y') AS date_bataille, SUM(prendre_casque.qte) AS nb_casques
FROM bataille
INNER JOIN prendre_casque ON bataille.id_bataille = prendre_casque.id_bataille
LEFT JOIN lieu ON personnage.id_lieu = lieu.id_lieu;
WHERE prendre_casque.id_personnage = $id_persoChoisi
GROUP BY bataille.id_bataille";




// on procède comme dans index.php : on souhaite récupérer deux tableaux exploitables
// premier tableau n'a qu'une seule ligne : les informations du personnage choisi
// deuxième tableau contient le nom des batailles, la date et le nb de casques pris

$gauloisStatement1 = $mysqlClient->prepare($sql1);
$gauloisStatement1->execute();
$gaulois1 = $gauloisStatement1->fetch();

$gauloisStatement2 = $mysqlClient->prepare($sql2);
$gauloisStatement2->execute();
$gaulois2 = $gauloisStatement2->fetchAll();

var_dump($gaulois1);
echo "<br>";
var_dump($gaulois2);


// si notre tableau à une ligne n'existe pas, on stoppe
if (!$gaulois1) {
    die("Personnage non trouvé");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du personnage Gaulois choisi</title>
</head>
<body>
    <div id='wrapper'>

<?php


        echo "<h1>$gaulois1['nom_personnage']</h1>";

        echo "<table id='table2'>
            <thead>
                <tr>
                    <th>Nom du villageois</th>
                    <th>Spécialité</th>
                    <th>Lieu d'habitation</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>".$gaulois1['nom_personnage']."</td>
                    <td>".$gaulois1['nom_specialite']."</td>
                    <td>".$gaulois1['nom_lieu']."</td>
                </tr>
            </tbody>
        </table>";

        echo "<h1>Batailles, dates et faits d'armes</h1>"; 


        function afficherTableGaulois1 (array $gaulois2) : string {
            asort($gaulois2); // on trie notre tableau alphabétiquement
        
            $result = "<table id='table1'>
                    <thead>
                        <tr>
                            <th>Nom de la bataille</th>
                            <th>Date de la bataille</th>
                            <th>Nombre de casques pris</th>
                        </tr>
                    </thead>
                    <tbody>"; // partie en dehors de la boucle
        
            foreach ($gaulois2 as $g2) {
                $result .= "<tr>
                                <td>".$g2['nom_bataille']."</td>
                                <td>".$g2['date_bataille']."</td>
                                <td>".$g2['nb_casques']."</td>
                            </tr>";
            }
        
            $result .= "</tbody>
                    </table>";
        
            return $result;
        
        }
        
        echo afficherTableGaulois1($gaulois2);

?>
    </div>
</body>
</html>


