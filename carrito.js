// Carrito guardado en LocalStorage. La compra se registra en MariaDB al confirmar.
let cart = JSON.parse(localStorage.getItem('techstore_cart') || '[]');

const cartPanel = document.getElementById('cartPanel');
const cartOverlay = document.getElementById('cartOverlay');
const checkoutModal = document.getElementById('checkoutModal');

function saveCart() {
  localStorage.setItem('techstore_cart', JSON.stringify(cart));
  renderCart();
}

function addToCart(id) {
  const product = allProducts.find(p => Number(p.id_producto) === Number(id));
  if (!product) return;
  const found = cart.find(item => Number(item.id) === Number(id));
  if (found) {
    if (found.cantidad >= Number(product.stock)) return alert('No hay más unidades disponibles.');
    found.cantidad++;
  } else {
    cart.push({ id: Number(id), nombre: product.nombre, precio: Number(product.precio), cantidad: 1, stock: Number(product.stock) });
  }
  saveCart();
}

function changeQuantity(id, amount) {
  const item = cart.find(p => p.id === id);
  if (!item) return;
  item.cantidad += amount;
  if (item.cantidad <= 0) cart = cart.filter(p => p.id !== id);
  if (item && item.cantidad > item.stock) item.cantidad = item.stock;
  saveCart();
}

function renderCart() {
  const count = cart.reduce((sum, item) => sum + item.cantidad, 0);
  document.getElementById('cartCount').textContent = count;
  const total = cart.reduce((sum, item) => sum + item.precio * item.cantidad, 0);
  document.getElementById('cartTotal').textContent = formatPrice(total);
  const container = document.getElementById('cartItems');
  if (!cart.length) {
    container.innerHTML = '<p class="empty">Tu carrito está vacío.</p>';
    return;
  }
  container.innerHTML = cart.map(item => `
    <div class="cart-row">
      <div><strong>${escapeHtml(item.nombre)}</strong><small>${formatPrice(item.precio)} c/u</small></div>
      <div class="quantity">
        <button onclick="changeQuantity(${item.id},-1)">−</button><span>${item.cantidad}</span>
        <button onclick="changeQuantity(${item.id},1)">+</button>
      </div>
    </div>`).join('');
}

function openCart() { cartPanel.classList.add('open'); cartOverlay.classList.add('show'); }
function closeCart() { cartPanel.classList.remove('open'); cartOverlay.classList.remove('show'); }

document.getElementById('openCart').addEventListener('click', openCart);
document.getElementById('closeCart').addEventListener('click', closeCart);
cartOverlay.addEventListener('click', closeCart);
document.getElementById('checkoutButton').addEventListener('click', () => {
  if (!cart.length) return alert('Agregá algún producto antes de continuar.');
  checkoutModal.classList.add('show');
});
document.getElementById('closeCheckout').addEventListener('click', () => checkoutModal.classList.remove('show'));

document.getElementById('checkoutForm').addEventListener('submit', async event => {
  event.preventDefault();
  const form = event.currentTarget;
  const message = document.getElementById('checkoutMessage');
  const values = new FormData(form);
  const button = form.querySelector('button[type="submit"]');
  button.disabled = true;
  message.textContent = 'Registrando pedido...';
  try {
    const result = await apiRequest(`${API}/checkout.php`, {
      method: 'POST',
      body: JSON.stringify({
        cliente: values.get('cliente'),
        email: values.get('email'),
        items: cart.map(item => ({ id: item.id, cantidad: item.cantidad }))
      })
    });
    message.textContent = `¡Pedido #${result.pedido} registrado! Total: ${formatPrice(result.total)}.`;
    cart = [];
    saveCart();
    await loadProducts();
    form.reset();
    setTimeout(() => { checkoutModal.classList.remove('show'); closeCart(); message.textContent = ''; }, 3000);
  } catch (error) {
    message.textContent = error.message;
  } finally {
    button.disabled = false;
  }
});

renderCart();
