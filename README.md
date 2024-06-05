# Nombre del Proyecto

# Aplicación de Gestión de Convenios del Centro Tecnológico de Cúcuta

## Descripción

Esta es una aplicación web diseñada para gestionar, buscar y actualizar convenios en el Centro Tecnológico de Cúcuta (CTC). La aplicación permite a los usuarios interactuar con los registros de convenios de manera eficiente y segura.

## Funcionalidades Principales

- **CRUD Completo**: Creación, lectura, actualización y eliminación de convenios.
- **Búsqueda y Filtrado**: Búsqueda avanzada y filtrado de registros.
- **Paginación**: Navegación paginada a través de grandes conjuntos de datos.
- **Exportación de Datos**: Exportación de datos a formato .xlsx (Excel).
- **Seguridad y Roles**: Gestión de usuarios con roles y permisos específicos.
- **Responsive**: Interfaz de usuario adaptable a cualquier tamaño de pantalla.
- **Modo Oscuro y Claro**: Posibilidad de cambiar el tema de la página.

## Tecnologías Utilizadas

- **Frontend**: HTML, CSS, Filament
- **Backend**: PHP, Laravel
- **Base de Datos**: MySQL
- **Entorno de Desarrollo**: Visual Studio Code

## Requisitos Previos

- **PHP** >= 7.4
- **Composer**
- **Node.js** y **npm**
- **MySQL**

## Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/Danirodry/app-gestion-convenios-ctc.git
   cd app-gestion-convenios-ctc

2. Instala las dependencias de PHP con Composer:

composer install

3. Instala las dependencias de Node.js:

npm install

4. Configura el archivo .env:

cp .env.example .env
php artisan key:generate

5. Configura la conexión a la base de datos en el archivo .env.

6. Ejecuta las migraciones y los seeders:

php artisan migrate

7. Crear usuario admin en filament

php artisan make:filament-user

8. Inicia el servidor de desarrollo:

php artisan serve

## Uso
Accede a la aplicación en tu navegador web en http://localhost:8000.

## Metodología de Trabajo
Kanban: Gestión del flujo de trabajo utilizando Kanban en Jira.
Control de Versiones: Uso de Git y GitHub para gestionar los commits y versiones del proyecto.


## Contribuciones
Las contribuciones son bienvenidas. Por favor, abre un "issue" para discutir cambios importantes antes de enviar un "pull request".

## Licencia
Este proyecto está licenciado bajo la Licencia GPL.