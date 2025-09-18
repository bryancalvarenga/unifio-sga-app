-- Migration: Criar tabela de presença
-- Arquivo: 2025_09_18_create_presence.sql

CREATE TABLE IF NOT EXISTS presence (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    evento_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Evita duplicar presença do mesmo usuário no mesmo evento
    UNIQUE KEY uniq_presence (evento_id, usuario_id),

    -- Chaves estrangeiras
    CONSTRAINT fk_presence_evento FOREIGN KEY (evento_id) 
        REFERENCES events(id) ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_presence_usuario FOREIGN KEY (usuario_id) 
        REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
