let adminProducts = [];
let adminCategories = [];
const productForm = document.getElementById('productForm');

async function loadAdminData() {
  try {
    const [productsData, categoriesData] = await Promise.all([
      apiRequest('../api/products.php'), apiRequest('../api/categories.php')
    ]);
    adminProducts = productsData.productos;
    adminCategories = categoriesData.categorias;
    document.getElementById('adminCategory').innerHTML = adminCategories.map(c =>
      `<option value="${c.id_categoria}">${escapeHtml(c.nombre)}</option>`).join('');
    renderAdminProducts();
  } catch (error) {
    document.getElementById('adminMessage').textContent = error.message;
  }
}

function renderAdminProducts() {
  const tbody = document.getElementById('adminProducts');
  tbody.innerHTML = adminProducts.map(p => `
    <tr><td>${escapeHtml(p.nombre)}</td><td>${formatPrice(p.precio)}</td><td>${p.stock}</td>
    <td><button class="table-action" onclick="editProduct(${p.id_producto})">Editar</button>
    <button class="table-action delete" onclick="deleteProduct(${p.id_producto})">Eliminar</button></td></tr>
  `).join('');
}

function editProduct(id) {
  const p = adminProducts.find(item => Number(item.id_producto) === Number(id));
  if (!p) return;
  for (const key of ['id_producto','nombre','descripcion','precio','stock','imagen','id_categoria']) {
    productForm.elements[key].value = p[key] ?? '';
  }
  document.getElementById('formTitle').textContent = 'Editar producto';
  document.getElementById('cancelEdit').hidden = false;
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetProductForm() {
  productForm.reset();
  productForm.elements.id_producto.value = '';
  document.getElementById('formTitle').textContent = 'Agregar producto';
  document.getElementById('cancelEdit').hidden = true;
}

document.getElementById('cancelEdit').addEventListener('click', resetProductForm);

productForm.addEventListener('submit', async event => {
  event.preventDefault();
  const data = Object.fromEntries(new FormData(productForm).entries());
  data.precio = Number(data.precio);
  data.stock = Number(data.stock);
  data.id_categoria = Number(data.id_categoria);
  const editing = Boolean(data.id_producto);
  try {
    await apiRequest('../api/products.php', {
      method: editing ? 'PUT' : 'POST',
      body: JSON.stringify(data)
    });
    document.getElementById('adminMessage').textContent = 'Producto guardado correctamente.';
    resetProductForm();
    await loadAdminData();
  } catch (error) {
    document.getElementById('adminMessage').textContent = error.message;
  }
});

async function deleteProduct(id) {
  if (!confirm('¿Querés quitar este producto del catálogo?')) return;
  try {
    await apiRequest(`../api/products.php?id=${id}`, { method: 'DELETE' });
    await loadAdminData();
  } catch (error) {
    alert(error.message);
  }
}

loadAdminData();
