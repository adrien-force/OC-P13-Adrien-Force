<h1 align="center">Site et API de GreenGoodies</h1>
<p align="center"><i>Projet N°13 de la formation Développeur d'application PHP Symfony
@OpenClassrooms <br> <a href="https://github.com/adrien-force/OC-P13-Adrien-Force/commits?author=adrien-force"><img src="https://img.shields.io/badge/Auteur_:-Adrien_FORCE-orange"></a></i></p>

Autres langues : [English](./README.en.md)

## 🎯 Table des matières
- [Description du projet](#-description)
- [Installation du projet](#-installation)
- [Prérequis](#-prérequis)
- [Utilisation du projet](#-utilisation)


## 📄 Description
<br>

Ce projet consiste à continuer le developpement d'un site web et une API pour une entreprise fictive nommée GreenGoodies.
Ce site web a pour but d'apporter une solution permettant aux clients de visualiser les produits de l'entreprise, de les ajouter à leur panier et de passer commande.

Durant ce projet, j'ai ajouté un système d'authentification par utilisateur, permettant de sécuriser l'accès aux différentes fonctionnalités du site.
Le project contient un système de gestion de panier, permettant aux utilisateurs de visualiser les produits ajoutés à leur panier, de les modifier ou de les supprimer.
Les commandes passées par les utilisateurs sont enregistrées en base de données, et peuvent être consultées par les utilisateurs; et sont bien entendu fictives ;).
<br> <br>


## 🔧 Prérequis

- Symfony ^7.0
- Symfony CLI
- Composer
- PHP >= 8.0, < 8.4
- Docker

## 🛠️ Installation

1. Cloner le projet sur votre machine
```bash
git clone https://github.com/adrien-force/OC-P13-Adrien-Force.git
```

2. Installer le projet
```bash
make install
```

Note : Si tout va bien les ports du container de la base de données sont valides, sinon il faudra les changer dans le fichier .env.local en les remplaçant par les ports affichés par ``docker ps``

## 🔥️ Utilisation

Le projet est un site web développé en PHP, HTML et pure CSS, sans librairies front-end.

Pour commencer à utiliser rendez-vous sur l'url local : http://greengoodies.local

Il est possible de s'inscrire, ou de se connecter avec un compte utilisateur déjà existant.
Dans les différents comptes créés avec les fixtures, 
<br> il y a un compte administrateur :
- email : admin@greengoodies.com
<br> et un compte utilisateur basique :
- email: user@greengoodies.com

Tous les utilisateurs créés avec les fixtures ont le mot de passe "password".
<br> <br>

Une documentation API est disponible à l'adresse : http://greengoodies.local/api/doc

<br>

