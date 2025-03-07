# Shape of You 💅

**Shape of You** est une application mobile-first qui aide les utilisateurs à mieux s’habiller grâce à l’intelligence artificielle. L’application propose des suggestions de tenues basées sur la morphologie, les tendances et la garde-robe des utilisateurs.


## Technologies utilisées

- **Back-end** : PHP Symfony
- **Front-end** : Twig, TailwindCSS
- **Intelligence Artificielle** : OpenAI
- **Base de données** : PostgreSQL
- **Déploiement** : OVH et DNS acheté chez Route 53 (AWS)


## Fonctionnalités principales

### Côté utilisateur :
- ✅ **Analyse de tenue via IA** : Prendre/Upload une photo et identifier les éléments de la tenue
- ✅ **Suggestions personnalisées** : Propositions de tenues basées sur la garde-robe avec l'IA
- ✅ **Recherche avancée** : Filtres pour rechercher des tenues ou items
- ✅ **Achat en ligne** : Redirection vers des sites partenaires
- ✅ **Historique des tenues** : Les utilisateurs peuvent voir leur tenues postées
- ✅ **Réseau social mode** : Interaction avec d’autres utilisateurs (Like, ajouter des tenues en favoris)

### 🛠️ Côté administrateur :
- ✅ **Dashboard & stats** : Visualisation des données utilisateurs
- ✅ **Gestion des contenus** : CRUD pour utilisateurs, outfits, items
- ✅ **Modération** : Alerte IA indiquand quand un champs est vide pour un item

---

## 🚀 Installation et lancement  

### 1️⃣ Prérequis
- PHP
- Symfony CLI
- Node.js & npm
- PostgreSQL
- Composer

### 2️⃣ Cloner le projet

```bash
git clone https://github.com/EEMI-killian/mono.git
cd mono
cd app
```

### 3️⃣ Installer les dépendances

```bash
composer install
npm install
```

### 4️⃣ Configurer l’environnement  
Créer un fichier `.env` en copiant `.env.example`, puis modifier les valeurs nécessaires

```bash
cp .env.example .env
```

### 5️⃣ Lancer le serveur  

```bash
symfony server:start
npm run watch (lancer la compilation Tailwind)
```

### 6️⃣ Accéder à l’application  
[http://localhost:8000](http://localhost:8000)  


## Contribution & collaboration

### 👥 Équipe projet  
| Nom | GitHub |
|------|--------|
| Killian Angely | [@KillianAngely] |
| Kays Zahidi | [@monkeyDkz] |
| Alexis Menez | [@Alexmz1] |

### 📌 Qui a fait quoi ?  
- **Back-end** : Killian Angely, Kays Zahidi, Alexis Menez  
- **Front-end** : Killian Angely, Kays Zahidi, Alexis Menez  
- **IA & analyse d’image** : Killian Angely, Kays Zahidi, Alexis Menez   
- **Intégration & mise en production** : Killian Angely


## 📅 Déploiement  

L’application est disponible en production ici :  
🔗 [Lien vers l’application en ligne](https://makeitshineclothing.com/)  


## 🛡️ Sécurité  
- Respect des bonnes pratiques Symfony
- Validation des entrées utilisateur
- Mise en place de Voter