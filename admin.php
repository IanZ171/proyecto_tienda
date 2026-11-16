<?php
// Panel de administración de demostración. Para uso real, agregar autenticación.
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administración | TechStore</title><link rel="stylesheet" href="../css/estilos.css"></head>
<body>
<header class="topbar"><a class="logo" href="../index.php"><span>TS</span> TechStore</a><a class="back-link" href="../index.php">← Volver a la tienda</a></header>
<main class="admin-wrap">
  <div class="admin-top"><div><p class="eyebrow">GESTIÓN DEL CATÁLOGO</p><h1>Administración</h1></div></div>
  <div class="admin-layout">
    <section class="admin-box">
      <h2 id="formTitle">Agregar producto</h2>
      <form id="productForm" class="admin-form">
        <input type="hidden" name="id_producto">
        <label>Nombre<input name="nombre" required maxlength="120"></label>
        <label>Descripción<textarea name="descripcion"></textarea></label>
        <label>Precio (ARS)<input name="precio" type="number" min="0" step="0.01" required></label>
        <label>Stock<input name="stock" type="number" min="0" required></label>
        <label>Imagen / emoji<input name="imagen" placeholder="Ejemplo: 🖥️"></label>
        <label>Categoría<select name="id_categoria" id="adminCategory" required></select></label>
        <button class="primary-button" type="submit">Guardar producto</button>
        <button class="table-action" type="button" id="cancelEdit" hidden>Cancelar edición</button>
        <p id="adminMessage" class="form-message"></p>
      </form>
    </section>
    <section class="admin-box">
      <h2>Productos registrados</h2>
      <div style="overflow-x:auto"><table class="admin-table"><thead><tr><th>Producto</th><th>Precio</th><th>Stock</th><th>Acciones</th></tr></thead><tbody id="adminProducts"></tbody></table></div>
    </section>
  </div>
</main>
<script src="../js/app.js"></script><script src="admin.js"></script>
</body></html>
