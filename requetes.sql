/*
requête pour obtenir les informations souhaitées de la page index.php

SELECT personnage.nom_personnage, specialite.nom_specialite, lieu.nom_lieu
FROM personnage
LEFT JOIN specialite ON personnage.id_specialite = specialite.id_specialite
LEFT JOIN lieu ON personnage.id_lieu = lieu.id_lieu;
*/


/*
requête pour obtenir les informations souhaitées de la page personnage.php

SELECT personnage.nom_personnage, specialite.nom_specialite, lieu.nom_lieu
FROM personnage
LEFT JOIN specialite ON personnage.id_specialite = specialite.id_specialite
LEFT JOIN lieu ON personnage.id_lieu = lieu.id_lieu
WHERE personnage.id_personnage = $id_persoChoisi


SELECT bataille.nom_bataille, DATE_FORMAT(bataille.date_bataille, '%d-%m-%Y') AS date_bataille, SUM(prendre_casque.qte) AS nb_casques
FROM bataille
INNER JOIN prendre_casque ON bataille.id_bataille = prendre_casque.id_bataille
LEFT JOIN lieu ON personnage.id_lieu = lieu.id_lieu;
WHERE prendre_casque.id_personnage = $id_persoChoisi (on sélectionne notre gaulois)
GROUP BY bataille.id_bataille (on additionne sur la bataille les différents casques ramassés)
*/
