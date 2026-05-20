<?php
 if (!isset($_SESSION['user_id'])) { header("Location: /SouqTN/public/login"); exit; } ?>
<?php
$orders     = $orders     ?? [];
$orderStats = $orderStats ?? ['total_cmd'=>0,'en_cours'=>0,'livrees'=>0,'montant_total'=>0];
$enCours    = $enCours    ?? [];
$livrees    = $livrees    ?? [];

function fmtPrix($n){ return number_format((float)$n, 3, ',', ' ') . ' TND'; }
function fmtDate($d){ return $d ? date('d/m/Y', strtotime($d)) : '—'; }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon espace — SouqTN</title>
  <link rel="stylesheet" href="/SouqTN/public/css/dashboard-client.css">
  <style>
    .badge-statut{display:inline-block;padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:600}
    .st-encours{background:rgba(232,160,32,.16);color:#B26A00}
    .st-livree {background:rgba(29,158,117,.16);color:#1D9E75}
    .order-block{margin-bottom:34px}
    .order-block h2{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.05rem;margin:0 0 14px;display:flex;align-items:center;gap:8px}
    .mini-tag{font-size:.7rem;background:var(--sky-bg);color:var(--sky-dk);padding:2px 9px;border-radius:20px}
  </style>
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-logo">Souq<span>TN</span></div>

  <div class="nav-section-label">Menu</div>
  <nav>
    <a href="/SouqTN/public/dashboard/client" class="nav-item active">🏠 Accueil</a>
    <a href="/SouqTN/public/tracking" class="nav-item">🚚 Suivi de livraison</a>
    <a href="/SouqTN/public/profile" class="nav-item">👤 Mon profil</a>
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
      <h1 class="page-title">Bonjour, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h1>
      <p class="page-subtitle">Suivi de vos commandes et livraisons.</p>
    </div>
    <div class="badge-date">📅 <?= date('d M Y') ?></div>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon">📦</div>
      <div class="stat-label">Commandes passées</div>
      <div class="stat-value"><?= (int)$orderStats['total_cmd'] ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">🚚</div>
      <div class="stat-label">Livraisons en cours</div>
      <div class="stat-value"><?= (int)$orderStats['en_cours'] ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">✅</div>
      <div class="stat-label">Livraisons reçues</div>
      <div class="stat-value"><?= (int)$orderStats['livrees'] ?></div>
    </div>
  </div>

  <?php if (empty($orders)): ?>

    <div class="table-card">
      <div class="empty-state">
        <span class="empty-icon">🛒</span>
        <p>Vous n'avez pas encore passé de commande.</p>
        <a href="/SouqTN/public/" class="btn-outline" style="margin-top:12px;display:inline-block">
          Découvrir la boutique →
        </a>
      </div>
    </div>

  <?php else: ?>

    <div class="order-block">
      <h2>🚚 Livraisons en cours <span class="mini-tag"><?= count($enCours) ?></span></h2>
      <div class="table-card">
        <?php if (empty($enCours)): ?>
          <div class="empty-state"><span class="empty-icon">📭</span><p>Aucune livraison en cours.</p></div>
        <?php else: ?>
          <table>
            <thead><tr><th>N° Commande</th><th>Date</th><th>Articles</th><th>Total</th><th>Statut</th></tr></thead>
            <tbody>
              <?php foreach ($enCours as $o): ?>
              <tr>
                <td><strong>#<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></strong></td>
                <td><?= fmtDate($o['created_at']) ?></td>
                <td><?= (int)$o['nb_unites'] ?> article(s)</td>
                <td><?= fmtPrix($o['total']) ?></td>
                <td><span class="badge-statut st-encours">En cours de livraison</span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>

    <div class="order-block">
      <h2>✅ Livraisons reçues <span class="mini-tag"><?= count($livrees) ?></span></h2>
      <div class="table-card">
        <?php if (empty($livrees)): ?>
          <div class="empty-state"><span class="empty-icon">📦</span><p>Aucune livraison reçue pour l'instant.</p></div>
        <?php else: ?>
          <table>
            <thead><tr><th>N° Commande</th><th>Date</th><th>Articles</th><th>Total</th><th>Statut</th></tr></thead>
            <tbody>
              <?php foreach ($livrees as $o): ?>
              <tr>
                <td><strong>#<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></strong></td>
                <td><?= fmtDate($o['created_at']) ?></td>
                <td><?= (int)$o['nb_unites'] ?> article(s)</td>
                <td><?= fmtPrix($o['total']) ?></td>
                <td><span class="badge-statut st-livree">Livrée</span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>

    <div class="order-block">
      <h2>🧾 Toutes mes commandes <span class="mini-tag"><?= count($orders) ?></span></h2>
      <div class="table-card">
        <table>
          <thead><tr><th>N° Commande</th><th>Date</th><th>Articles</th><th>Total</th><th>Statut</th></tr></thead>
          <tbody>
            <?php foreach ($orders as $o): ?>
            <tr>
              <td><strong>#<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></strong></td>
              <td><?= fmtDate($o['created_at']) ?></td>
              <td><?= (int)$o['nb_unites'] ?> article(s)</td>
              <td><?= fmtPrix($o['total']) ?></td>
              <td>
                <?php if ($o['statut'] === 'livree'): ?>
                  <span class="badge-statut st-livree">Livrée</span>
                <?php else: ?>
                  <span class="badge-statut st-encours">En cours</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  <?php endif; ?>

</main>

</body>
</html>

