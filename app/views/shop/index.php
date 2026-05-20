<?php
$products      = $products      ?? [];
$userCart      = $userCart      ?? [];
$totalProducts = $totalProducts ?? count($products);
$totalStock    = $totalStock    ?? array_sum(array_column($products, 'stock'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>SouqTN — Artisanat & Produits Tunisiens Authentiques</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Amiri:wght@400;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/SouqTN/public/css/style1.css">
</head>
<body>

<!-- ── TOPBAR ── -->
<div class="topbar">
  <span>🚚 Livraison gratuite dès <b>150 TND</b></span>
  <span class="topbar-sep">|</span>
  <span>⭐ Artisans tunisiens vérifiés</span>
  <span class="topbar-sep">|</span>
  <span>📦 Retours faciles sous 30 jours</span>
</div>

<!-- ── HEADER ── -->
<header class="header">
  <div class="header-inner">
    <div class="logo">
      <div class="logo-box">سوق</div>
      <div class="logo-text"><strong>SouqTN</strong><span>سوق تونس الأصيل</span></div>
    </div>
    <div class="searchbar">
      <select class="search-cat">
        <option>Toutes catégories</option>
        <option>Artisanat</option><option>Gastronomie</option><option>Bijoux</option>
        <option>Textile</option><option>Beauté</option><option>Maison</option>
      </select>
      <input class="search-inp" id="headerSearchInput" placeholder="Rechercher produits, artisans, régions…" autocomplete="off"/>
      <button class="search-go" id="openSearchBtn">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </button>
    </div>
    <div class="header-actions">
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/SouqTN/public/dashboard/client" class="hdr-btn" title="Mon profil">
          <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span><?= htmlspecialchars($_SESSION['username'] ?? 'Profil') ?></span>
        </a>
        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
        <a href="/SouqTN/public/dashboard/admin" class="hdr-btn" title="Espace administrateur" style="color:var(--gold)">
          <svg viewBox="0 0 24 24"><path d="M12 2l7 4v6c0 5-3.5 8-7 10-3.5-2-7-5-7-10V6z"/></svg>
          <span>Admin</span>
        </a>
        <?php endif; ?>
        <a href="/SouqTN/public/logout" class="hdr-btn" title="Se déconnecter">
          <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          <span>Déconnexion</span>
        </a>
      <?php else: ?>
        <a href="/SouqTN/public/login" class="hdr-btn">
          <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span>Compte</span>
        </a>
      <?php endif; ?>
      <button class="hdr-btn" id="wishOpenBtn">
        <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        <span>Favoris</span>
      </button>
      <div class="hdr-cart-wrap">
        <button class="hdr-btn" id="cartOpenBtn">
          <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
          <span>Panier</span>
        </button>
        <div class="cart-dot" id="cartDotBadge">0</div>
      </div>
    </div>
  </div>
</header>

<!-- ── NAVBAR ── -->
<nav class="navbar">
  <div class="navbar-inner">
    <button class="nav-all">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      Toutes les catégories
    </button>
    <div class="nav-links">
      <a class="nav-link hot">🔥 Offres du Jour</a>
      <a class="nav-link" data-cat="artisanat">Artisanat</a>
      <a class="nav-link" data-cat="gastronomie">Gastronomie</a>
      <a class="nav-link" data-cat="bijoux">Bijoux</a>
      <a class="nav-link" data-cat="textile">Textile</a>
      <a class="nav-link" data-cat="beaute">Beauté & Soin</a>
      <a class="nav-link new-lnk">Nouveautés</a>
    </div>
    <div class="nav-right">🇹🇳 100% Artisans Tunisiens</div>
  </div>
</nav>

<!-- ── HERO ── -->
<div class="hero-zone">
  <div class="hero-main">
    <div class="hero-deco">🏺</div>
    <div class="hero-c">
      <div class="hero-pill">✦ Artisanat Authentique 2025</div>
      <h1 class="hero-h1">Trésors <em>Tunisiens</em><br/>Livrés Chez Vous</h1>
      <p class="hero-sub">Poteries de Nabeul, tapis de Kairouan, bijoux berbères, huiles d'olive primées — directement des mains des artisans.</p>
      <div class="hero-btns">
        <button class="btn-hero btn-hero-solid" onclick="document.getElementById('shopZone').scrollIntoView({behavior:'smooth'})">🛍 Explorer les produits</button>
        <button class="btn-hero btn-hero-ghost">En savoir plus</button>
      </div>
      <div class="hero-stats">
        <div class="hstat"><span class="hstat-n" data-count="2400">0</span><span class="hstat-l">Produits</span></div>
        <div class="hstat"><span class="hstat-n" data-count="380">0</span><span class="hstat-l">Artisans</span></div>
        <div class="hstat"><span class="hstat-n" data-count="24">0</span><span class="hstat-l">Gouvernorats</span></div>
        <div class="hstat"><span class="hstat-n" data-count="98">0</span><span class="hstat-l">% Satisfaction</span></div>
      </div>
    </div>
  </div>
  <div class="hero-side">
    <div class="side-banner side-b1"><div class="side-tag">Gastronomie</div><div class="side-title">Huile d'Olive<br/>Extra Vierge Bio</div><div class="side-sub">Médaille d'Or NYIOOC 2024</div><a class="side-link">Commander →</a></div>
    <div class="side-banner side-b2"><div class="side-tag">Bijoux</div><div class="side-title">Filigrane d'Argent<br/>de la Médina</div><div class="side-sub">Jusqu'à -20% cette semaine</div><a class="side-link">Voir les bijoux →</a></div>
  </div>
</div>

<!-- ── TRUST STRIP ── -->
<div class="trust">
  <div class="trust-inner">
    <div class="trust-item"><span class="trust-ico">🚚</span><div><strong>Livraison Gratuite</strong><span>Dès 150 TND — 24 gouvernorats</span></div></div>
    <div class="trust-item"><span class="trust-ico">🔒</span><div><strong>Paiement Sécurisé</strong><span>D17, CB, Virement, Cash</span></div></div>
    <div class="trust-item"><span class="trust-ico">↩️</span><div><strong>Retours 30 jours</strong><span>Sans conditions</span></div></div>
    <div class="trust-item"><span class="trust-ico">⭐</span><div><strong>Artisans Vérifiés</strong><span>380+ créateurs certifiés</span></div></div>
    <div class="trust-item"><span class="trust-ico">📞</span><div><strong>Support 7j/7</strong><span>Chat, email, téléphone</span></div></div>
  </div>
</div>

<!-- ── SHOP ZONE ── -->
<div class="shop-zone" id="shopZone">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sb-block">
      <div class="sb-head"><span>Catégories</span><a>Tout voir</a></div>
      <div class="sb-body"><div id="catList"></div></div>
    </div>
    <div class="sb-block">
      <div class="sb-head">Prix (TND)</div>
      <div class="sb-body">
        <div class="price-inputs">
          <input type="number" class="price-inp" id="priceMin" placeholder="Min" min="0"/>
          <span class="price-sep">—</span>
          <input type="number" class="price-inp" id="priceMax" placeholder="Max"/>
        </div>
        <div class="presets-row">
          <button class="preset-btn" onclick="setPrice(0,50,this)">< 50</button>
          <button class="preset-btn" onclick="setPrice(50,150,this)">50–150</button>
          <button class="preset-btn" onclick="setPrice(150,500,this)">150–500</button>
          <button class="preset-btn" onclick="setPrice(500,Infinity,this)">500+</button>
        </div>
      </div>
    </div>
    <div class="sb-block">
      <div class="sb-head">Région artisan</div>
      <div class="sb-body" id="regionList"></div>
    </div>
    <div class="sb-block">
      <div class="sb-head">Note minimale</div>
      <div class="sb-body">
        <div class="rating-item" onclick="setRating(4.5)"><span class="stars-gold">★★★★★</span><span style="font-size:12px;color:var(--ink2)">4.5+</span><span class="rating-count">(48)</span></div>
        <div class="rating-item" onclick="setRating(4)"><span class="stars-gold">★★★★☆</span><span style="font-size:12px;color:var(--ink2)">4.0+</span><span class="rating-count">(87)</span></div>
        <div class="rating-item" onclick="setRating(0)"><span class="stars-gold">★★★☆☆</span><span style="font-size:12px;color:var(--ink2)">3.0+</span><span class="rating-count">(110)</span></div>
      </div>
    </div>
    <div class="sb-block">
      <div class="sb-head">Labels</div>
      <div class="sb-body" style="display:flex;flex-direction:column;gap:7px">
        <label class="label-check"><input type="checkbox" class="badge-filter" value="bio"/> 🌿 Bio certifié</label>
        <label class="label-check"><input type="checkbox" class="badge-filter" value="bestseller"/> 🔥 Meilleures ventes</label>
        <label class="label-check"><input type="checkbox" class="badge-filter" value="new"/> ✨ Nouveautés</label>
        <label class="label-check"><input type="checkbox" class="badge-filter" value="promo"/> 💰 En promotion</label>
      </div>
    </div>
  </aside>

  <!-- MAIN PRODUCTS -->
  <main>
    <div class="breadcrumb">
      <a onclick="filterByCategory('all')">Accueil</a>
      <span class="breadcrumb-sep">›</span>
      <a>Boutique</a>
      <span class="breadcrumb-sep">›</span>
      <span id="breadcrumbLabel">Tous les produits</span>
    </div>
    <div class="active-filters" id="activeFilters"></div>
    <div class="toolbar">
      <span class="results-count" id="resultsCount"><b>12</b> produits trouvés</span>
      <div class="toolbar-sep"></div>
      <span class="sort-label">Trier par:</span>
      <select class="sort-select" id="sortSelect">
        <option value="default">Recommandés</option>
        <option value="price-asc">Prix croissant</option>
        <option value="price-desc">Prix décroissant</option>
        <option value="rating">Mieux notés</option>
        <option value="reviews">Plus d'avis</option>
      </select>
      <div class="toolbar-sep"></div>
      <div class="view-btns">
        <button class="view-btn active" id="gridViewBtn" onclick="setView('grid')" title="Grille">
          <svg viewBox="0 0 16 16"><path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm8 0A1.5 1.5 0 0 1 10.5 9h3A1.5 1.5 0 0 1 15 10.5v3A1.5 1.5 0 0 1 13.5 15h-3A1.5 1.5 0 0 1 9 13.5z"/></svg>
        </button>
        <button class="view-btn" id="listViewBtn" onclick="setView('list')" title="Liste">
          <svg viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/></svg>
        </button>
      </div>
    </div>
    <div class="products-grid" id="productsGrid"></div>
    <div class="pagination">
      <button class="page-btn">← Préc.</button>
      <button class="page-btn active">1</button>
      <button class="page-btn">2</button>
      <button class="page-btn">3</button>
      <button class="page-btn">Suiv. →</button>
    </div>
  </main>
</div>

<!-- ── PROMO STRIP ── -->
<div class="promo-zone">
  <div class="promo-card prom-1"><div class="prom-ey">Offre Flash</div><div class="prom-tt">Harissa &amp; Épices de Nabeul</div><div class="prom-sub">Jusqu'à -30% sur la gastronomie</div><button class="prom-btn">Voir les offres →</button></div>
  <div class="promo-card prom-2"><div class="prom-ey">Artisan Vedette</div><div class="prom-tt">Huile Chetoui Médaille d'Or</div><div class="prom-sub">Habib Mansouri · Sfax</div><button class="prom-btn">Commander →</button></div>
  <div class="promo-card prom-3"><div class="prom-ey">Collection</div><div class="prom-tt">Tapis Berbères de Kairouan</div><div class="prom-sub">Tissés à la main — Certifiés OAT</div><button class="prom-btn">Découvrir →</button></div>
</div>

<!-- ── ARTISANS ── -->
<div class="section-zone">
  <div class="section-head">
    <div class="section-label"><div class="section-bar"></div><h2>Nos Artisans</h2></div>
    <a class="see-all-link">Voir tous les artisans →</a>
  </div>
  <div class="artisans-grid" id="artisansGrid"></div>
</div>

<!-- ── RECOMMENDED ── -->
<div class="section-zone">
  <div class="section-head">
    <div class="section-label"><div class="section-bar"></div><h2>Sélection du Moment</h2></div>
    <a class="see-all-link">Voir tout →</a>
  </div>
  <div class="reco-grid" id="recoGrid"></div>
</div>

<!-- ── FOOTER ── -->
<footer class="footer">
  <div class="footer-top">
    <div>
      <div class="footer-logo-box">سوق</div>
      <div class="footer-brand-name">SouqTN</div>
      <p class="footer-brand-desc">La première marketplace tunisienne dédiée à l'artisanat authentique et aux produits du terroir. Livraison dans toute la Tunisie et à l'international.</p>
      <div class="footer-socials">
        <div class="footer-social">fb</div><div class="footer-social">ig</div>
        <div class="footer-social">yt</div><div class="footer-social">tg</div>
      </div>
    </div>
    <div>
      <div class="footer-col-title">Boutique</div>
      <div class="footer-links"><a>Tous les produits</a><a>Artisanat</a><a>Gastronomie</a><a>Bijoux</a><a>Promotions</a></div>
    </div>
    <div>
      <div class="footer-col-title">Aide</div>
      <div class="footer-links"><a>Comment commander</a><a>Livraison & délais</a><a>Retours</a><a>FAQ</a><a>Contact</a></div>
    </div>
    <div>
      <div class="footer-col-title">Artisans</div>
      <div class="footer-links"><a>Devenir vendeur</a><a>Nos artisans</a><a>Label qualité</a><a>Partenaires</a></div>
    </div>
    <div>
      <div class="footer-col-title">Newsletter</div>
      <p class="footer-news-desc">Offres exclusives et nouveaux artisans directement dans votre boîte mail.</p>
      <div class="footer-news-form">
        <input class="footer-news-input" type="email" placeholder="votre@email.com"/>
        <button class="footer-news-btn">S'inscrire</button>
      </div>
      <div class="payment-icons">
        <span class="pay-icon">💳 Carte</span><span class="pay-icon">📱 D17</span>
        <span class="pay-icon">🏦 Virement</span><span class="pay-icon">💵 Cash</span>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© 2025 SouqTN — Tous droits réservés</span>
    <span class="footer-flag">🇹🇳 Fait en Tunisie · شُنُق تُونُسِيَّة</span>
    <div><a>Mentions légales</a><a>Confidentialité</a><a>CGV</a></div>
  </div>
</footer>

<!-- ── CART DRAWER ── -->
<div class="overlay-bg" id="drawerOverlay"></div>
<div class="cart-drawer" id="cartDrawer">
  <div class="cart-drawer-head">
    <h3>🛒 Mon Panier (<span id="cartItemCount">0</span>)</h3>
    <button class="cart-close" id="cartCloseBtn">✕</button>
  </div>
  <div class="cart-body" id="cartBody">
    <div class="cart-empty-state">
      <div class="empty-emoji">🛒</div>
      <p>Votre panier est vide</p>
      <p style="font-size:12px;color:var(--muted)">Découvrez nos produits artisanaux</p>
    </div>
  </div>
  <div id="cartFooterZone" style="display:none">
    <div class="cart-footer-zone">
      <div class="cart-line"><span>Sous-total</span><span id="cartSubtotal">0,000 TND</span></div>
      <div class="cart-line"><span>Livraison</span><span id="cartShipping">7,000 TND</span></div>
      <div class="free-ship-badge" id="freeShipMsg" style="display:none">✅ Livraison gratuite appliquée !</div>
      <div class="cart-line total"><span>Total</span><span id="cartTotal">0,000 TND</span></div>
      <button class="checkout-btn" id="checkoutBtn">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
        Passer la commande
      </button>
    </div>
  </div>
</div>

<!-- ── WISHLIST DRAWER ── -->
<div class="cart-drawer" id="wishDrawer">
  <div class="cart-drawer-head">
    <h3>♥ Mes Favoris (<span id="wishItemCount">0</span>)</h3>
    <button class="cart-close" id="wishCloseBtn">✕</button>
  </div>
  <div class="cart-body" id="wishBody">
    <div class="cart-empty-state">
      <div class="empty-emoji">♡</div>
      <p>Aucun favori pour le moment</p>
      <p style="font-size:12px;color:var(--muted)">Cliquez sur le cœur d'un produit</p>
    </div>
  </div>
</div>

<!-- ── SEARCH OVERLAY ── -->
<div class="search-overlay" id="searchOverlay">
  <div class="search-panel">
    <div class="search-panel-top">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input class="search-panel-input" id="searchPanelInput" placeholder="Rechercher produits, artisans…" autocomplete="off"/>
      <button class="search-panel-close" id="searchPanelCloseBtn">✕</button>
    </div>
    <div class="search-results-list" id="searchResultsList">
      <div class="search-empty">Tapez pour rechercher…</div>
    </div>
  </div>
</div>

<!-- ── PRODUCT MODAL ── -->
<div class="modal-overlay" id="productModal">
  <div class="modal-box">
    <button class="modal-close-btn" id="modalCloseBtn">✕</button>
    <div id="modalContent"></div>
  </div>
</div>

<!-- ── TOAST ── -->
<div class="toast-container" id="toastContainer"></div>

<script>
/* ═══════════════════════════════════════════
   DATA
═══════════════════════════════════════════ */
const CATS = [
  {slug:'all',name:'Tous les produits',icon:'🛍',count:12},
  {slug:'artisanat',name:'Artisanat',icon:'🏺',count:3},
  {slug:'gastronomie',name:'Gastronomie',icon:'🫒',count:3},
  {slug:'bijoux',name:'Bijoux',icon:'💍',count:2},
  {slug:'textile',name:'Textile',icon:'🧶',count:2},
  {slug:'beaute',name:'Beauté',icon:'🌹',count:2},
  {slug:'maison',name:'Maison',icon:'🕌',count:0},
];

const ARTISANS = [
  {id:1,name:'Fatma Ben Salah',region:'Nabeul',spec:'Poterie',em:'🏺',prods:48,rating:4.9,sales:1250},
  {id:2,name:'Mohamed Trabelsi',region:'Kairouan',spec:'Tapis & Kilims',em:'🧶',prods:32,rating:4.8,sales:890},
  {id:3,name:'Amira Chokri',region:'Tunis Médina',spec:'Bijoux Filigrane',em:'💍',prods:65,rating:4.95,sales:2100},
  {id:4,name:'Habib Mansouri',region:'Sfax',spec:"Huile d'Olive",em:'🫒',prods:18,rating:4.7,sales:3400},
  {id:5,name:'Sonia Baccar',region:'Djerba',spec:'Savonnerie',em:'🌿',prods:27,rating:4.85,sales:680},
  {id:6,name:'Rafik Jlassi',region:'Sidi Bou Saïd',spec:'Zellige & Mosaïque',em:'🕌',prods:22,rating:4.9,sales:420},
];

<?php
/* Génère le catalogue JS à partir des produits réels en base de données.
   Les champs non stockés (emoji, matière, dimensions, poids) reçoivent
   une valeur par défaut déduite de la catégorie. */
$emojiByCat = [
  'artisanat'   => "\xF0\x9F\x8F\xBA", 'textile'  => "\xF0\x9F\xA7\xB6", 'bijoux' => "\xF0\x9F\x92\x8D",
  'gastronomie' => "\xF0\x9F\xAB\x92", 'beaute'   => "\xF0\x9F\xA7\xBC", 'maison' => "\xF0\x9F\x95\x8C",
];
$jsProducts = array_map(function($p) use ($emojiByCat) {
  $cat = strtolower($p['category'] ?? '');
  return [
    'id'      => (int)$p['id'],
    'name'    => $p['name'],
    'cat'     => $cat,
    'artisan' => $p['artisan']  ?? 'Artisan SouqTN',
    'region'  => $p['region']   ?? 'Tunisie',
    'price'   => (float)$p['price'],
    'orig'    => $p['orig_price'] !== null ? (float)$p['orig_price'] : null,
    'em'      => $emojiByCat[$cat] ?? "\xF0\x9F\x9B\x8D\xEF\xB8\x8F",
    'rating'  => (float)($p['rating']  ?? 0),
    'reviews' => (int)($p['reviews']   ?? 0),
    'stock'   => (int)$p['stock'],
    'badge'   => $p['badge'] ?: null,
    'desc'    => $p['description'] ?? '',
    'mat'     => 'Artisanat tunisien',
    'dim'     => "\xE2\x80\x94",
    'wt'      => "\xE2\x80\x94",
  ];
}, $products);
?>
const PRODUCTS = <?= json_encode($jsProducts, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

/* ═══════════════════════════════════════════
   STATE
═══════════════════════════════════════════ */
const STATE = {
  cat:'all', priceMin:0, priceMax:Infinity,
  regions:[], minRating:0, badges:[],
  sort:'default', view:'grid',
  cart:[], wish:[]
};

/* ═══════════════════════════════════════════
   SYNCHRONISATION SERVEUR (panier + favoris persistants)
   STATE.cart / STATE.wish servent de cache local ;
   la source de vérité est la base de données.
═══════════════════════════════════════════ */
const API_BASE = '/SouqTN/public';
let USER_LOGGED = false;   // déterminé au chargement via /cart/list

async function apiGet(path) {
  try {
    const r = await fetch(API_BASE + path, { headers:{'X-Requested-With':'fetch'} });
    return await r.json();
  } catch (e) { return { success:false }; }
}
async function apiPost(path, data) {
  try {
    const fd = new FormData();
    Object.entries(data || {}).forEach(([k,v]) => fd.append(k, v));
    const r = await fetch(API_BASE + path, { method:'POST', body:fd });
    return await r.json();
  } catch (e) { return { success:false }; }
}

// Charge panier + favoris depuis la base au démarrage
async function loadServerState() {
  const c = await apiGet('/cart/list');
  if (c && c.success) {
    USER_LOGGED = true;
    STATE.cart = (c.cart || []).map(it => ({ id: parseInt(it.product_id), qty: parseInt(it.qty) }));
  } else {
    USER_LOGGED = false;     // visiteur non connecté : panier en mémoire seulement
  }
  const w = await apiGet('/wish/list');
  if (w && w.success) STATE.wish = (w.wish || []).map(Number);

  updateCartUI();
  renderProducts();
  renderReco();
}

// Redirige vers la connexion si action nécessitant un compte
function needLoginRedirect() {
  showToast('Connectez-vous pour sauvegarder', 'error');
  setTimeout(() => { window.location.href = API_BASE + '/login'; }, 1200);
}


/* ═══════════════════════════════════════════
   HELPERS
═══════════════════════════════════════════ */
const tnd = n => n.toFixed(3).replace('.',',')+' TND';
const strs = r => '★'.repeat(Math.floor(r))+'☆'.repeat(5-Math.floor(r));
const disc = (o,c) => o ? Math.round((1-c/o)*100) : null;

function showToast(msg, type='') {
  const c = document.getElementById('toastContainer');
  const t = document.createElement('div');
  t.className = 'toast-msg'+(type?' '+type:'');
  t.textContent = (type==='success'?'✓ ':type==='error'?'✕ ':'')+msg;
  c.appendChild(t);
  requestAnimationFrame(()=>requestAnimationFrame(()=>t.classList.add('show')));
  setTimeout(()=>{t.classList.remove('show');setTimeout(()=>t.remove(),350)}, 2800);
}

/* ═══════════════════════════════════════════
   RENDER SIDEBAR
═══════════════════════════════════════════ */
function renderCats() {
  document.getElementById('catList').innerHTML = CATS.map(c=>`
    <div class="cat-item${STATE.cat===c.slug?' active':''}" onclick="filterByCategory('${c.slug}')">
      <div class="cat-left"><div class="cat-icon">${c.icon}</div>${c.name}</div>
      <span class="cat-count">${c.count}</span>
    </div>`).join('');
}

function renderRegions() {
  const regions = [...new Set(ARTISANS.map(a=>a.region))];
  document.getElementById('regionList').innerHTML = regions.map(r=>`
    <div class="region-item${STATE.regions.includes(r)?' active':''}" onclick="toggleRegion('${r}')">
      <div class="region-cb"></div><span>${r}</span>
    </div>`).join('');
}

/* ═══════════════════════════════════════════
   RENDER ARTISANS
═══════════════════════════════════════════ */
function renderArtisans() {
  document.getElementById('artisansGrid').innerHTML = ARTISANS.map(a=>`
    <div class="artisan-card">
      <div class="artisan-avatar">${a.em}</div>
      <div class="artisan-badge">✦ Vérifié</div>
      <div class="artisan-name">${a.name}</div>
      <div class="artisan-region">📍 ${a.region}</div>
      <div class="artisan-meta">
        <div class="artisan-meta-item"><span class="artisan-meta-num">${a.prods}</span><span class="artisan-meta-label">Produits</span></div>
        <div class="artisan-meta-item"><span class="artisan-meta-num">${a.rating}</span><span class="artisan-meta-label">Note</span></div>
      </div>
    </div>`).join('');
}

/* ═══════════════════════════════════════════
   PRODUCT CARD BUILDER
═══════════════════════════════════════════ */
const BADGE_CLASS = {promo:'badge-promo',new:'badge-new',premium:'badge-premium',bio:'badge-bio',bestseller:'badge-bestseller'};
const BADGE_LABEL = {promo:'PROMO',new:'NOUVEAU',premium:'PREMIUM',bio:'BIO',bestseller:'TOP VENTE'};

function buildCard(p) {
  const d = disc(p.orig, p.price);
  const w = STATE.wish.includes(p.id);
  const lowStock = p.stock < 10;
  return `
  <div class="product-card" data-id="${p.id}">
    <div class="product-img">
      <div class="product-emoji">${p.em}</div>
      <div class="product-badges">${p.badge?`<span class="badge ${BADGE_CLASS[p.badge]||''}">${BADGE_LABEL[p.badge]||p.badge}</span>`:''}</div>
      ${d?`<div class="discount-pct">-${d}%</div>`:''}
      <button class="wishlist-btn${w?' liked':''}" onclick="event.stopPropagation();toggleWish(${p.id})">${w?'♥':'♡'}</button>
      <button class="quick-add-btn" onclick="event.stopPropagation();addToCart(${p.id})">+ Ajouter au panier</button>
    </div>
    <div class="product-info" onclick="openModal(${p.id})">
      <div class="product-brand">${p.artisan} · ${p.region}</div>
      <div class="product-name">${p.name}</div>
      <div class="product-rating">
        <span class="stars">${strs(p.rating)}</span>
        <span class="rating-val">${p.rating} (${p.reviews})</span>
      </div>
      <div class="product-pricing">
        <span class="price-main">${tnd(p.price)}</span>
        ${p.orig?`<span class="price-original">${tnd(p.orig)}</span>`:''}
      </div>
      <div class="stock-row">
        <div class="stock-dot ${lowStock?'dot-orange':'dot-green'}"></div>
        <span class="${lowStock?'stock-low':'stock-ok'}">${lowStock?`Plus que ${p.stock} en stock`:'En stock'}</span>
      </div>
      <button class="add-cart-btn" onclick="event.stopPropagation();addToCart(${p.id})">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        Ajouter au panier
      </button>
    </div>
  </div>`;
}

/* ═══════════════════════════════════════════
   RENDER PRODUCTS
═══════════════════════════════════════════ */
function renderProducts() {
  let prods = [...PRODUCTS];
  if (STATE.cat !== 'all') prods = prods.filter(p=>p.cat===STATE.cat);
  prods = prods.filter(p=>p.price>=STATE.priceMin && p.price<=STATE.priceMax);
  if (STATE.regions.length) prods = prods.filter(p=>STATE.regions.includes(p.region));
  if (STATE.minRating) prods = prods.filter(p=>p.rating>=STATE.minRating);
  if (STATE.badges.length) prods = prods.filter(p=>STATE.badges.includes(p.badge));
  switch(STATE.sort) {
    case 'price-asc': prods.sort((a,b)=>a.price-b.price); break;
    case 'price-desc': prods.sort((a,b)=>b.price-a.price); break;
    case 'rating': prods.sort((a,b)=>b.rating-a.rating); break;
    case 'reviews': prods.sort((a,b)=>b.reviews-a.reviews); break;
  }
  const n = prods.length;
  document.getElementById('resultsCount').innerHTML = `<b>${n}</b> produit${n>1?'s':''} trouvé${n>1?'s':''}`;
  const grid = document.getElementById('productsGrid');
  if (!n) {
    grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:52px 20px;color:var(--muted)">
      <div style="font-size:2.8rem;margin-bottom:10px">🔍</div>
      <p style="font-size:14px;font-weight:600;color:var(--ink);margin-bottom:4px">Aucun produit trouvé</p>
      <p style="font-size:13px">Essayez d'autres critères</p>
      <button onclick="clearFilters()" style="margin-top:14px;background:var(--primary);color:#fff;border:none;padding:10px 22px;border-radius:8px;font-weight:700;font-size:13px;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif">Effacer les filtres</button>
    </div>`;
    return;
  }
  grid.innerHTML = prods.map(buildCard).join('');
}

function renderReco() {
  const picks = [...PRODUCTS].sort((a,b)=>b.reviews-a.reviews).slice(0,6);
  document.getElementById('recoGrid').innerHTML = picks.map(buildCard).join('');
}

/* ═══════════════════════════════════════════
   ACTIVE FILTERS CHIPS
═══════════════════════════════════════════ */
function renderChips() {
  const chips = [];
  if (STATE.cat !== 'all') {
    const c = CATS.find(x=>x.slug===STATE.cat);
    chips.push({label: c?.name||STATE.cat, clear: ()=>filterByCategory('all')});
  }
  if (STATE.priceMin>0 || STATE.priceMax<Infinity) {
    const mx = STATE.priceMax===Infinity?'∞':STATE.priceMax;
    chips.push({label:`${STATE.priceMin}–${mx} TND`, clear:()=>setPrice(0,Infinity,null)});
  }
  STATE.regions.forEach(r=>chips.push({label:`📍 ${r}`, clear:()=>toggleRegion(r)}));
  if (STATE.minRating) chips.push({label:`${strs(STATE.minRating)}+`, clear:()=>{STATE.minRating=0;renderProducts();renderChips();}});
  const el = document.getElementById('activeFilters');
  el.innerHTML = chips.map((c,i)=>`<div class="filter-chip">${c.label}<button onclick="_clr(${i})">×</button></div>`).join('');
  el._clrs = chips.map(c=>c.clear);
}
window._clr = i => document.getElementById('activeFilters')._clrs[i]();

/* ═══════════════════════════════════════════
   FILTER ACTIONS
═══════════════════════════════════════════ */
window.filterByCategory = function(slug) {
  STATE.cat = slug;
  document.getElementById('breadcrumbLabel').textContent = slug==='all'?'Tous les produits':(CATS.find(c=>c.slug===slug)?.name||slug);
  renderCats(); renderChips(); renderProducts();
  document.querySelectorAll('.nav-link[data-cat]').forEach(el=>el.classList.toggle('active', el.dataset.cat===slug));
};

window.setPrice = function(min, max, btn) {
  STATE.priceMin = min;
  STATE.priceMax = max===99999 ? Infinity : max;
  document.querySelectorAll('.preset-btn').forEach(b=>b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  document.getElementById('priceMin').value = min||'';
  document.getElementById('priceMax').value = (max===Infinity||max===99999)?'':max;
  renderChips(); renderProducts();
};

window.toggleRegion = function(r) {
  const i = STATE.regions.indexOf(r);
  if (i===-1) STATE.regions.push(r); else STATE.regions.splice(i,1);
  renderRegions(); renderChips(); renderProducts();
};

window.setRating = function(n) {
  STATE.minRating = n;
  renderChips(); renderProducts();
};

window.clearFilters = function() {
  STATE.cat='all'; STATE.priceMin=0; STATE.priceMax=Infinity;
  STATE.regions=[]; STATE.minRating=0; STATE.badges=[];
  document.querySelectorAll('.badge-filter').forEach(cb=>cb.checked=false);
  document.querySelectorAll('.preset-btn').forEach(b=>b.classList.remove('active'));
  renderCats(); renderRegions(); renderChips(); renderProducts();
};

document.getElementById('sortSelect').addEventListener('change', e=>{STATE.sort=e.target.value;renderProducts();});
document.getElementById('priceMin').addEventListener('change', e=>{STATE.priceMin=parseFloat(e.target.value)||0;renderChips();renderProducts();});
document.getElementById('priceMax').addEventListener('change', e=>{STATE.priceMax=parseFloat(e.target.value)||Infinity;renderChips();renderProducts();});
document.querySelectorAll('.badge-filter').forEach(cb=>cb.addEventListener('change',()=>{
  const v=cb.value, i=STATE.badges.indexOf(v);
  if(cb.checked&&i===-1)STATE.badges.push(v); else if(!cb.checked&&i!==-1)STATE.badges.splice(i,1);
  renderProducts();
}));

window.setView = function(v) {
  STATE.view = v;
  document.getElementById('productsGrid').classList.toggle('list-view', v==='list');
  document.getElementById('gridViewBtn').classList.toggle('active', v==='grid');
  document.getElementById('listViewBtn').classList.toggle('active', v==='list');
};

document.querySelectorAll('.nav-link[data-cat]').forEach(link=>link.addEventListener('click',()=>{
  if (link.dataset.cat) {filterByCategory(link.dataset.cat); document.getElementById('shopZone').scrollIntoView({behavior:'smooth'});}
}));

/* ═══════════════════════════════════════════
   CART
═══════════════════════════════════════════ */
window.addToCart = function(id) {
  const p = PRODUCTS.find(x=>x.id===id);
  if (!p) return;
  if (!USER_LOGGED) { needLoginRedirect(); return; }
  const ex = STATE.cart.find(i=>i.id===id);
  if (ex) ex.qty++; else STATE.cart.push({id, qty:1});
  updateCartUI();
  showToast(`${p.em} "${p.name}" ajouté au panier`, 'success');
  apiPost('/cart/add2', { id, qty:1 });
};

window.removeFromCart = function(id) {
  STATE.cart = STATE.cart.filter(i=>i.id!==id);
  updateCartUI();
  if (USER_LOGGED) apiPost('/cart/remove', { id });
};

window.changeQty = function(id, delta) {
  const it = STATE.cart.find(i=>i.id===id);
  if (it) it.qty = Math.max(1, it.qty+delta);
  updateCartUI();
  if (it && USER_LOGGED) apiPost('/cart/setqty', { id, qty: it.qty });
};

function updateCartUI() {
  const total = STATE.cart.reduce((s,i)=>s+i.qty, 0);
  document.getElementById('cartDotBadge').textContent = total;
  document.getElementById('cartItemCount').textContent = total;
  const body = document.getElementById('cartBody');
  const footer = document.getElementById('cartFooterZone');
  if (!STATE.cart.length) {
    body.innerHTML = `<div class="cart-empty-state"><div class="empty-emoji">🛒</div><p>Votre panier est vide</p><p style="font-size:12px;color:var(--muted)">Découvrez nos produits artisanaux</p></div>`;
    footer.style.display = 'none'; return;
  }
  footer.style.display = '';
  body.innerHTML = STATE.cart.map(item=>{
    const p = PRODUCTS.find(x=>x.id===item.id);
    if (!p) return '';
    return `<div class="cart-item">
      <div class="cart-item-img">${p.em}</div>
      <div class="cart-item-detail">
        <div class="cart-item-name">${p.name}</div>
        <div class="cart-item-artisan">${p.artisan}</div>
        <div class="cart-item-price">${tnd(p.price*item.qty)}</div>
        <div class="cart-qty-row">
          <div class="qty-ctrl">
            <button class="qty-btn" onclick="changeQty(${p.id},-1)">−</button>
            <div class="qty-val">${item.qty}</div>
            <button class="qty-btn" onclick="changeQty(${p.id},1)">+</button>
          </div>
          <button class="cart-remove" onclick="removeFromCart(${p.id})">Supprimer</button>
        </div>
      </div>
    </div>`;
  }).join('');
  const sub = STATE.cart.reduce((s,i)=>{const p=PRODUCTS.find(x=>x.id===i.id);return s+(p?p.price*i.qty:0);}, 0);
  const ship = sub>=150?0:7;
  document.getElementById('cartSubtotal').textContent = tnd(sub);
  document.getElementById('cartShipping').textContent = ship===0?'GRATUIT 🎉':tnd(ship);
  document.getElementById('cartTotal').textContent = tnd(sub+ship);
  document.getElementById('freeShipMsg').style.display = ship===0?'flex':'none';
}

const openCart = ()=>{document.getElementById('cartDrawer').classList.add('open');document.getElementById('drawerOverlay').classList.add('active');};
const closeCartDrawer = ()=>{document.getElementById('cartDrawer').classList.remove('open');document.getElementById('drawerOverlay').classList.remove('active');};
document.getElementById('cartOpenBtn').addEventListener('click', openCart);
document.getElementById('cartCloseBtn').addEventListener('click', closeCartDrawer);
document.getElementById('drawerOverlay').addEventListener('click', closeCartDrawer);

/* ═══════════════════════════════════════════
   PASSER LA COMMANDE (checkout → base de données)
═══════════════════════════════════════════ */
const _checkoutBtn = document.getElementById('checkoutBtn');
if (_checkoutBtn) {
  _checkoutBtn.addEventListener('click', async () => {
    if (!USER_LOGGED) { needLoginRedirect(); return; }
    if (!STATE.cart.length) { showToast('Votre panier est vide', 'error'); return; }

    _checkoutBtn.disabled = true;
    const res = await apiPost('/cart/checkout', {});
    _checkoutBtn.disabled = false;

    if (res && res.success) {
      STATE.cart = [];
      updateCartUI();
      showToast('✅ Commande passée ! Suivez-la dans votre espace.', 'success');
      setTimeout(() => { window.location.href = API_BASE + '/dashboard/client'; }, 1400);
    } else {
      showToast((res && res.message) ? res.message : 'Erreur lors de la commande', 'error');
    }
  });
}

/* ═══════════════════════════════════════════
   WISHLIST DRAWER
═══════════════════════════════════════════ */
function renderWish() {
  document.getElementById('wishItemCount').textContent = STATE.wish.length;
  const body = document.getElementById('wishBody');
  if (!STATE.wish.length) {
    body.innerHTML = `<div class="cart-empty-state"><div class="empty-emoji">♡</div><p>Aucun favori pour le moment</p><p style="font-size:12px;color:var(--muted)">Cliquez sur le cœur d'un produit</p></div>`;
    return;
  }
  body.innerHTML = STATE.wish.map(id=>{
    const p = PRODUCTS.find(x=>x.id===id);
    if (!p) return '';
    return `<div class="cart-item">
      <div class="cart-item-img">${p.em}</div>
      <div class="cart-item-detail">
        <div class="cart-item-name">${p.name}</div>
        <div class="cart-item-artisan">${p.artisan}</div>
        <div class="cart-item-price">${tnd(p.price)}</div>
        <div class="cart-qty-row">
          <button class="qty-btn" style="width:auto;padding:0 10px" onclick="addToCart(${p.id})">+ Panier</button>
          <button class="cart-remove" onclick="toggleWish(${p.id})">Retirer</button>
        </div>
      </div>
    </div>`;
  }).join('');
}

const openWish = ()=>{renderWish();document.getElementById('wishDrawer').classList.add('open');document.getElementById('drawerOverlay').classList.add('active');};
const closeWishDrawer = ()=>{document.getElementById('wishDrawer').classList.remove('open');document.getElementById('drawerOverlay').classList.remove('active');};
document.getElementById('wishOpenBtn').addEventListener('click', openWish);
document.getElementById('wishCloseBtn').addEventListener('click', closeWishDrawer);
document.getElementById('drawerOverlay').addEventListener('click', closeWishDrawer);

/* ═══════════════════════════════════════════
   WISHLIST
═══════════════════════════════════════════ */
window.toggleWish = function(id) {
  if (!USER_LOGGED) { needLoginRedirect(); return; }
  const i = STATE.wish.indexOf(id);
  const p = PRODUCTS.find(x=>x.id===id);
  if (i===-1) {STATE.wish.push(id); showToast(`${p?.em} Ajouté aux favoris`, 'success');}
  else {STATE.wish.splice(i,1); showToast('Retiré des favoris');}
  renderProducts(); renderReco(); renderWish();
  apiPost('/wish/toggle', { id });
};

/* ═══════════════════════════════════════════
   SEARCH
═══════════════════════════════════════════ */
function openSearch(q='') {
  document.getElementById('searchOverlay').classList.add('active');
  const inp = document.getElementById('searchPanelInput');
  inp.value = q; inp.focus();
  renderSearchResults(q);
}

document.getElementById('openSearchBtn').addEventListener('click',()=>openSearch(document.getElementById('headerSearchInput').value));
document.getElementById('headerSearchInput').addEventListener('keydown',e=>{if(e.key==='Enter') openSearch(e.target.value);});
document.getElementById('searchPanelCloseBtn').addEventListener('click',()=>document.getElementById('searchOverlay').classList.remove('active'));
document.getElementById('searchOverlay').addEventListener('click',e=>{if(e.target===document.getElementById('searchOverlay')) document.getElementById('searchOverlay').classList.remove('active');});
document.getElementById('searchPanelInput').addEventListener('input',e=>renderSearchResults(e.target.value));

function renderSearchResults(q) {
  const el = document.getElementById('searchResultsList');
  if (!q) {el.innerHTML='<div class="search-empty">Tapez pour rechercher…</div>'; return;}
  const ql = q.toLowerCase();
  const res = PRODUCTS.filter(p=>p.name.toLowerCase().includes(ql)||p.artisan.toLowerCase().includes(ql)||p.region.toLowerCase().includes(ql)||p.cat.toLowerCase().includes(ql));
  el.innerHTML = res.length
    ? res.map(p=>`<div class="search-result" onclick="document.getElementById('searchOverlay').classList.remove('active');openModal(${p.id})">
        <div class="search-result-em">${p.em}</div>
        <div><div class="search-result-name">${p.name}</div><div class="search-result-meta">${p.artisan} · ${p.region}</div></div>
        <div class="search-result-price">${tnd(p.price)}</div>
      </div>`).join('')
    : `<div class="search-empty">Aucun résultat pour "<b>${q}</b>"</div>`;
}

/* ═══════════════════════════════════════════
   PRODUCT MODAL
═══════════════════════════════════════════ */
window.openModal = function(id) {
  const p = PRODUCTS.find(x=>x.id===id);
  if (!p) return;
  const d = disc(p.orig, p.price);
  const w = STATE.wish.includes(p.id);
  document.getElementById('modalContent').innerHTML = `
    <div class="modal-layout">
      <div class="modal-image">${p.em}</div>
      <div class="modal-details">
        <div class="modal-badge-row">
          ${p.badge?`<span class="badge ${BADGE_CLASS[p.badge]||''}">${BADGE_LABEL[p.badge]||p.badge}</span>`:''}
          ${d?`<span style="background:var(--primary-lt);color:var(--primary);font-size:11px;font-weight:700;padding:2px 8px;border-radius:4px">-${d}%</span>`:''}
        </div>
        <div class="modal-title">${p.name}</div>
        <div class="modal-artisan">Par <a>${p.artisan}</a> · ${p.region}</div>
        <div class="modal-rating-row">
          <span class="stars" style="font-size:16px">${strs(p.rating)}</span>
          <span style="font-size:12.5px;color:var(--muted)">${p.rating} · ${p.reviews} avis</span>
        </div>
        <div class="modal-price-row">
          <span class="modal-price">${tnd(p.price)}</span>
          ${p.orig?`<span class="modal-was">${tnd(p.orig)}</span>`:''}
          ${d?`<span class="modal-save">Économisez ${tnd(p.orig-p.price)}</span>`:''}
        </div>
        <p class="modal-desc">${p.desc}</p>
        <div class="spec-table">
          ${p.mat?`<div class="spec-row"><span class="spec-label">Matière</span><span class="spec-value">${p.mat}</span></div>`:''}
          ${p.dim?`<div class="spec-row"><span class="spec-label">Dimensions</span><span class="spec-value">${p.dim}</span></div>`:''}
          ${p.wt?`<div class="spec-row"><span class="spec-label">Poids</span><span class="spec-value">${p.wt}</span></div>`:''}
          <div class="spec-row"><span class="spec-label">Stock</span><span class="spec-value" style="color:${p.stock<10?'#D97706':'#16A34A'}">${p.stock<10?`⚠ Plus que ${p.stock} en stock`:`✓ En stock (${p.stock} disponibles)`}</span></div>
        </div>
        <div class="modal-qty-row">
          <span class="modal-qty-label">Quantité :</span>
          <div class="qty-ctrl">
            <button class="qty-btn" onclick="var v=this.nextElementSibling;v.textContent=Math.max(1,+v.textContent-1)">−</button>
            <div class="qty-val" id="modalQtyVal">1</div>
            <button class="qty-btn" onclick="var v=this.previousElementSibling;v.textContent=Math.min(${p.stock},+v.textContent+1)">+</button>
          </div>
        </div>
        <div class="modal-actions">
          <button class="modal-add-cart" onclick="addToCart(${p.id});document.getElementById('productModal').classList.remove('active')">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            Ajouter au panier
          </button>
          <button class="modal-wish-btn${w?' liked':''}" onclick="toggleWish(${p.id})" style="${w?'color:#ef4444;border-color:#fca5a5':''}">♥</button>
        </div>
        <div class="modal-trust">
          <span class="modal-trust-item">🔒 Paiement sécurisé</span>
          <span class="modal-trust-item">🚚 Livraison 24 gouvernorats</span>
          <span class="modal-trust-item">↩️ Retours 30 jours</span>
          <span class="modal-trust-item">⭐ Artisan vérifié</span>
        </div>
      </div>
    </div>`;
  document.getElementById('productModal').classList.add('active');
};

document.getElementById('modalCloseBtn').addEventListener('click',()=>document.getElementById('productModal').classList.remove('active'));
document.getElementById('productModal').addEventListener('click',e=>{if(e.target===document.getElementById('productModal')) document.getElementById('productModal').classList.remove('active');});

/* ═══════════════════════════════════════════
   KEYBOARD
═══════════════════════════════════════════ */
document.addEventListener('keydown', e=>{
  if (e.key==='Escape') {
    document.getElementById('productModal').classList.remove('active');
    document.getElementById('searchOverlay').classList.remove('active');
    closeCartDrawer();
  }
});

/* ═══════════════════════════════════════════
   COUNTER ANIMATION
═══════════════════════════════════════════ */
function animateCounters() {
  document.querySelectorAll('[data-count]').forEach(el=>{
    const target = +el.dataset.count;
    const steps = 40, dur = 1400;
    let cur = 0;
    const tick = setInterval(()=>{
      cur += target/steps;
      if (cur>=target) {el.textContent=target.toLocaleString(); clearInterval(tick);}
      else el.textContent = Math.floor(cur).toLocaleString();
    }, dur/steps);
  });
}

/* ═══════════════════════════════════════════
   INIT
═══════════════════════════════════════════ */
renderCats();
renderRegions();
renderArtisans();
renderProducts();
renderReco();
renderChips();
updateCartUI();
animateCounters();
loadServerState();   // ← charge panier + favoris depuis la base (si connecté)
</script>
</body>
</html>

