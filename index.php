<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TechStore | Tecnología para vos</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header class="topbar">
  <a class="logo" href="index.php"><span>TS</span> TechStore</a>
  <nav><a href="#productos">Productos</a><a href="admin/admin.php">Administración</a></nav>
  <button class="cart-button" id="openCart">Carrito <b id="cartCount">0</b></button>
</header>

<main>
  <section class="hero">
    <div>
      <p class="eyebrow">TECNOLOGÍA • INFORMÁTICA • GAMING</p>
      <h1>Todo lo que necesitás<br><span>para tu próximo nivel.</span></h1>
      <p class="hero-copy">Encontrá componentes, periféricos y accesorios para armar tu espacio ideal.</p>
      <a class="primary-link" href="#productos">Explorar productos</a>
    </div>
    <div class="hero-art"><div class="orb"></div><div class="chip">TECH<br>STORE</div><div class="floating f1">GPU</div><div class="floating f2">SSD</div></div>
  </section>

  <section class="catalog" id="productos">
    <div class="section-heading"><div><p class="eyebrow">NUESTRO CATÁLOGO</p><h2>Productos destacados</h2></div><span id="resultCount" class="muted"></span></div>
    <div class="filters">
      <input id="searchInput" type="search" placeholder="Buscar teclado, monitor, memoria...">
      <select id="categoryFilter"><option value="">Todas las categorías</option></select>
      <select id="priceFilter"><option value="">Cualquier precio</option><option value="50000">Hasta $50.000</option><option value="150000">Hasta $150.000</option><option value="300000">Hasta $300.000</option><option value="600000">Hasta $600.000</option></select>
    </div>
    <div id="productsGrid" class="products-grid"><p class="muted">Cargando productos...</p></div>
  </section>
</main>

<footer><strong>TechStore</strong><span>Proyecto educativo de tienda online</span></footer>

<div class="overlay" id="cartOverlay"></div>
<aside class="cart-panel" id="cartPanel">
  <div class="cart-head"><h2>Tu carrito</h2><button id="closeCart" class="icon-button">✕</button></div>
  <div id="cartItems" class="cart-items"></div>
  <div class="cart-bottom"><div class="total-line"><span>Total</span><strong id="cartTotal">$0</strong></div><button class="primary-button" id="checkoutButton">Finalizar compra</button></div>
</aside>

<div class="modal-backdrop" id="checkoutModal">
  <form class="checkout-form" id="checkoutForm">
    <button type="button" class="modal-close" id="closeCheckout">✕</button>
    <p class="eyebrow">ÚLTIMO PASO</p><h2>Datos de compra</h2>
    <label>Nombre completo<input name="cliente" required maxlength="100"></label>
    <label>Correo electrónico<input name="email" type="email" required maxlength="150"></label>
    <p class="muted small">Esta compra es una simulación educativa; no se realiza ningún cobro.</p>
    <button class="primary-button" type="submit">Confirmar pedido</button>
    <p id="checkoutMessage" class="form-message"></p>
  </form>
</div>
<script src="js/app.js"></script>
<script src="js/productos.js"></script>
<script src="js/carrito.js"></script>
</body>
</html>
