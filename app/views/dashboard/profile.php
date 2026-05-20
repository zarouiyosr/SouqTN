<?php
if (!isset($_SESSION['user_id'])) { header("Location: /SouqTN/public/login"); exit; } ?>
<?php $user = $user ?? []; $error = $error ?? null; $success = $success ?? null; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon profil — SouqTN</title>
  <link rel="stylesheet" href="/SouqTN/public/css/dashboard-client.css">
  <style>
    .profile-card{background:var(--pearl);border:1px solid var(--border);border-radius:16px;
      padding:30px;max-width:520px;box-shadow:var(--shadow)}
    .profile-card h2{font-family:'Plus Jakarta Sans',sans-serif;margin:0 0 22px;font-size:1.15rem}
    .pf-group{margin-bottom:18px}
    .pf-group label{display:block;font-size:.8rem;color:var(--muted);margin-bottom:6px;font-weight:600}
    .pf-group input{width:100%;padding:11px 14px;border:1px solid var(--border);border-radius:10px;
      font-size:.92rem;box-sizing:border-box;background:var(--surface2);color:var(--ink)}
    .pf-group input:focus{outline:none;border-color:var(--sky);background:#fff}
    .pf-btn{width:100%;padding:12px;border:none;border-radius:10px;background:var(--sky-dk);
      color:#fff;font-weight:600;font-size:.95rem;cursor:pointer;transition:.2s}
    .pf-btn:hover{filter:brightness(1.07)}
    .pf-msg{padding:11px 14px;border-radius:10px;font-size:.88rem;margin-bottom:18px}
    .pf-ok {background:rgba(29,158,117,.15);color:#1D9E75}
    .pf-err{background:rgba(224,75,74,.13);color:#E04B4A}
    .pf-hint{font-size:.74rem;color:var(--muted);margin-top:5px}
  </style>
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-logo">Souq<span>TN</span></div>
  <div class="nav-section-label">Menu</div>
  <nav>
    <a href="/SouqTN/public/dashboard/client" class="nav-item">🏠 Accueil</a>
    <a href="/SouqTN/public/tracking" class="nav-item">🚚 Suivi de livraison</a>
    <a href="/SouqTN/public/profile" class="nav-item active">👤 Mon profil</a>
  </nav>
  <div class="sidebar-footer">
    <div class="user-pill">
      <div class="user-avatar"><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></div>
      <div class="user-info">
        <div class="user-name"><?= htmlspecialchars($_SESSION['username']) ?></div>
        <div class="user-role">Client</div>
      </div>
      <a href="/SouqTN/public/logout" class="user-logout" title="Déconnexion">⎋</a>
    </div>
  </div>
</aside>

<main class="main">

  <div class="topbar">
    <div>
      <h1 class="page-title">Mon profil</h1>
      <p class="page-subtitle">Gérez vos informations personnelles.</p>
    </div>
    <div class="badge-date">📅 <?= date('d M Y') ?></div>
  </div>

  <div class="profile-card">
    <h2>Informations du compte</h2>

    <?php if ($success): ?>
      <div class="pf-msg pf-ok">✓ Vos informations ont été mises à jour.</div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="pf-msg pf-err">⚠ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/SouqTN/public/profile/update">

      <div class="pf-group">
        <label>Nom d'utilisateur</label>
        <input name="username" type="text" required
               value="<?= htmlspecialchars($user['username'] ?? '') ?>">
      </div>

      <div class="pf-group">
        <label>Adresse email</label>
        <input name="email" type="email" required
               value="<?= htmlspecialchars($user['email'] ?? '') ?>">
      </div>

      <div class="pf-group">
        <label>Nouveau mot de passe</label>
        <input name="password" type="password" placeholder="Laisser vide pour ne pas changer">
        <div class="pf-hint">Min. 8 caractères. Laissez vide si vous ne voulez pas le modifier.</div>
      </div>

      <button type="submit" class="pf-btn">Enregistrer les modifications</button>

    </form>
  </div>

</main>

</body>
</html>

