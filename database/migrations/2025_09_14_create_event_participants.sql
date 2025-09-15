/* Event Participants */

CREATE TABLE IF NOT EXISTS event_participants (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    evento_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    status ENUM('INSCRITO','CANCELADO','CONFIRMADO') DEFAULT 'INSCRITO',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_participante_evento FOREIGN KEY (evento_id) REFERENCES events(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_participante_usuario FOREIGN KEY (usuario_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    UNIQUE KEY uniq_evento_usuario (evento_id, usuario_id) -- evita duplicidade
) ENGINE=InnoDB;
