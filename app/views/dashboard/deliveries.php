<?php
$allOrders     = $allOrders     ?? [];
$deliveryStats = $deliveryStats ?? ['total_cmd'=>0,'en_cours'=>0,'livrees'=>0,'chiffre_affaires'=>0];
function dlvPrix($n){ return number_format((float)$n, 3, ',', ' ') . ' TND'; }
function dlvDate($d){ return $d ? date('d/m/Y H:i', strtotime($d)) : '—'; }
?>
<style>
  .dlv-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:26px}
  .dlv-stat{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:18px 20px}
  .dlv-stat .lbl{font-size:.72rem;color:var(--text);opacity:.55;text-transform:uppercase;letter-spacing:.05em}
  .dlv-stat .val{font-size:1.7rem;font-weight:700;color:var(--text);margin-top:6px}
  .dlv-stat.s1{border-top:3px solid #C8401A}
  .dlv-stat.s2{border-top:3px solid #E8A020}
  .dlv-stat.s3{border-top:3px solid #1D9E75}
  .dlv-stat.s4{border-top:3px solid #6c7086}

  .dlv-card{background:var(--card);border:1px solid var(--border);border-radius:16px;
    padding:20px;box-shadow:0 10px 25px rgba(0,0,0,.06)}
  table{width:100%;border-collapse:collapse;margin-top:8px}
  th{text-align:left;padding:10px 12px;font-size:.74rem;color:var(--text);opacity:.5;
    text-transform:uppercase;letter-spacing:.04em}
  td{padding:13px 12px;border-bottom:1px solid var(--border);font-size:.88rem;color:var(--text)}
  tr:hover td{background:rgba(0,0,0,.02)}

  .bdg{display:inline-block;padding:4px 12px;border-radius:20px;font-size:.74rem;font-weight:600}
  .b-enc{background:rgba(232,160,32,.16);color:#B26A00}
  .b-liv{background:rgba(29,158,117,.16);color:#1D9E75}

  .dlv-btn{border:none;padding:7px 13px;border-radius:8px;font-size:.78rem;
    font-weight:600;cursor:pointer;transition:.18s}
  .btn-liv{background:#1D9E75;color:#fff}
  .btn-enc{background:rgba(232,160,32,.18);color:#B26A00}
  .dlv-btn:hover{filter:brightness(1.07)}
  .dlv-btn:disabled{opacity:.5;cursor:default}
</style>

<div class="dlv-stats">
  <div class="dlv-stat s1">
    <div class="lbl">Commandes totales</div>
    <div class="val"><?= (int)$deliveryStats['total_cmd'] ?></div>
  </div>
  <div class="dlv-stat s2">
    <div class="lbl">En cours</div>
    <div class="val"><?= (int)$deliveryStats['en_cours'] ?></div>
  </div>
  <div class="dlv-stat s3">
    <div class="lbl">Livrées</div>
    <div class="val"><?= (int)$deliveryStats['livrees'] ?></div>
  </div>
  <div class="dlv-stat s4">
    <div class="lbl">Chiffre d'affaires</div>
    <div class="val" style="font-size:1.2rem"><?= dlvPrix($deliveryStats['chiffre_affaires']) ?></div>
  </div>
</div>

<div class="dlv-card">
  <?php if (empty($allOrders)): ?>
    <p style="text-align:center;color:var(--text);opacity:.6;padding:40px 0">
      Aucune commande pour le moment.
    </p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>N°</th><th>Client</th><th>Date</th><th>Articles</th>
          <th>Total</th><th>Statut</th><th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($allOrders as $o): ?>
        <tr id="ordrow<?= (int)$o['id'] ?>">
          <td><strong>#<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></strong></td>
          <td>
            <?= htmlspecialchars($o['username']) ?><br>
            <span style="font-size:.76rem;opacity:.6"><?= htmlspecialchars($o['email']) ?></span>
          </td>
          <td><?= dlvDate($o['created_at']) ?></td>
          <td><?= (int)$o['nb_unites'] ?> art.</td>
          <td><?= dlvPrix($o['total']) ?></td>
          <td class="statcell">
            <?php if ($o['statut'] === 'livree'): ?>
              <span class="bdg b-liv">Livrée</span>
            <?php else: ?>
              <span class="bdg b-enc">En cours</span>
            <?php endif; ?>
          </td>
          <td class="actcell">
            <?php if ($o['statut'] === 'livree'): ?>
              <button class="dlv-btn btn-enc"
                onclick="changeStatut(<?= (int)$o['id'] ?>, 'en_cours')">
                ↩ Repasser en cours
              </button>
            <?php else: ?>
              <button class="dlv-btn btn-liv"
                onclick="changeStatut(<?= (int)$o['id'] ?>, 'livree')">
                ✓ Marquer livrée
              </button>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<script>
async function changeStatut(id, statut) {
  const row  = document.getElementById('ordrow' + id);
  const btn  = row.querySelector('.dlv-btn');
  btn.disabled = true;

  try {
    const fd = new FormData();
    fd.append('id', id);
    fd.append('statut', statut);
    const r = await fetch('/SouqTN/public/admin/deliveries/status',
                          { method:'POST', body:fd });
    const data = await r.json();

    if (data.status === 'success') {
      const statCell = row.querySelector('.statcell');
      const actCell  = row.querySelector('.actcell');
      if (statut === 'livree') {
        statCell.innerHTML = '<span class="bdg b-liv">Livrée</span>';
        actCell.innerHTML  =
          '<button class="dlv-btn btn-enc" onclick="changeStatut(' + id +
          ", 'en_cours')\">↩ Repasser en cours</button>";
      } else {
        statCell.innerHTML = '<span class="bdg b-enc">En cours</span>';
        actCell.innerHTML  =
          '<button class="dlv-btn btn-liv" onclick="changeStatut(' + id +
          ", 'livree')\">✓ Marquer livrée</button>";
      }
    } else {
      alert(data.message || 'Erreur');
      btn.disabled = false;
    }
  } catch (e) {
    alert('Erreur réseau');
    btn.disabled = false;
  }
}
</script>

