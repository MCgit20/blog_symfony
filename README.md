# Blog Symfony - Projet Final du Module

📋 Description du Projet
Ce projet est un blog complet développé avec Symfony (dernière version) dans le cadre de l'examen final du module de développement web PHP. L'objectif est de créer une plateforme de blog moderne avec un système de gestion de contenu, d'utilisateurs et de commentaires, tout en respectant les bonnes pratiques de développement Symfony.
Le blog implémente un système de rôles et permissions permettant de différencier les actions possibles selon le type d'utilisateur (visiteur, utilisateur connecté, administrateur).
🎯 Objectifs du Projet

Démontrer la maîtrise du framework Symfony
Mettre en œuvre un système d'authentification et d'autorisation robuste
Créer une interface utilisateur responsive avec Bootstrap
Appliquer les principes de développement web moderne
Gérer le versioning du code avec Git et GitHub

✨ Fonctionnalités Principales
👤 Visiteur (non connecté)
Le visiteur dispose d'un accès limité aux fonctionnalités publiques :

✅ Consultation de la page d'accueil
✅ Navigation dans la liste des articles publiés
✅ Lecture complète des articles
✅ Visualisation des commentaires existants
❌ Impossibilité d'ajouter des commentaires
❌ Pas d'accès aux fonctionnalités réservées

🔐 Utilisateur Connecté (ROLE_USER)
L'utilisateur authentifié bénéficie de fonctionnalités étendues :

✅ Toutes les fonctionnalités du visiteur
✅ Ajout de commentaires sur les articles
✅ Consultation de son profil personnel
✅ Modification de ses informations personnelles (fonctionnalité facultative)
✅ Gestion de sa photo de profil (fonctionnalité facultative)

⚙️ Administrateur (ROLE_ADMIN)
L'administrateur dispose de tous les pouvoirs sur la plateforme :
Gestion des Articles

➕ Création de nouveaux articles
✏️ Modification des articles existants
🗑️ Suppression d'articles
🖼️ Gestion des images associées aux articles
📁 Attribution de catégories aux articles

Gestion des Utilisateurs

👥 Consultation de la liste complète des utilisateurs inscrits
✅ Validation des nouveaux comptes utilisateurs
❌ Désactivation/blocage de comptes utilisateurs
🔍 Visualisation des profils utilisateurs

Gestion des Commentaires (fonctionnalité facultative)

✅ Approbation des commentaires en attente
❌ Désapprobation/suppression de commentaires inappropriés
👁️ Modération globale des commentaires

🛠️ Technologies et Outils
Backend

Framework : Symfony 7.x (dernière version)
Langage : PHP 8.2+
ORM : Doctrine
Sécurité : Symfony Security Bundle
Validation : Symfony Validator

Frontend

Framework CSS : Bootstrap 5
Template : Template Bootstrap adapté et personnalisé
Design : Interface responsive et moderne

Base de Données

SGBD : MySQL 8.0+ / MariaDB 10.6+
Migrations : Doctrine Migrations

Outils de Développement

Versioning : Git
Hébergement du code : GitHub
Serveur local : Symfony CLI

📦 Structure des Entités

👤 User (Utilisateur)
- id : integer (Identifiant unique)
- email : string (Adresse e-mail unique)
- password : string (Mot de passe haché)
- roles : array (ROLE_USER, ROLE_ADMIN)
- firstName : string (Prénom)
- lastName : string (Nom)
- profilePicture : string (URL photo de profil) *facultatif*
- createdAt : datetime (Date d'inscription)
- updatedAt : datetime (Dernière mise à jour) *facultatif*
  
Relations : OneToMany avec Post, OneToMany avec Comment

📄 Post (Article)
- id : integer (Identifiant unique)
- title : string (Titre de l'article)
- content : text (Contenu complet)
- publishedAt : datetime (Date de publication)
- picture : string (URL de l'image)
- author : User (Auteur de l'article)
- category : Category (Catégorie)
  
Relations : ManyToOne avec User, ManyToOne avec Category, OneToMany avec Comment

📁 Category (Catégorie)
- id : integer (Identifiant unique)
- name : string (Nom de la catégorie)
- description : text (Description) *facultatif*
  
Relations : OneToMany avec Post

💬 Comment (Commentaire)
- id : integer (Identifiant unique)
- content : text (Contenu du commentaire)
- createdAt : datetime (Date de création)
- status : string (validé, en attente, supprimé) *facultatif*
- author : User (Auteur du commentaire)
- post : Post (Article commenté)
  
Relations : ManyToOne avec User, ManyToOne avec Post

🔗 Relations entre Entités
User ──(1:N)──> Post       (Un utilisateur peut écrire plusieurs articles)
User ──(1:N)──> Comment    (Un utilisateur peut écrire plusieurs commentaires)
Post ──(N:1)──> Category   (Un article appartient à une catégorie)
Post ──(1:N)──> Comment    (Un article peut avoir plusieurs commentaires)

🚀 Installation et Configuration

Prérequis

- PHP 8.2 ou supérieur
- Composer
- Symfony CLI
- MySQL 8.0+ ou MariaDB 10.6+
- Git
- 
Étapes d'Installation

1️⃣ Cloner le repository
git clone https://github.com/[votre-username]/[nom-du-repo].git
cd [nom-du-repo]

2️⃣ Installer les dépendances
bashcomposer install
3️⃣ Configurer les variables d'environnement
bash# Copier le fichier .env
cp .env .env.local

# Éditer .env.local et configurer la connexion à la base de données

DATABASE_URL="mysql://username:password@127.0.0.1:3306/blog_symfony"

4️⃣ Créer la base de données
php bin/console doctrine:database:create

5️⃣ Exécuter les migrations
php bin/console doctrine:migrations:migrate

6️⃣ Charger les fixtures (optionnel)
php bin/console doctrine:fixtures:load

7️⃣ Lancer le serveur de développement
symfony server:start
```

8️⃣ **Accéder à l'application**
```
http://localhost:8000
👥 Système de Rôles et Permissions
Configuration des Rôles

ROLE_USER : Rôle par défaut attribué à tous les utilisateurs inscrits
ROLE_ADMIN : Rôle administrateur avec tous les privilèges

Hiérarchie des Rôles

yamlsecurity:
    role_hierarchy:
        ROLE_ADMIN: ROLE_USER
Protection des Routes
Les routes sont sécurisées via les attributs #[IsGranted()] ou la configuration security.yaml :
php#[IsGranted('ROLE_ADMIN')]
public function admin(): Response
{
    // Accessible uniquement aux administrateurs
}
```

## 📁 Structure du Projet
```
├── config/              # Configuration Symfony
├── public/              # Point d'entrée et assets publics
├── src/
│   ├── Controller/      # Contrôleurs
│   ├── Entity/          # Entités Doctrine
│   ├── Form/            # Formulaires Symfony
│   ├── Repository/      # Repositories Doctrine
│   └── Security/        # Configuration sécurité
├── templates/           # Templates Twig
│   ├── base.html.twig
│   ├── post/
│   ├── user/
│   └── admin/
├── migrations/          # Migrations de base de données
└── tests/               # Tests unitaires et fonctionnels
🧪 Tests et Validation
Tests à Effectuer

✅ Création, modification et suppression d'articles (Admin)
✅ Ajout de commentaires (Utilisateur connecté)
✅ Gestion des profils utilisateurs
✅ Vérification des permissions selon les rôles
✅ Test de l'authentification (inscription, connexion, déconnexion)
✅ Validation des formulaires
✅ Responsive design sur différents appareils

Commandes de Test
bash# Lancer les tests
php bin/phpunit

# Vérifier la qualité du code
php vendor/bin/phpstan analyse src

# Vérifier le respect des standards de code
php vendor/bin/php-cs-fixer fix --dry-run
📊 Critères d'Évaluation

✅ Fonctionnalité : Toutes les pages et fonctionnalités doivent être opérationnelles
✅ Sécurité : Mise en œuvre correcte des rôles et permissions
✅ Qualité du code : Respect des bonnes pratiques Symfony et standards PSR
✅ Git : Utilisation efficace de Git avec des commits réguliers et clairs
✅ Design : Interface Bootstrap responsive et ergonomique
