# Voyage-Facile

✈️ Présentation du projet
Ce projet est une application web permettant aux utilisateurs de planifier leurs voyages facilement grâce à un système d’abonnement.
L’objectif est de proposer une plateforme centralisée où l’utilisateur peut gérer ses voyages, choisir une formule d’abonnement et organiser son séjour depuis un tableau de bord intuitif.


🚀 Fonctionnalités principales

Création de compte et authentification utilisateur

Choix d’une formule d’abonnement (Basic / Premium / Pro)

Création et gestion de voyages

Ajout d’activités incluses selon la formule choisie

Tableau de bord récapitulatif du voyage

Interface responsive (desktop / tablette / mobile)

Architecture MVC avec Symfony



🛠️ Stack technique

Front-end

HTML5 / CSS3

JavaScript

Twig (templating Symfony)

Back-end

PHP 8

Symfony

Base de données

MySQL

DevOps

Docker

Git & GitHub


## Installation du projet

### Prerequis

- Docker et Docker Compose installes
- Git installe

### Etapes d'installation

1. Cloner le projet :

```bash
git clone <url-du-repository>
cd test
```

2. Verifier le fichier `.env`

La connexion a la base de donnees est deja configuree pour Docker :

```env
DATABASE_URL="mysql://app:app@db:3306/test?serverVersion=8.0&charset=utf8mb4"
```

3. Lancer les conteneurs Docker :

```bash
docker compose up -d --build
```

4. Installer les dependances PHP dans le conteneur :

```bash
docker compose exec php composer install
```

5. Creer ou mettre a jour la base de donnees avec les migrations :

```bash
docker compose exec php php bin/console doctrine:migrations:migrate
```

6. Charger les donnees de test si besoin :

```bash
docker compose exec php php bin/console doctrine:fixtures:load
```

7. Acceder au projet :

- Application : `http://localhost:8085`
- phpMyAdmin : `http://localhost:8082`

Identifiants phpMyAdmin :

- Serveur : `db`
- Utilisateur : `root`
- Mot de passe : `root`

### Commandes utiles

Arreter le projet :

```bash
docker compose down
```

Relancer le projet :

```bash
docker compose up -d
```
