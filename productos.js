// Catálogo, búsqueda y filtros.
let allProducts = [];
let categories = [];

const grid = document.getElementById('productsGrid');
const searchInput = document.getElementById('searchInput');
const categoryFilter = document.getElementById('categoryFilter');
const priceFilter = document.getElementById('priceFilter');

async function loadCategories() {
  const data = await apiRequest(`${API}/categories.php`);
  categories = data.categorias;
  categoryFilter.innerHTML = '<option value="">Todas las categorías</option>' +
    categories.map(c => `<option value="${c.id_categoria}">${escapeHtml(c.nombre)}</option>`).join('');
}

async function loadProducts() {
  try {
    const data = await apiRequest(`${API}/products.php`);
    allProducts = data.productos;
    renderProducts();
  } catch (error) {
    grid.innerHTML = `<p class="muted">${escapeHtml(error.message)} Asegurate de importar la base de datos.</p>`;
  }
}

function escapeHtml(value) {
  return String(value ?? '').replace(/[&<>"']/g, char => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
  }[char]));
}

function renderProducts() {
  const query = searchInput.value.trim().toLowerCase();
  const category = categoryFilter.value;
  const max = Number(priceFilter.value) || Infinity;
  const filtered = allProducts.filter(p =>
    (p.nombre.toLowerCase().includes(query) || (p.descripcion || '').toLowerCase().includes(query)) &&
    (!category || String(p.id_categoria) === category) &&
    Number(p.precio) <= max
  );
  document.getElementById('resultCount').textContent = `${filtered.length} productos`;
  if (!filtered.length) {
    grid.innerHTML = '<p class="muted">No encontramos productos con esos filtros.</p>';
    return;
  }
  grid.innerHTML = filtered.map(p => `
    <article class="product-card">
      <div class="product-image">${escapeHtml(p.imagen || '💻')}</div>
      <div class="product-info">
        <span class="category-tag">${escapeHtml(p.categoria)}</span>
        <h3>${escapeHtml(p.nombre)}</h3>
        <p>${escapeHtml(p.descripcion)}</p>
        <div class="product-bottom">
          <span class="price">${formatPrice(p.precio)}</span>
          <button class="add-button" onclick="addToCart(${Number(p.id_producto)})" ${Number(p.stock) < 1 ? 'disabled' : ''}>
            ${Number(p.stock) < 1 ? 'Sin stock' : 'Agregar +'}
          </button>
        </div>
        <small class="muted">Stock: ${Number(p.stock)}</small>
      </div>
    </article>`).join('');
}

searchInput.addEventListener('input', renderProducts);
categoryFilter.addEventListener('change', renderProducts);
priceFilter.addEventListener('change', renderProducts);

(async function initCatalog() {
  try { await loadCategories(); } catch (e) { console.error(e); }
  await loadProducts();
})();
