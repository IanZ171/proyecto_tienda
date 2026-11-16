// Funciones generales y utilidades compartidas.
const API = 'api';

function formatPrice(value) {
  return new Intl.NumberFormat('es-AR', {
    style: 'currency', currency: 'ARS', maximumFractionDigits: 0
  }).format(Number(value));
}

async function apiRequest(url, options = {}) {
  const response = await fetch(url, {
    headers: { 'Content-Type': 'application/json' },
    ...options
  });
  const data = await response.json();
  if (!response.ok || !data.ok) throw new Error(data.mensaje || 'Ocurrió un error.');
  return data;
}
