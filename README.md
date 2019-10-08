# Instrum partitions

L'[Union Instrumentale de Bex](http://www.instrumbex.ch) fête ses 150 ans en 2019. On peut imaginer la quantité de partitions qui ont été jouées depuis… et cela représente une grande quantité de données d'archives qui sont répertoriées sous différents formats. Le plus vieux à ce jour est un système de "cartes de visites" pour chaque partition, ce qui rend fastidieux la recherche. Heureusement, ce système a été saisi dans un fichier Excel, ce qui a grandement facilité la migration vers cette solution.  
L'archivage physique se base sur un format numérique, les partitions étant tamponnées d'un numéro qui les identifie.

Seulement :
 
- les cartes, ça prend une place conséquente ;
- la gestion des accès n'est pas tres aisée ;
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

- leur titre ;
- une éventuelle traduction ;
- les compositeurs ;
- les arrangeurs ;
- l'instrumentation (principalement pour savoir s'il s'agit de version "flex-band" à quelques voix ou pour orchestre complet) ;
- le type de pièce ;
- la taille des partitions (pour les marches de concert ou les marches de défilé, c'est souvent du A4 respectivement A5), pour les copies… de sécurité ;
- l'état (complet, prêté, etc.) ;
- les mouvements (qui reprennent les propriétés "de base" des partitions) ;
- les voix, avec les fichiers électroniques si besoin ;
- le niveau ;
- l'éditeur ;
- l'année d'édition ;
- la référence ;
- d'éventuelles remarques.

Le projet fonctionne avec Symfony 3 (3.4.32 en date de modification), et ne nécessite donc qu'un serveur remplissant les prérequis de cette version de Symfony pour le faire fonctionner.

## Statuts de partitions

L'application est livrée avec 6 statuts qui sont parfois liés à un élément de mise en page :

Dénomination               | Couleur                           | Utilisation
---------------------------|-----------------------------------|---
Non vérifié (inconnu)      | Bleu                              | Nouvelles pièces qui viennent d'être reçues<br />Pièces ressorties des archives<br />Pièces de retour de prêt<br />Pièces dont le statut réel est bel et bien inconnu
Vérifié, non marqué        | Vert                              | Pièces dont l'état a été vérifié (les éventuels manques ayant été saisis), mais le temps de mettre les numéros sur toutes les partitions n'a pas été pris
Rangé                      | Blanc (mise en page "par défaut") | Pièces qui dorment dans les cartons, avec les partitions ayant le numéro dessus. Ceci indépendamment du fait que toutes les voix soient présentes ou non
Perdu                      | Rouge                             | Pièces qui sont présentes dans la base de données, mais dont la partition physique n'est plus du tout disponible (trop de manques donc jetées, jamais revenu de prêt, partitions qui ont subi trop d'outrages du temps, trop de mauvais souvenirs…)
Prêté  à une autre société | Gris clair (texte seulement)      | Pièces qui sont en prêt chez d'autres sociétés
Rendu à une autre société  | Gris clair (texte seulement)      | Pièces qui étaient prêtées par d'autres sociétés. Elles sont dans la base de données pour l'historique de ce qui a été joué

A noter que si le statut est "Rangé" ou "Vérifié, non marqué" et qu'il y a des manques de signalé, la couleur n'est plus blanche, mais orange.

### Statuts et cycle de vie d'une pièce

1. **Nouvelle pièce**

	A la réception, la pièce doit être au minimum saisie, ce qui attribue d'office un numéro unique à la pièce.  
	Les informations obligatoires sont le **titre** et le(s) **compositeur(s)**. Le statut est **Non vérifié (inconnu)**.
	
	Ces partitions vont très probablement attendre qu'on n'ait plus besoin d'elles dans un lieu à portée de main.

2. **Pièces plus jouées**

	Après un concert (ou la dernière exécution d'un programme, ou au changement d'un programme), les pièces plus jouées doivent être vérifiées, c'est-à-dire qu'on va vérifier qu'on ait tous les originaux qui permettent son exécution. Normalement, le statut de ces pièces est _Non vérifié (inconnu)_.
	  
	1. Si le temps le permet, chaque partition originale est estampillée du numéro qui a été attribué à la pièce lors de sa réception. Les partitions manquantes sont saisies comme telles. Les informations qui n'avaient pas été saisies lors de la réception sont complétées, et la pièce rangée parmi les archives.  
	Le statut devient alors **Rangé**.  
	Ces partitions attendront un certain temps dans les boîtes à archives.
	
	2. Si le temps n'a été pris que pour vérifier si elles étaient complètes, mais que le numéro n'a pas été apposé sur toutes les partitions (dans le cas de partitions achetées pour le programme qui vient d'être remplacé), le statut devient **Vérifié, non marqué**.  
	Ces pièces attendront qu'on leur appose le numéro avant d'être *Rangé*es.

3. **Prêt à d'autres sociétés**

	Les directeurs aiment bien pouvoir emprunter des partitions quelque part pour les jouer jouer autre part, et il faut dire que les comptes des sociétés s'en trouvent moins grêlés de frais. Il y a un statut prévu pour ça : **Prêté à une autre société**.

4. **Retour de prêt**

	1. Si on ne fait que prendre note du retour, le statut devient **Non vérifié (inconnu)**, en attendant qu'on ait le temps de vérifier. On ne range évidemment pas ces partitions dans les archives.
	2. Si en revanche le temps de tout vérifier est pris, comme les originaux sont déjà marqués, on va juste relever les éventuels manques et mettre le statut **Rangé**.