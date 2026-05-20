<?php
$activeTab      = $activeTab      ?? 'dashboard';
$stats          = $stats          ?? [];
$recentUsers    = $recentUsers    ?? [];
$recentProducts = $recentProducts ?? [];
$chart          = $chart          ?? ['labels' => [], 'data' => []];
$users          = $users          ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin — SouqTN</title>
  <link rel="stylesheet" href="/SouqTN/public/css/dashboard.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<nav class="sidebar">
  <div class="sidebar-logo">⚡ Souq<span>TN</span></div>

  <div class="nav-section-label">Vue générale</div>
  <a href="/SouqTN/public/dashboard/admin"
     class="nav-item <?= $activeTab === 'dashboard' ? 'active' : '' ?>">
    Dashboard
  </a>

  <div class="nav-section-label">Gestion</div>
  <a href="/SouqTN/public/admin/users"
     class="nav-item <?= $activeTab === 'users' ? 'active' : '' ?>">
    Users <span class="nav-badge"><?= $stats['users'] ?></span>
  </a>
  <a href="/SouqTN/public/admin/products"
   class="nav-item <?= $activeTab === 'products' ? 'active' : '' ?>">
  Produits <span class="nav-badge"><?= $stats['products'] ?></span>
</a>
  <a href="/SouqTN/public/admin/deliveries"
   class="nav-item <?= $activeTab === 'deliveries' ? 'active' : '' ?>">
  Livraisons
</a>

  <div class="sidebar-footer">
    <div style="margin-bottom:10px;font-size:13px;opacity:.8">
      <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?> · <?= date('d M Y') ?>
    </div>
    <a href="/SouqTN/public/logout"
       style="display:flex;align-items:center;gap:8px;justify-content:center;
              padding:10px 14px;border-radius:10px;text-decoration:none;
              background:rgba(200,64,26,.15);color:#C8401A;font-weight:600;
              font-size:14px;transition:.2s"
       onmouseover="this.style.background='#C8401A';this.style.color='#fff'"
       onmouseout="this.style.background='rgba(200,64,26,.15)';this.style.color='#C8401A'">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Déconnexion
    </a>
  </div>
</nav>

<main class="main">

<?php if ($activeTab === 'dashboard'): ?>

  <div class="page-header">
    <div class="page-title">Dashboard</div>
    <div class="page-subtitle">Analyse en temps réel</div>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total Users</div>
      <div class="stat-value"><?= $stats['users'] ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Produits</div>
      <div class="stat-value"><?= $stats['products'] ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Nouveaux (7j)</div>
      <div class="stat-value"><?= $stats['new_users_7d'] ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Commandes aujourd'hui</div>
      <div class="stat-value"><?= $stats['orders_today'] ?></div>
    </div>
  </div>

  <div class="chart-card">
    <div class="card-title">Croissance des utilisateurs</div>
    <div class="chart-wrap">
      <canvas id="chartUsers"></canvas>
    </div>
  </div>

  <div class="table-card">
    <div class="table-card-header">
      <div class="card-title">Derniers utilisateurs</div>
    </div>
    <table>
      <thead><tr><th>Nom</th><th>Email</th><th>Rôle</th></tr></thead>
      <tbody>
        <?php foreach ($recentUsers as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= $u['role'] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="table-card">
    <div class="table-card-header">
      <div class="card-title">Derniers produits</div>
    </div>
    <table>
      <tbody>
        <?php foreach ($recentProducts as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td><?= number_format($p['price'], 3, ',', ' ') ?> TND</td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <script>
  new Chart(document.getElementById('chartUsers'), {
    type: 'line',
    data: {
      labels: <?= json_encode($chart['labels']) ?>,
      datasets: [{
        data: <?= json_encode($chart['data']) ?>,
        borderColor: '#C8401A',
        backgroundColor: 'rgba(200,64,26,0.08)',
        fill: true, tension: 0.4
      }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { color: '#64748b' } },
        y: { ticks: { color: '#64748b' } }
      }
    }
  });
  </script>

<?php elseif ($activeTab === 'users'): ?>

  <div class="page-header">
    <div class="page-title">Gestion des utilisateurs</div>
  </div>

  <?php require __DIR__ . '/users_crud.php'; ?>

<?php elseif ($activeTab === 'products'): ?>

  <div class="page-header">
    <div class="page-title">Gestion des produits</div>
  </div>

  <?php require __DIR__ . '/products_crud.php'; ?>

<?php elseif ($activeTab === 'deliveries'): ?>

  <div class="page-header">
    <div class="page-title">Gestion des livraisons</div>
  </div>

  <?php require __DIR__ . '/deliveries.php'; ?>

<?php endif; ?>

</main>
</body>
</html>

