# Entorno de Desarrollo Local — Moodle 5.3

Repositorio personal de prácticas, plantillas de configuración y scripts CLI para el desarrollo de plugins en **Moodle LMS**.

---

## 1. Arquitectura y Stack Tecnológico (Opción B: Nativo en WSL2)

| Componente | Especificación |
| :--- | :--- |
| **Sistema Anfitrión** | Windows (acceso web mediante *localhost forwarding* de WSL2) |
| **Sistema de Desarrollo** | Ubuntu 26.04 LTS sobre WSL2 |
| **Versión de Moodle** | 5.3 (código fuente en `~/moodle-dev/moodle`, con `public/` como *DocumentRoot*) |
| **Servidor Web y PHP** | Apache 2.4 + PHP-FPM 8.4 |
| **Base de Datos** | PostgreSQL (`moodledev`) |
| **URL de Acceso Local** | `http://localhost:8080` |
| **Directorio de Datos (`dataroot`)** | `/var/www/moodledata-dev` |

---

## 2. Procedimiento de Creación y Configuración del Entorno

Pasos ejecutados para dejar la instancia operativa y lista para el desarrollo modular:

1. **Separación de código fuente y directorio de datos (`moodledata`):**
   - Repositorio de código ubicado en `~/moodle-dev/moodle` (con `public/` expuesto por Apache).
   - Directorio de datos ubicado fuera del alcance web en `/var/www/moodledata-dev`, asignando propiedad recursiva al usuario del servidor web (`www-data:www-data`).
2. **Configuración de red y puertos en WSL2:**
   - VirtualHost de Apache escuchando en el puerto `8080` y `$CFG->wwwroot = 'http://localhost:8080'` en `config.php`, aprovechando el túnel automático de `localhost` de WSL2 sin necesidad de modificar el archivo `hosts` de Windows.
3. **Ajuste de permisos y modo depuración en `config.php`:**
   - Definición de `$CFG->directorypermissions = 0777;` (eliminando el bit SGID `02777` por defecto para evitar bloqueos de `mkdir()` en WSL2).
   - Activación de `DEBUG_DEVELOPER` (`E_ALL | E_STRICT` y `$CFG->debugdisplay = 1`).
4. **Instalación del paquete de idioma español (`es`) vía CLI:**
   ```bash
   sudo -u www-data php ~/moodle-dev/moodle/admin/cli/install_langpack.php --lang=es
   sudo -u www-data php ~/moodle-dev/moodle/admin/cli/purge_caches.php

## 3. Cómo Arrancar, Comprobar y Parar el Entorno

En WSL2, los servicios de Ubuntu pueden iniciarse detenidos al abrir una sesión nueva de Windows. Utiliza estos comandos desde la terminal de Ubuntu para controlar todo el ciclo de vida del entorno:

### ▶️ Arrancar el entorno completo
```bash
sudo service postgresql start
sudo service php8.4-fpm start
sudo service apache2 start
sudo service cron start
```

### 🔍 Comprobar estado de los servicios y respuesta web
```bash
sudo service postgresql status
sudo service php8.4-fpm status
sudo service apache2 status
sudo service cron status
curl -I http://localhost:8080
```
*(La respuesta HTTP esperada es `HTTP/1.1 200 OK` o `HTTP/1.1 303 See Other`).*

### ⏹️ Parar el entorno completo
```bash
sudo service apache2 stop
sudo service php8.4-fpm stop
sudo service postgresql stop
sudo service cron stop
```

---

## 4. Estructura del Repositorio

- `README.md`: Documentación de arquitectura, instalación y operación del entorno.
- `.gitignore`: Exclusión de credenciales reales (`config.php`), volcados `.sql` y directorios `moodledata`.
- `config/config-dev.example.php`: Plantilla de configuración de Moodle para desarrollo sin secretos.
- `scripts/consultas_t3.php`: Script CLI de pruebas con la Database API (`$DB`) y depuración SQL.
