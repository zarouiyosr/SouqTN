# Comment publier le projet sur GitHub

Le projet est prêt avec une structure professionnelle. Voici les
étapes pour le mettre en ligne sur GitHub.

## 1. Créer le dépôt sur GitHub (dans le navigateur)

1. Aller sur https://github.com → bouton **New repository**
2. Nom : `SouqTN`
3. Visibilité : Public ou Private (selon votre choix)
4. **Ne pas** cocher « Add README » (on en a déjà un)
5. Cliquer **Create repository**

## 2. Pousser le projet (dans le terminal)

Depuis le dossier du projet :

```bash
cd SouqTN
git init
git add .
git commit -m "chore: structure initiale du projet SouqTN"
git branch -M main
git remote add origin https://github.com/VOTRE_COMPTE/SouqTN.git
git push -u origin main
```

(Remplacez `VOTRE_COMPTE` par votre nom d'utilisateur GitHub.)

## 3. Créer les branches de l'équipe

```bash
git branch feature/p1
git branch feature/p2
git branch feature/p3
git branch feature/p4
git push origin feature/p1 feature/p2 feature/p3 feature/p4
```

Chaque membre travaille ensuite sur sa branche (voir
`docs/PLAN_COMMITS_GIT.md`).

## Structure du dépôt obtenue

```
SouqTN/
├── .github/
│   ├── ISSUE_TEMPLATE/
│   │   ├── bug_report.md
│   │   └── feature_request.md
│   ├── workflows/
│   │   └── php-lint.yml          ← vérif auto syntaxe PHP
│   └── PULL_REQUEST_TEMPLATE.md
├── docs/
│   ├── ARCHITECTURE.md
│   ├── INSTALLATION.md
│   ├── REPARTITION_EQUIPE.md
│   └── PLAN_COMMITS_GIT.md
├── app/            (controllers, models, views)
├── core/
├── crud/
├── public/
├── .gitignore
├── CONTRIBUTING.md
├── LICENSE
├── README.md
├── config.php
└── database.sql
```

## Bonnes pratiques

- Le fichier `.gitignore` évite de pousser les fichiers inutiles
  (uploads, logs, .DS_Store).
- Les **templates** (issues / PR) s'affichent automatiquement sur
  GitHub quand un membre crée une issue ou une pull request.
- Le **workflow** `php-lint.yml` vérifie la syntaxe PHP à chaque push
  (onglet « Actions » sur GitHub).
- Ne poussez **jamais** un vrai mot de passe dans `config.php` si le
  dépôt est public.
