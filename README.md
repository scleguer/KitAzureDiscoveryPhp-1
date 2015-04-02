# Kit Azure Discovery pour PHP

Ce projet est destiné à vous aider à découvrir Windows Azure. Si vous le 
déployez sur une Application Web Azure, vous pourrez alors utiliser votre 
webcam pour effectuer des captures écran s'appuyant sur une détection de 
mouvement.

## Un projet Symfony 2

Ce projet s'appuie sur le framework PHP Symfony 2.

### Console KUDU

Après avoir déployé les sources de ce projet sur une Application Web Azure,
vous devrez passer les lignes de commandes ci-dessous pour que application
fonctionne correctement en mode production. Cela permettra d'installer les 
indispensables bibliothèques ainsi que de générer les fichiers JS et CSS 
nécessaires.

Utilisez pour cela la console proposée par KUDU et rendue disponible par 
Windows Azure. 

Pour y accéder, utiliser l'URL https://lenomdevotreapplication.scm.azurewebsites.net, 
puis sélectionnez la catégorie :

> Debug console 

puis la commande :

> CMD

### Installation de composer

Afin de pouvoir procéder à l'installation des bibliothèques attendues pour 
ce projet, vous devrez installer composer grâce à la ligne de commande suivante :

```
```

### Installation des bibliothèques

Déplacez-vous ensuite à l'intérieur du dossier wwwroot, contenu dans le dossier
site :

```
D:\home>cd site\wwwroot
```

Puis, appeler la commande composer pour installer les dépendances :

```
D:\home\site\wwwroot>php composer.phar install
```

### Génération des fichiers

Enfin, utilisez la commande qui suit pour générer les fichiers CSS et JS :

```
D:\home\site\wwwroot>php app/console assetic:dump --env=prod --no-debug
```

Testez votre site déployé sur Windows Azure à l'URL : http://lenomdevotreapplication.azurewebsites.net

Et voilà ;)