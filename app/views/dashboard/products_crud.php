<style>
.page {
  width: 100%;
  display: grid;
  grid-template-columns: 360px 1fr;
  gap: 20px;
}
.card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.25);
}
.card h2 {
  font-family: 'Syne', sans-serif;
  font-size: 1.1rem;
  margin-bottom: 12px;
}
.card input, .card select, .card textarea {
  width: 100%;
  padding: 10px;
  margin: 8px 0;
  border-radius: 10px;
  border: 1px solid var(--border);
  background: var(--surface);
  color: var(--text);
  font-size: 0.9rem;
  box-sizing: border-box;
  font-family: inherit;
}
.card textarea { resize: vertical; min-height: 80px; }
.card input:focus, .card select:focus, .card textarea:focus {
  border-color: var(--accent); outline: none;
}
.btn {
  width: 100%; padding: 10px; border: none;
  border-radius: 10px; background: var(--accent);
  color: white; font-weight: 600; cursor: pointer;
  transition: 0.2s; margin-top: 8px;
}
.btn:hover { background: #9A3010; }
.crud-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
.crud-table td { padding: 12px; border-bottom: 1px solid var(--border); font-size: 0.85rem; }
.crud-table tr:hover td { background: rgba(255,255,255,0.03); }
.btn-edit {
  background: rgba(200,64,26,0.15); color: #C8401A;
  border: none; padding: 6px 10px; border-radius: 8px;
  cursor: pointer; margin-right: 6px;
}
.btn-delete {
  background: rgba(239,68,68,0.15); color: #f87171;
  border: none; padding: 6px 10px; border-radius: 8px; cursor: pointer;
}
.modal-overlay {
  position: fixed; top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.6);
  display: flex; justify-content: center; align-items: center;
  opacity: 0; pointer-events: none; transition: 0.25s; z-index: 200;
}
.modal-overlay.show { opacity: 1; pointer-events: auto; }
.modal-box {
  background: var(--card); border: 1px solid var(--border);
  padding: 24px; border-radius: 16px; width: 380px;
}
.modal-box h3 { font-family: 'Syne', sans-serif; margin: 0 0 16px; }
.modal-box input, .modal-box select, .modal-box textarea {
  width: 100%; padding: 10px; margin: 6px 0;
  border-radius: 10px; border: 1px solid var(--border);
  background: var(--surface); color: var(--text);
  font-size: 0.9rem; box-sizing: border-box; font-family: inherit;
}
.modal-box textarea { resize: vertical; min-height: 70px; }
.btn-close {
  width: 100%; padding: 9px; margin-top: 8px;
  border: 1px solid var(--border); border-radius: 10px;
  background: transparent; color: var(--text); cursor: pointer; font-size: 0.9rem;
}
@media(max-width: 900px) { .page { grid-template-columns: 1fr; } }
</style>

<div class="page">

  <!-- Formulaire créer -->
  <div class="card">
    <h2>Nouveau produit</h2>

    <input  id="e_name"        placeholder="Nom du produit">
    <textarea id="e_description" placeholder="Description"></textarea>
    <select id="e_category">
      <option value="artisanat">Artisanat</option>
      <option value="gastronomie">Gastronomie</option>
      <option value="bijoux">Bijoux</option>
      <option value="textile">Textile</option>
      <option value="beaute">Beauté</option>
      <option value="maison">Maison</option>
    </select>
    <input  id="e_price"       placeholder="Prix TND (ex: 85)" type="number" min="0" step="0.001">
    <input  id="e_orig"        placeholder="Prix barré (optionnel)" type="number" min="0" step="0.001">
    <input  id="e_stock"       placeholder="Stock (ex: 15)" type="number" min="0">
    <input  id="e_image"       placeholder="URL de l'image (optionnel)">

    <button class="btn" onclick="ProductCRUD.save()">Enregistrer</button>
  </div>

  <!-- Tableau -->
  <div class="card">
    <h2>Produits</h2>

    <table class="crud-table">
      <thead>
        <tr>
          <th style="text-align:left;padding:8px 12px;font-size:.75rem;color:var(--text);opacity:.5">Nom</th>
          <th style="text-align:left;padding:8px 12px;font-size:.75rem;color:var(--text);opacity:.5">Prix</th>
          <th style="text-align:left;padding:8px 12px;font-size:.75rem;color:var(--text);opacity:.5">Stock</th>
          <th style="text-align:left;padding:8px 12px;font-size:.75rem;color:var(--text);opacity:.5">Actions</th>
        </tr>
      </thead>
      <tbody id="productBody">
        <?php foreach ($products ?? [] as $e): ?>
        <tr id="prow<?= $e['id'] ?>">
          <td><strong><?= htmlspecialchars($e['name']) ?></strong></td>
          <td style="color:#9ca3af;font-size:.8rem"><?= number_format($e['price'], 3, ',', ' ') ?> TND</td>
          <td style="font-size:.8rem">
            <?= $e['stock'] ?>
            <?php if ($e['stock'] <= 0): ?>
              <span style="color:#f87171;font-size:.75rem"> · Rupture</span>
            <?php endif; ?>
          </td>
          <td>
            <button class="btn-edit"
              onclick="ProductCRUD.openModal(
                <?= $e['id'] ?>,
                '<?= addslashes($e['name']) ?>',
                '<?= addslashes($e['description']) ?>',
                '<?= addslashes($e['category']) ?>',
                <?= $e['price'] ?>,
                <?= $e['orig_price'] ?? 0 ?>,
                <?= $e['stock'] ?>,
                '<?= addslashes($e['image_url'] ?? '') ?>'
              )">✏️</button>
            <button class="btn-delete" onclick="ProductCRUD.del(<?= $e['id'] ?>, '<?= addslashes($e['name']) ?>')">
              🗑️
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>

<!-- Modal modifier -->
<div class="modal-overlay" id="productModal">
  <div class="modal-box">
    <h3>Modifier produit</h3>

    <input type="hidden" id="m_pid">
    <input    id="m_name"        placeholder="Nom">
    <textarea id="m_description" placeholder="Description"></textarea>
    <select   id="m_category">
      <option value="artisanat">Artisanat</option>
      <option value="gastronomie">Gastronomie</option>
      <option value="bijoux">Bijoux</option>
      <option value="textile">Textile</option>
      <option value="beaute">Beauté</option>
      <option value="maison">Maison</option>
    </select>
    <input    id="m_price"       type="number" min="0" step="0.001" placeholder="Prix TND">
    <input    id="m_orig"        type="number" min="0" step="0.001" placeholder="Prix barré (optionnel)">
    <input    id="m_stock"       type="number" min="0" placeholder="Stock">
    <input    id="m_image"       placeholder="URL image (optionnel)">

    <button class="btn"       onclick="ProductCRUD.update()">Enregistrer</button>
    <button class="btn-close" onclick="ProductCRUD.closeModal()">Fermer</button>
  </div>
</div>

<script>
const ProductCRUD = (() => {
  const BASE = '/SouqTN/public';

  async function post(url, data) {
    const fd = new FormData();
    Object.entries(data).forEach(([k, v]) => fd.append(k, v));
    const res = await fetch(BASE + url, { method: 'POST', body: fd });
    return res.json();
  }

  function fmtPrice(n) {
    return Number(n).toLocaleString('fr-FR', { minimumFractionDigits: 3, maximumFractionDigits: 3 }) + ' TND';
  }

  // ── Créer ────────────────────────────────────────────────────
  async function save() {
    const data = await post('/admin/products/save', {
      name:        document.getElementById('e_name').value.trim(),
      description: document.getElementById('e_description').value.trim(),
      category:    document.getElementById('e_category').value,
      price:       document.getElementById('e_price').value,
      orig_price:  document.getElementById('e_orig').value,
      stock:       document.getElementById('e_stock').value,
      image_url:   document.getElementById('e_image').value.trim(),
    });

    if (data.status !== 'success') { alert(data.message); return; }
    alert(data.message);

    const e  = data.product;
    const tr = document.createElement('tr');
    tr.id = 'prow' + e.id;
    tr.innerHTML = `
      <td><strong>${e.name}</strong></td>
      <td style="color:#9ca3af;font-size:.8rem">${fmtPrice(e.price)}</td>
      <td style="font-size:.8rem">${e.stock}</td>
      <td>
        <button class="btn-edit"
          onclick="ProductCRUD.openModal(${e.id},'${e.name}','${e.description}','${e.category}',${e.price},${e.orig_price || 0},${e.stock},'${e.image_url}')">
          ✏️
        </button>
        <button class="btn-delete" onclick="ProductCRUD.del(${e.id},'${e.name}')">🗑️</button>
      </td>`;
    document.getElementById('productBody').prepend(tr);

    document.getElementById('e_name').value        = '';
    document.getElementById('e_description').value = '';
    document.getElementById('e_category').value    = 'artisanat';
    document.getElementById('e_price').value       = '';
    document.getElementById('e_orig').value        = '';
    document.getElementById('e_stock').value       = '';
    document.getElementById('e_image').value       = '';
  }

  // ── Ouvrir modal ─────────────────────────────────────────────
  function openModal(id, name, description, category, price, orig, stock, image) {
    document.getElementById('m_pid').value         = id;
    document.getElementById('m_name').value        = name;
    document.getElementById('m_description').value = description;
    document.getElementById('m_category').value    = category;
    document.getElementById('m_price').value       = price;
    document.getElementById('m_orig').value        = orig || '';
    document.getElementById('m_stock').value       = stock;
    document.getElementById('m_image').value       = image;
    document.getElementById('productModal').classList.add('show');
  }

  function closeModal() {
    document.getElementById('productModal').classList.remove('show');
  }

  // ── Modifier ─────────────────────────────────────────────────
  async function update() {
    const id = document.getElementById('m_pid').value;
    const data = await post('/admin/products/save', {
      id,
      name:        document.getElementById('m_name').value.trim(),
      description: document.getElementById('m_description').value.trim(),
      category:    document.getElementById('m_category').value,
      price:       document.getElementById('m_price').value,
      orig_price:  document.getElementById('m_orig').value,
      stock:       document.getElementById('m_stock').value,
      image_url:   document.getElementById('m_image').value.trim(),
    });

    if (data.status !== 'success') { alert(data.message); return; }
    alert(data.message);
    closeModal();

    const row = document.getElementById('prow' + id);
    if (row) {
      row.cells[0].innerHTML = '<strong>' + document.getElementById('m_name').value + '</strong>';
      row.cells[1].textContent = fmtPrice(document.getElementById('m_price').value);
      row.cells[2].textContent = document.getElementById('m_stock').value;
    }
  }

  // ── Supprimer ────────────────────────────────────────────────
  async function del(id, name) {
    if (!confirm('Supprimer « ' + name + ' » ?')) return;

    const data = await post('/admin/products/delete', { id });

    if (data.status !== 'success') { alert(data.message); return; }
    document.getElementById('prow' + id)?.remove();
  }

  return { save, openModal, closeModal, update, del };
})();
</script>

