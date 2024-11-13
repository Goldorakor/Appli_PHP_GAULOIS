

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
$sql = "SELECT potion.id_potion, potion.nom_potion
FROM potion
ORDER BY potion.nom_potion";

// $gauloisStatement = $mysqlClient->prepare($sql); (ne peut pas être exploité seul)

$potionsStatement = $mysqlClient->prepare($sql);
$potionsStatement->execute();
$potions = $potionsStatement->fetchAll();

// var_dump($potions);  on vérifie sur la page index le contenu du tableau

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des potions gauloises</title>
</head>
<body>
    <div id='wrapper'>
        <h1>Liste des potions de ma BDD</h1>

<?php
function afficherTablePotions (array $potions) : string {
    // asort($potions);  on trie notre tableau alphabétiquement (on trie dans la requête)

    $result = "<table id='table1'>
            <thead>
                <tr>
                    <th>Nom de la potion</th>
                   
                    
                </tr>
            </thead>
            <tbody>"; // partie en dehors de la boucle

    foreach ($potions as $potion) {
        $result .= "<tr>
                        <td><a href='potion.php?id=".$potion['id_potion']."'>".$potion['nom_potion']."</a></td>
                        
                        
                    </tr>";
    }

    $result .= "</tbody>
            </table>";

    return $result;

}

echo afficherTablePotions($potions);

/* 
?id = ".$potion['id_potion']."  un paramètre GET, qui permet de transmettre des informations dans l’URL.
Le code <?= $potion['id_potion'] ?> 
insère dynamiquement la valeur de id_personnage pour chaque potion.
*/

?>

    </div>
    
</body>
</html>
