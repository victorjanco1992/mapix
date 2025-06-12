# 🧭 Ruta Libre

**Mapix** es una aplicación web orientada al turismo de exploración no convencional. Permite a usuarios visualizar, registrar y compartir rutas de aventura, con funcionalidades de geolocalización, mapas interactivos y autenticación de usuarios.

---

## 🌐 Tecnologías utilizadas

- **PHP** – Lógica del servidor y estructura MVC
- **MySQL** – Base de datos relacional para rutas, usuarios y registros
- **HTML & CSS** – Estructura y estilos de la interfaz
- **JavaScript** – Interacción dinámica en el navegador
- **Leaflet.js** – Librería de mapas interactivos
- **Autenticación de usuarios** – Sistema de login y registro seguro

---

## ⚙️ Instalación local

### 1. Requisitos previos
 PHP 7.4+  
- MySQL / MariaDB  
- Servidor local (recomendado: XAMPP, WAMP, Laragon, etc.)  
- Git (opcional)

### 2. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/mapix.git
cd mapix
### 3. Configurar base de datos
 -   Crear una base de datos (por ejemplo: ruta_libre)
 -   Importar el archivo mapa.sql en phpMyAdmin
### 4. Levantar el proyecto
 -   Colocar la carpeta en htdocs/ (XAMPP) o similar
 -   Ingresar desde el navegador a:
http://localhost/mapix
