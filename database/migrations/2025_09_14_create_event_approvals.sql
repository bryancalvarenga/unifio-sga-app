/* Event Approvals */

CREATE TABLE IF NOT EXISTS event_approvals (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    evento_id INT UNSIGNED NOT NULL,
    aprovado_por INT UNSIGNED NOT NULL, -- usuário (professor, atlética ou admin)
    status ENUM('APROVADO','REJEITADO') NOT NULL,
    justificativa TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_aprovacao_evento FOREIGN KEY (evento_id) REFERENCES events(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_aprovacao_usuario FOREIGN KEY (aprovado_por) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
