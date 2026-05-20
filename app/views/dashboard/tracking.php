<?php if (!isset($_SESSION['user_id'])) { header("Location: /SouqTN/public/login"); exit; } ?>
<?php
$orders = $orders ?? [];
function fmtPrix($n){ return number_format((float)$n, 3, ',', ' ') . ' TND'; }
function fmtDate($d){ return $d ? date('d/m/Y à H:i', strtotime($d)) : '—'; }


function etapeNiveau(string $statut): int {
    return $statut === 'livree' ? 4 : 3;  // 4 = livrée incluse
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Suivi de livraison — SouqTN</title>
  <link rel="stylesheet" href="/SouqTN/public/css/dashboard-client.css">
  <style>
    .track-card{background:var(--pearl);border:1px solid var(--border);border-radius:16px;
      padding:24px;margin-bottom:26px;box-shadow:var(--shadow)}
    .track-head{display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;
      align-items:center;margin-bottom:22px;padding-bottom:16px;border-bottom:1px solid var(--border)}
    .track-num{font-weight:700;font-size:1.05rem;color:var(--ink)}
    .track-sub{font-size:.8rem;color:var(--muted);margin-top:3px}
    .badge-statut{display:inline-block;padding:5px 14px;border-radius:20px;font-size:.78rem;font-weight:600}
    .st-encours{background:rgba(232,160,32,.16);color:#B26A00}
    .st-livree {background:rgba(29,158,117,.16);color:#1D9E75}

    /* Timeline */
    .timeline{display:flex;justify-content:space-between;position:relative;margin:30px 10px}
    .timeline::before{content:'';position:absolute;top:16px;left:5%;right:5%;height:3px;
      background:var(--border);z-index:0}
    .tl-step{position:relative;z-index:1;text-align:center;flex:1}
    .tl-dot{width:34px;height:34px;border-radius:50%;margin:0 auto 8px;
      display:flex;align-items:center;justify-content:center;font-size:15px;
      background:var(--surface2);border:3px solid var(--border);color:var(--muted)}
    .tl-step.done .tl-dot{background:var(--sky-dk);border-color:var(--sky-dk);color:#fff}
    .tl-label{font-size:.74rem;color:var(--muted);font-weight:600}
    .tl-step.done .tl-label{color:var(--ink)}

    .track-items{margin-top:24px}
    .track-items h4{font-size:.85rem;color:var(--muted);text-transform:uppercase;
      letter-spacing:.05em;margin:0 0 10px}
    .ti-row{display:flex;justify-content:space-between;padding:9px 0;
      border-bottom:1px solid var(--border);font-size:.9rem}
    .ti-row:last-child{border-bottom:none}
    .ti-name{color:var(--ink)}
    .ti-qty{color:var(--muted);font-size:.82rem}
    .track-total{display:flex;justify-content:space-between;margin-top:14px;
      padding-top:14px;border-top:2px solid var(--border);font-weight:700}
  </style>
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-logo">Souq<span>TN</span></div>
  <div class="nav-section-label">Menu</div>
  <nav>
    <a href="/SouqTN/public/dashboard/client" class="nav-item">🏠 Accueil</a>
    <a href="/SouqTN/public/tracking" class="nav-item active">🚚 Suivi de livraison</a>
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
      <h1 class="page-title">Suivi de livraison</h1>
      <p class="page-subtitle">État détaillé de chacune de vos commandes.</p>
    </div>
    <div class="badge-date">📦 <?= count($orders) ?> commande(s)</div>
  </div>

  <?php if (empty($orders)): ?>

    <div class="table-card">
      <div class="empty-state">
        <span class="empty-icon">🚚</span>
        <p>Aucune commande à suivre pour le moment.</p>
        <a href="/SouqTN/public/" class="btn-outline" style="margin-top:12px;display:inline-block">
          Passer une commande →
        </a>
      </div>
    </div>

  <?php else: ?>

    <?php foreach ($orders as $o): ?>
      <?php $niv = etapeNiveau($o['statut']); ?>
      <div class="track-card">

        <div class="track-head">
          <div>
            <div class="track-num">Commande #<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></div>
            <div class="track-sub">Passée le <?= fmtDate($o['created_at']) ?></div>
          </div>
          <?php if ($o['statut'] === 'livree'): ?>
            <span class="badge-statut st-livree">✓ Livrée</span>
          <?php else: ?>
            <span class="badge-statut st-encours">⏳ En cours de livraison</span>
          <?php endif; ?>
        </div>

        <!-- Timeline des étapes -->
        <div class="timeline">
          <div class="tl-step <?= $niv >= 1 ? 'done' : '' ?>">
            <div class="tl-dot">🛒</div>
            <div class="tl-label">Commandée</div>
          </div>
          <div class="tl-step <?= $niv >= 2 ? 'done' : '' ?>">
            <div class="tl-dot">📦</div>
            <div class="tl-label">En préparation</div>
          </div>
          <div class="tl-step <?= $niv >= 3 ? 'done' : '' ?>">
            <div class="tl-dot">🚚</div>
            <div class="tl-label">Expédiée</div>
          </div>
          <div class="tl-step <?= $niv >= 4 ? 'done' : '' ?>">
            <div class="tl-dot">✅</div>
            <div class="tl-label">Livrée</div>
          </div>
        </div>

        <!-- Détail des articles -->
        <div class="track-items">
          <h4>Articles de la commande</h4>
          <?php if (!empty($o['items'])): ?>
            <?php foreach ($o['items'] as $it): ?>
            <div class="ti-row">
              <div>
                <span class="ti-name"><?= htmlspecialchars($it['name']) ?></span>
                <span class="ti-qty"> × <?= (int)$it['qty'] ?></span>
              </div>
              <div><?= fmtPrix($it['price'] * $it['qty']) ?></div>
            </div>
            <?php endforeach; ?>
            <div class="track-total">
              <span>Total</span>
              <span><?= fmtPrix($o['total']) ?></span>
            </div>
          <?php else: ?>
            <p style="color:var(--muted);font-size:.86rem">Détail indisponible.</p>
          <?php endif; ?>
        </div>

      </div>
    <?php endforeach; ?>

  <?php endif; ?>

</main>

</body>
</html>
<?php

