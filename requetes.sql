/*
requête pour obtenir les informations souhaitées de la page index.php

SELECT personnage.nom_personnage, specialite.nom_specialite, lieu.nom_lieu
FROM personnage
LEFT JOIN specialite ON personnage.id_specialite = specialite.id_specialite
LEFT JOIN lieu ON personnage.id_lieu = lieu.id_lieu;
*/