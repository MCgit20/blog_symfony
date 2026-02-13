# 🧪 Guide de Test Rapide - Blog Symfony

## **AVANT DE COMMENCER**

✅ S'assurer que :
- [x] Les fixtures ont été chargées (`symfony console doctrine:fixtures:load`)
- [x] Le serveur tourne (`symfony serve`)
- [x] Aucune erreur SQL ou configuration

---

## **1️⃣ TEST ACCÈS PUBLIC (visiteur)**

### Étape 1: Accueil
- **URL**: http://127.0.0.1:8000/
- **Attendu**: 
  - ✅ Page affichée avec 5 articles de test
  - ✅ Boutons "Lire plus" visibles
  - ✅ Menu avec "Connexion" et "Inscription"

### Étape 2: Cliquer sur "Lire plus"
- **URL**: http://127.0.0.1:8000/post/1
- **Attendu**:
  - ✅ Détail d'un article affiché
  - ✅ Contenu complet visible
  - ✅ Section commentaires visible
  - ✅ Alerte: "Connectez-vous pour ajouter un commentaire"
  - ❌ Pas de formulaire de commentaire

---

## **2️⃣ TEST INSCRIPTION**

### Étape 1: Aller à l'inscription
- **URL**: http://127.0.0.1:8000/register
- **Remplir**: 
  - Email: test@blog.com
  - Prénom: Jean
  - Nom: Dupont
  - Mot de passe: Test123456 (au moins 6 caractères)
  - Accepter les conditions

### Étape 2: Soumettre
- **Attendu**:
  - ✅ Redirection après inscription
  - ✅ Utilisateur connecté automatiquement
  - ✅ Menu affiche "Déconnexion" au lieu de "Connexion"

---

## **3️⃣ TEST UTILISATEUR CONNECTÉ**

### Étape 1: Ajouter un commentaire
- **URL**: http://127.0.0.1:8000/post/1
- **Action**: Remplir le formulaire et cliquer "Ajouter un commentaire"
- **Attendu**:
  - ✅ Commentaire ajouté sous l'article
  - ✅ Affiche votre nom et la date
  - ✅ Peut ajouter plusieurs commentaires

### Étape 2: Vérifier l'accès restreint
- **URL**: http://127.0.0.1:8000/admin/post
- **Attendu**: 
  - ❌ **Erreur 403 Access Denied** (normal, pas admin)

### Étape 3: Déconnexion
- **Action**: Cliquer "Déconnexion"
- **Attendu**:
  - ✅ Redirection à l'accueil
  - ✅ Menu affiche "Connexion" de nouveau

---

## **4️⃣ TEST ADMINISTRATEUR**

### Connexion Admin
- **URL**: http://127.0.0.1:8000/login
- **Identifiants**: 
  - admin@blog.com
  - admin123

### Étape 1: Gestion des articles
- **URL**: http://127.0.0.1:8000/admin/post
- **Attendu**:
  - ✅ Liste de tous les articles
  - ✅ Boutons "edit" et "delete" visibles

### Étape 2: Créer un article
- **URL**: http://127.0.0.1:8000/admin/post/new
- **Remplir**:
  - Titre: "Mon premier article admin"
  - Contenu: "Contenu de test..."
  - Catégorie: "Technologie"
- **Attendu**:
  - ✅ Article créé visible dans la liste
  - ✅ Article visible sur la page d'accueil

### Étape 3: Éditer un article
- **URL**: http://127.0.0.1:8000/admin/post/{id}/edit
- **Action**: Modifier le titre
- **Attendu**:
  - ✅ Changement sauvegardé et visible

### Étape 4: Supprimer un article
- **URL**: http://127.0.0.1:8000/admin/post
- **Action**: Cliquer le bouton "delete"
- **Attendu**:
  - ✅ Article supprimé de la liste

### Étape 5: Gestion des catégories
- **URL**: http://127.0.0.1:8000/category
- **Attendu**:
  - ✅ Liste des 3 catégories (Technologie, Voyage, Cuisine)
  - ✅ Boutons pour créer/éditer/supprimer

### Étape 6: Gestion des commentaires
- **URL**: http://127.0.0.1:8000/comment
- **Attendu**:
  - ✅ Liste de tous les commentaires
  - ✅ Possibilité de approuver/supprimer (optionnel)

---

## **5️⃣ TEST RESPONSIVE DESIGN**

### F12 DevTools (Chrome/Firefox)
- **Mobile (375px)**:
  - ✅ Menu responsive
  - ✅ Cartes d'articles empilées
  - ✅ Textes lisibles

- **Tablette (768px)**:
  - ✅ 2 colonnes d'articles
  - ✅ Navigation fluide

- **Desktop (1024px+)**:
  - ✅ 3-4 colonnes d'articles
  - ✅ Layout complet

---

## **🔒 TESTS DE SÉCURITÉ**

### Test 1: Accès non autorisé
1. Se déconnecter
2. Modifier manuellement l'URL: http://127.0.0.1:8000/admin/post
3. **Attendu**: ❌ **Redirection vers /login** (sécurité OK)

### Test 2: CSRF Protection
1. Créer un article admin
2. Network tab -> Vérifier presence `_token`
3. **Attendu**: ✅ Token présent dans formulaires

---

## **Résumé des fonctionnalités**

| Fonctionnalité | Visiteur | User | Admin |
|---|:---:|:---:|:---:|
| Voir accueil | ✅ | ✅ | ✅ |
| Voir articles | ✅ | ✅ | ✅ |
| Ajouter commentaire | ❌ | ✅ | ✅ |
| Gérer articles | ❌ | ❌ | ✅ |
| Gérer catégories | ❌ | ❌ | ✅ |
| Gérer commentaires | ❌ | ❌ | ✅ |

---

## **🐛 Si quelque chose ne fonctionne pas**

```bash
# Vérifier les logs
tail -f var/log/dev.log

# Nettoyer le cache
symfony console cache:clear

# Recharger les fixtures
symfony console doctrine:fixtures:load --no-interaction

# Relancer le serveur
symfony serve
```
