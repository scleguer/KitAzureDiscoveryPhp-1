# Kit Azure Discovery pour PHP

Ce projet est destiné à vous aider à découvrir Microsoft Azure. Si vous le 
déployez sur une Application Web Azure, vous pourrez alors utiliser votre 
webcam pour effectuer des captures écran s'appuyant sur une détection de 
mouvement.

## Un projet Symfony 2

Ce projet s'appuie sur le framework PHP Symfony 2.

## Stockage des images

Cette version utilise le Cache Redis Azure. Ce stockage vient en complément d'un stockage des images sur le système de fichier de l'application. La priorité est donnée à la récupération des images sur le Cache Redis Azure.

### Console KUDU

Après avoir déployé les sources de ce projet sur une Application Web Azure,
vous devrez passer les lignes de commandes ci-dessous pour que application
fonctionne correctement en mode production. Cela permettra d'installer les 
indispensables bibliothèques ainsi que de générer les fichiers JS et CSS 
nécessaires.

Utilisez pour cela la console proposée par KUDU et rendue disponible par 
Microsoft Azure. 

Pour y accéder, utiliser l'URL https://lenomdevotreapplication.scm.azurewebsites.net, 
puis sélectionnez la catégorie :

> Debug console 

puis la commande :

> CMD

### Installation de composer

Pour commencer, déplacez-vous ensuite à l'intérieur du dossier wwwroot, contenu dans le dossier
site :

```
D:\home>cd site\wwwroot
```

Afin de pouvoir procéder à l'installation des bibliothèques attendues pour 
ce projet, vous devrez installer composer grâce à la ligne de commande suivante :

```
D:\home\site\wwwroot>curl -sS https://getcomposer.org/installer | php
```

### Installation des bibliothèques

Ensuite, appelez la commande composer pour installer les dépendances :

```
D:\home\site\wwwroot>php composer.phar install
```

Lorsque la configuration des paramètres vous est demandée, laissez toutes les valeurs
par défaut.

### Génération des fichiers

Enfin, utilisez la commande qui suit pour générer les fichiers CSS et JS :

```
D:\home\site\wwwroot>php app/console assetic:dump --env=prod --no-debug
```

Testez votre site déployé sur Microsoft Azure à l'URL : http://lenomdevotreapplication.azurewebsites.net

Et voilà ;)
