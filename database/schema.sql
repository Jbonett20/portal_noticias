-- Base de datos
CREATE DATABASE IF NOT EXISTS portal_noticias CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portal_noticias;

-- Usuarios (administradores/editores)
CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  email VARCHAR(150) NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL, -- almacenar resultado de password_hash()
  full_name VARCHAR(150) NULL,
  role ENUM('admin','editor','author') NOT NULL DEFAULT 'editor',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Secciones (ej: Comidas Rapidas, Restaurantes)
CREATE TABLE sections (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(120) NOT NULL UNIQUE,
  title VARCHAR(150) NOT NULL,
  description TEXT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Negocios / establecimientos
CREATE TABLE businesses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  section_id INT UNSIGNED NOT NULL,
  name VARCHAR(200) NOT NULL,
  slug VARCHAR(230) NOT NULL UNIQUE,
  short_description VARCHAR(255) NULL,
  description TEXT NULL,
  mission TEXT NULL,
  vision TEXT NULL,
  logo_path VARCHAR(255) NULL, -- ruta al logo (uploads/logos/...)
  website VARCHAR(255) NULL,
  phone VARCHAR(60) NULL,
  address VARCHAR(255) NULL,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  created_by INT UNSIGNED NOT NULL, -- Cambiado a NOT NULL para requerir owner
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT, -- Cambiado a RESTRICT
  INDEX idx_section_id(section_id),
  INDEX idx_created_by(created_by), -- Nuevo índice
  FULLTEXT KEY ft_name_desc (name, short_description, description)
) ENGINE=InnoDB;

-- Imágenes adicionales para cada negocio
CREATE TABLE business_images (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id INT UNSIGNED NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  caption VARCHAR(255) NULL,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  display_order INT NOT NULL DEFAULT 0,
  uploaded_by INT UNSIGNED NULL,
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (business_id) REFERENCES businesses(id) ON DELETE CASCADE,
  FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_business_id(business_id)
) ENGINE=InnoDB;

-- Noticias
CREATE TABLE news (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(300) NOT NULL UNIQUE,
  summary VARCHAR(500) NULL,
  content LONGTEXT NOT NULL,
  published_at DATETIME NULL, -- si NULL => borrador
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  author_id INT UNSIGNED NULL,
  featured_image VARCHAR(255) NULL,
  views INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
  FULLTEXT KEY ft_title_content (title, summary, content),
  INDEX idx_published_at(published_at)
) ENGINE=InnoDB;

-- Imágenes para noticias
CREATE TABLE news_images (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  news_id INT UNSIGNED NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  caption VARCHAR(255) NULL,
  display_order INT NOT NULL DEFAULT 0,
  uploaded_by INT UNSIGNED NULL,
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE,
  FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Registro de auditoría mínimo (opcional)
CREATE TABLE audit_log (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  entity VARCHAR(80) NOT NULL, -- e.g., 'businesses' or 'news'
  entity_id INT UNSIGNED NULL,
  action VARCHAR(60) NOT NULL, -- 'create','update','delete'
  data JSON NULL, -- snapshot opcional
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Insertar datos de ejemplo
INSERT INTO users (username, email, password_hash, full_name, role) VALUES
('admin', 'admin@portal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin'),
('editor1', 'editor@portal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Editor Principal', 'editor');

INSERT INTO sections (slug, title, description, sort_order) VALUES
('comidas-rapidas', 'Comidas Rápidas', 'Establecimientos de comida rápida y delivery', 1),
('restaurantes', 'Restaurantes', 'Restaurantes tradicionales y temáticos', 2),
('cafeterias', 'Cafeterías', 'Cafeterías y establecimientos de café', 3);