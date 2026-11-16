TECHSTORE - GUÍA DE INSTALACIÓN
================================

IMPORTANTE
Este proyecto usa HTML, CSS, JavaScript, PHP y MariaDB.
El navegador no debe conectarse directamente a MariaDB: PHP funciona como
backend y realiza las consultas desde el servidor usando PDO y consultas preparadas.

REQUISITOS
- XAMPP instalado.
- Apache y MySQL iniciados desde el Panel de Control de XAMPP.
- PHP con la extensión PDO MySQL habilitada (normalmente ya viene activada).

INSTALACIÓN EN WINDOWS / XAMPP
1. Descomprimí la carpeta TechStore.
2. Copiá la carpeta TechStore dentro de:
   C:\xampp\htdocs\
3. Abrí XAMPP y encendé Apache y MySQL.
4. Entrá en http://localhost/phpmyadmin
5. Abrí la pestaña Importar.
6. Elegí el archivo:
   C:\xampp\htdocs\TechStore\database\techstore.sql
7. Presioná Importar / Continuar.
   El script crea la base techstore, sus tablas y productos de ejemplo.
8. Abrí la tienda:
   http://localhost/TechStore/
9. Para administrar productos:
   http://localhost/TechStore/admin/admin.php

CONFIGURACIÓN DE LA BASE
Archivo: config/database.php
Por defecto:
- Servidor: localhost
- Base de datos: techstore
- Usuario: root
- Contraseña: vacía

Si tu usuario root tiene contraseña, modificá DB_PASS en ese archivo.

QUÉ INCLUYE
- Catálogo conectado a MariaDB.
- Búsqueda por nombre y descripción.
- Filtro por categoría y precio máximo.
- Carrito guardado en LocalStorage.
- Registro de clientes y pedidos en MariaDB.
- Descuento de stock al confirmar un pedido.
- Panel para agregar, editar y quitar productos.

ACLARACIONES IMPORTANTES
- El checkout es una simulación educativa. No cobra dinero ni procesa tarjetas.
- El panel de administración es una demostración y todavía NO tiene inicio de sesión.
  No lo publiques en Internet sin agregar autenticación y controles de seguridad.
- Las imágenes de los productos se representan inicialmente con emojis. Podés
  reemplazarlos por nombres/rutas de imágenes dentro de la carpeta img.
- Los precios de ejemplo son ficticios y están expresados en pesos argentinos.

SOLUCIÓN DE PROBLEMAS
- Si aparece "No se pudieron cargar..." revisá que Apache y MySQL estén encendidos.
- Revisá que hayas importado database/techstore.sql en phpMyAdmin.
- Si la conexión falla, revisá config/database.php.
- Abrí la página desde http://localhost/TechStore/ y NO haciendo doble clic
  sobre index.php. PHP necesita ejecutarse desde Apache.
