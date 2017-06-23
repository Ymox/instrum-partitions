# Instrum partitions

L'Union Instrumentale de Bex fête ses 150 ans en 2019. On peut imaginer la quantité de partitions qui ont été jouées depuis… et cela représente une grande quantité de données d'archives qui sont sous différents formats. Le plus vieux à ce jour est un système de "cartes de visites" pour chaque partition, ce qui rend fastidieux la recherche. Heureusement, ce système a été saisi dans un fichier Excel, et c'est la liste la plus à jour à l'heure actuelle.

Seulement :
 
- ça prend une place conséquente ;
- la gestion des accès n'est pas tres aisée ;
- le partage non-plus ;
- la synchronisation quand plusieurs personnes ont travaillé dessus est un casse-tête ;
- difficile sinon impossible de travailler à plusieurs en même temps ;
- la normalisation des données laisse à désirer (une ligne par compositeur, plus une éventuelle ligne pour la traduction, rendant peu pertinents les tris) ;
- quelques doublons sont déjà présents vu que l'ID devait être choisi avant la saisie ;
- c'est difficilement évolutif sans complexifier la recherche sur les nouvelles informations (programmes des concerts notamment — "quand a-t'on joué quoi") ;
- c'est difficilement compatible avec une GED qui à terme semble inévitable ;

Du coup, ce projet est là pour faire un pont entre les précédentes solutions et celles qui probablement viendront un jour.

Actuellement, le projet permet donc de saisir des partitions au niveau de :

- leur titre ;
- une éventuelle traduction ;
- les compositeurs ;
- les arrangeurs ;
- l'instrumentation (principalement pour savoir s'il s'agit de version "flex-band" à quelques voix ou pour orcheste complet) ;
- le type de pièce ;
- la taille des partitions (pour les marches de concert ou les marches de défilé, c'est souvent du A4 respectivement A5) ;
- l'état (complet, prêté, etc.) ;
- l'éditeur ;
- l'année d'édition ;
- la référence ;
- d'éventuelles remarques.

Le projet fonctionne avec Symfony 3 (3.3.2 en date de rédaction), et ne nécessite donc qu'un serveur pour le faire fonctionner.