# Sistema de Gestión de Noticias - Portal de Noticias

## Funcionalidades Implementadas

### 📰 Vista Pública de Noticias
- **URL**: `index.php?controller=news&action=index`
- Lista de noticias con paginación
- Noticias destacadas en la parte superior
- Sidebar con noticias recientes
- Búsqueda de noticias
- Categorías y vistas

### 📄 Vista Individual de Noticia
- **URL**: `index.php?controller=news&action=show&slug=[slug-noticia]`
- Contenido completo de la noticia
- Galería de imágenes adicionales
- Noticias relacionadas
- Botones de compartir en redes sociales
- Contador de visualizaciones

### 🔍 Búsqueda de Noticias
- **URL**: `index.php?controller=news&action=search&q=[términos]`
- Búsqueda por título, contenido y categoría
- Resaltado de términos de búsqueda
- Resultados paginados

### 🛠️ Panel Administrativo

#### Lista de Noticias (Admin)
- **URL**: `index.php?controller=news&action=admin`
- **Permisos**: Editor o Admin
- Vista completa de todas las noticias
- Filtros por estado (publicadas, borradores, archivadas)
- Estadísticas en tiempo real
- Acciones rápidas (editar, publicar/despublicar, eliminar)

#### Crear Nueva Noticia
- **URL**: `index.php?controller=news&action=create`
- **Permisos**: Editor o Admin
- Editor completo con vista previa en tiempo real
- Subida múltiple de imágenes con drag & drop
- Configuración de categorías y estado
- Opción de noticia destacada
- Contadores de caracteres para optimización SEO

#### Editar Noticia
- **URL**: `index.php?controller=news&action=edit&id=[id]`
- **Permisos**: Editor o Admin
- Edición completa de noticias existentes
- Gestión de imágenes actuales y nuevas
- Historial de cambios visible
- Vista previa actualizada

## 🚀 Cómo Usar el Sistema

### Para Administradores:

1. **Acceder al Panel Admin**
   - Inicia sesión como administrador
   - Ve a: Dashboard → Gestionar Noticias

2. **Crear una Nueva Noticia**
   ```
   1. Clic en "Nueva Noticia"
   2. Completar título y contenido (obligatorios)
   3. Agregar extracto para SEO
   4. Subir imágenes (primera será la principal)
   5. Configurar categoría y estado
   6. Marcar como destacada si es necesario
   7. Guardar como borrador o publicar directamente
   ```

3. **Gestionar Noticias Existentes**
   - Ver estadísticas en tiempo real
   - Filtrar por estado
   - Publicar/despublicar con un clic
   - Editar contenido completo
   - Eliminar noticias (solo admins)

### Para Editores:
- Mismas funciones que admin excepto eliminar noticias
- Pueden crear, editar y publicar noticias
- Acceso a estadísticas y gestión completa

### Para Usuarios Públicos:

1. **Ver Noticias**
   - Navegar a "Noticias" en el menú principal
   - Explorar noticias destacadas
   - Leer noticias completas
   - Buscar contenido específico

2. **Búsqueda**
   - Usar la barra de búsqueda en la página de noticias
   - Buscar por palabras clave, categorías o contenido
   - Filtros automáticos disponibles

## 🔧 Características Técnicas

### Seguridad
- Protección CSRF en todos los formularios
- Verificación de sesiones y permisos
- Validación de archivos subidos
- Escape de HTML para prevenir XSS

### SEO y Rendimiento
- URLs amigables con slugs
- Meta descripciones personalizables
- Contadores de caracteres para optimización
- Imágenes optimizadas y responsive

### Multimedia
- Soporte para JPG, PNG, GIF, WebP
- Límite de 5MB por imagen
- Galería automática de imágenes
- Preview en tiempo real

### Base de Datos
- Tablas: `news` y `news_images`
- Relaciones con usuarios (autores)
- Contadores de visualizaciones
- Estados de publicación

## 📱 Responsive Design
- Interfaz completamente responsive
- Optimizado para móviles y tablets
- Bootstrap 5 para consistencia
- Iconos Font Awesome

## 🔗 Enlaces Importantes

### Admin:
- Lista de noticias: `index.php?controller=news&action=admin`
- Nueva noticia: `index.php?controller=news&action=create`
- Dashboard: `index.php?controller=dashboard`

### Público:
- Todas las noticias: `index.php?controller=news&action=index`
- Búsqueda: `index.php?controller=news&action=search`
- Inicio: `index.php`

## ⚠️ Requisitos
- PHP 7.4+
- MySQL/MariaDB
- Permisos de escritura en `uploads/news/`
- Sessions habilitadas
- MeekroDB configurado

El sistema está completamente funcional y listo para usar. Todos los archivos han sido creados con seguridad integrada y funcionalidades completas para la gestión de noticias.