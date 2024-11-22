
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
    die("erreur : l'id de la potion est manquant");
}

// on récupère l'id du perso choisi dans l'index
$id_potionChoisie = $_GET['id'];


// on créé nos 2 variables requêtes pour alléger un peu l'écriture (celles présentes dans le fichier requetes.sql)
$sql1 = "SELECT potion.nom_potion
FROM potion
WHERE potion.id_potion = :id
ORDER BY potion.nom_potion";


$sql2 = "SELECT ingredient.nom_ingredient, composer.qte, ingredient.cout_ingredient
FROM composer
INNER JOIN potion ON composer.id_potion = potion.id_potion
INNER JOIN ingredient ON composer.id_ingredient = ingredient.id_ingredient
WHERE potion.id_potion = :id;

// on procède comme dans index.php : on souhaite récupérer deux tableaux exploitables
// premier tableau n'a qu'une seule ligne : les informations du personnage choisi
// deuxième tableau contient le nom des batailles, la date et le nb de casques pris

$potionsStatement1 = $mysqlClient->prepare($sql1);
$potionsStatement1->execute(["id" => $id_potionChoisie]);
$potions1 = $potionsStatement1->fetch();

$potionsStatement2 = $mysqlClient->prepare($sql2);
$potionsStatement2->execute(["id" => $id_potionChoisie]);
$potions2 = $potionsStatement2->fetchAll();

// var_dump($potions1);
// echo "<br>";
// var_dump($potions2);


// si notre tableau à une ligne n'existe pas, on stoppe
if (!$potions1) {
    die("Potion non trouvée");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la potion gauloise choisie</title>
</head>
<body>
    <div id='wrapper'>

<?php


        //echo "<h1>."$gaulois1['nom_personnage']."</h1>";
        echo "<h1>Détails de la potion choisie</h1>";

        echo "<table id='table2'>
            <thead>
                <tr>
                    <th>Nom de la potion</th>
                    
                   
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>".$potions1['nom_potion']."</td>
                    
                    
                </tr>
            </tbody>
        </table>";

        echo "<h1>Composition des potions :noms, quantités et coûts des ingrédients</h1>"; 


        function afficherTablepotions1 (array $potions2) : string {
            // asort($potions2);  on trie notre tableau alphabétiquement
        
            $result = "<table id='table1'>
                    <thead>
                        <tr>
                            <th>Nom de l'ingrédient</th>
                            <th>Quantité utilisée</th>
                            <th>Coût de l'ingrédient</th>
                        </tr>
                    </thead>
                    <tbody>"; // partie en dehors de la boucle
        
            foreach ($potions2 as $potion2) {
                $result .= "<tr>
                                <td>".$potion2['nom_ingredient']."</td>
                                <td>".$potion2['qte']."</td>
                                <td>".$potion2['cout_ingredient']."</td>
                            </tr>";
            }
        
            $result .= "</tbody>
                    </table>";
        
            return $result;
        
        }
        
        echo afficherTablepotions1($potions2);

?>
    </div>
</body>
</html>
