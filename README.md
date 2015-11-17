# Kit Azure Discovery pour PHP

Ce projet est destiné à vous aider à découvrir Microsoft Azure. Si vous le 
déployez sur une Application Web Azure, vous pourrez alors utiliser votre 
webcam pour effectuer des captures écran s'appuyant sur une détection de 
mouvement.

## Un projet Symfony 2

Ce projet s'appuie sur le framework PHP Symfony 2.

### Console KUDU

Après avoir déployé les sources de ce projet sur une Application Web Azure,
vous devrez passer les lignes de commandes ci-dessous pour que l'application
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

Il se peut qu'à la fin de la procédure d'installation une erreur se produise, 
au moment où un bundle essaie de supprimer le cache, correspondant à la ligne suivante :

```
> Sensio\Bundle\DistributionBundle\Composer\ScriptHandler::clearCache
```

Dans ce cas, utilisez à la place, la commande qui suit :

```
D:\home\site\wwwroot>php app/console cache:clear --verbose
```

### Génération des fichiers

Si l'erreur présentée dans la section précédente s'est produite, il faut alors utiliser,
avant la prochaine commande, la commande suivante :

```
D:\home\site\wwwroot>php app/console assets:install --env=prod
```

Enfin, utilisez la commande qui suit pour générer les fichiers CSS et JS :

```
D:\home\site\wwwroot>php app/console assetic:dump --env=prod --no-debug
```

Testez votre site déployé sur Microsoft Azure à l'URL : http://lenomdevotreapplication.azurewebsites.net

Et voilà ;)
