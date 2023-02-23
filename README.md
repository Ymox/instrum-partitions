# Instrum partitions

L'[Union Instrumentale de Bex](https://instrumbex.ch/) a fêté ses 150 ans en 2019. On peut imaginer la quantité de partitions qui ont été jouées depuis… et cela représente une grande quantité de données d'archives qui sont répertoriées sous différents formats. Le plus vieux à ce jour est un système de "cartes de visites" pour chaque partition, ce qui rend fastidieux la recherche. Heureusement, ce système a été saisi dans un fichier Excel, ce qui a grandement facilité la migration vers cette solution.  
L'archivage physique se base sur un format numérique, les partitions étant tamponnées d'un numéro qui les identifie.

Seulement :
 
- les cartes, ça prend une place conséquente ;
- la gestion des accès n'est pas très aisée ;
- le partage non-plus ;
- la synchronisation du fichier Excel quand plusieurs personnes ont travaillé dessus est un casse-tête ;
- la normalisation des données laisse à désirer :

	- une ligne par compositeur
	- plus une éventuelle ligne pour la traduction
	
	rendant peu pertinents les tris.

- quelques doublons étaient déjà présents vu que l'ID devait être choisi avant la saisie ;
- c'était difficilement évolutif sans complexifier la recherche sur les nouvelles informations (programmes des concerts notamment — "quand a-t'on joué quoi") ;
- c'était difficilement compatible avec une GED qui à terme semble inévitable.

Du coup, ce projet est là pour faire un pont entre les précédentes solutions et celles qui probablement viendront un jour, tout en permettant de rapidement collecter les informations pour la SUISA.

Actuellement, le projet permet donc de saisir des partitions en renseignant :

- _leur titre_ ;
- _une éventuelle traduction_ ;
- _les compositeurs_ ;
- _les arrangeurs_ ;
- l'instrumentation (principalement pour savoir s'il s'agit de version "flex-band" à quelques voix ou pour orchestre complet) ;
- _le type de pièce_ (variété, classique, etc.) ;
- la taille des partitions (pour les marches de concert ou les marches de défilé, c'est souvent du A4 respectivement A5), pour les copies… de sécurité ;
- l'emplacement ;
- l'état ;
- _les manques_ ;
- les mouvements (qui reprennent les propriétés _en italique_ de cette liste) ;
- les parties, avec les fichiers électroniques si besoin ;
- le niveau ;
- l'éditeur ;
- l'année d'édition ;
- la référence ;
- _d'éventuelles remarques_.

Le projet fonctionne avec Symfony 6.2, et ne nécessite donc qu'un serveur remplissant les prérequis de cette version de Symfony pour le faire fonctionner.

## Statuts de partitions

L'application est livrée avec 7 emplacements et 3 états indépendants qui sont parfois liés à un élément de mise en page.

Dénomination                                           | Couleur                                    | Utilisation
-------------------------------------------------------|--------------------------------------------|---
Perdue                                                 | Texte noir sur fond gris                   | Pièces qui sont présentes dans la base de données, mais dont la partition physique n'est plus du tout disponible (trop de manques donc jetées, jamais revenu de prêt, partitions qui ont subi trop d'outrages du temps, trop de mauvais souvenirs…)
Rendue à une autre société                             | Texte gris sur fond blanc                  | Pièces qui sont en prêt chez d'autres sociétés
En prêt à une autre société                            | Texte gris sur fond blanc                  | Pièces qui étaient prêtées par d'autres sociétés. Elles sont dans la base de données pour l'historique de ce qui a été joué
Emplacement inconnu / à confirmer                      | Bleu clair                                 | Nouvelles pièces qui viennent d'être reçues<br />Pièces ressorties des archives<br />Pièces de retour de prêt<br />Pièces dont le statut réel est bel et bien inconnu
Version électronique                                   | Noir sur blanc (mise en page "par défaut") | Pièces dont les partitions sont disponibles en téléchargement depuis le serveur de l'application
Dans un carton d’archives                              | Noir sur blanc (mise en page "par défaut") | Pièces qui dorment dans les cartons, avec les partitions ayant le numéro dessus. Ceci indépendamment du fait que toutes les voix soient présentes ou non
Non vérifié                                            | Rouge                                      | Pièces dont l'état n'est pas vérifié, donc on ne sait pas si le jeu est complet ni évidemment ce qu'il pourrait manquer
Partitions manquantes                                  | Orange                                     | Pièces vérifiées dont on sait qu'il y a des manques
Non tamponné                                           | Bleu foncé                                 | Pièces vérifiées, mais non marquées au tampon encreur
Non coloré                                             | Bleu foncé                                 | Pièces vérifiées, mais non marquées d'une note de couleur
Quelque part dans les archives vérifié coloré tamponné | Vert                                       | Pièces vérifiées, tamponnées et marquées, attendant d'être rangées dans un carton d'archives