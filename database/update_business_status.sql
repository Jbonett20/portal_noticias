-- Script para agregar campos de estado a la tabla businesses
USE portal_noticias;

-- Agregar campos para estado del negocio
ALTER TABLE businesses 
ADD COLUMN is_open TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Si el negocio está abierto (1) o cerrado (0)',
ADD COLUMN closed_reason VARCHAR(255) NULL COMMENT 'Razón por la cual está cerrado si is_open = 0',
ADD COLUMN business_hours JSON NULL COMMENT 'Horarios de atención del negocio en formato JSON',
ADD COLUMN status ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active' COMMENT 'Estado general del negocio';

-- Agregar campo business_id a la tabla users para vincular usuario con negocio
ALTER TABLE users 
ADD COLUMN business_id INT UNSIGNED NULL COMMENT 'ID del negocio al que pertenece el usuario',
ADD FOREIGN KEY (business_id) REFERENCES businesses(id) ON DELETE SET NULL;

-- Actualizar datos existentes
-- Vincular usuarios existentes con sus negocios (basado en created_by)
UPDATE users u 
JOIN businesses b ON u.id = b.created_by 
SET u.business_id = b.id 
WHERE u.role IN ('author', 'editor');

-- Insertar horarios de ejemplo
UPDATE businesses SET 
    business_hours = JSON_OBJECT(
        'monday', JSON_OBJECT('open', '08:00', 'close', '22:00', 'is_open', true),
        'tuesday', JSON_OBJECT('open', '08:00', 'close', '22:00', 'is_open', true),
        'wednesday', JSON_OBJECT('open', '08:00', 'close', '22:00', 'is_open', true),
        'thursday', JSON_OBJECT('open', '08:00', 'close', '22:00', 'is_open', true),
        'friday', JSON_OBJECT('open', '08:00', 'close', '23:00', 'is_open', true),
        'saturday', JSON_OBJECT('open', '09:00', 'close', '23:00', 'is_open', true),
        'sunday', JSON_OBJECT('open', '10:00', 'close', '21:00', 'is_open', true)
    )
WHERE business_hours IS NULL;

-- Agregar algunos casos de prueba
UPDATE businesses SET is_open = 0, closed_reason = 'Renovaciones en el local' WHERE id = 2;
UPDATE businesses SET status = 'inactive' WHERE id = 3;