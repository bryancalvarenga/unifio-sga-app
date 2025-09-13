/* Events */

CREATE TABLE IF NOT EXISTS events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL, 
    categoria ENUM('ESPORTIVO', 'NAO_ESPORTIVO') NOT NULL,
    subtipo_esportivo ENUM('FUTSAL','VOLEI', 'BASQUETE') NULL,
    subtipo_nao_esportivo ENUM('PALESTRA','WORKSHOP','FORMATURA') NULL,
    finalidade ENUM('TREINO','CAMPEONATO','OUTRO') NULL,
    data_evento DATE NOT NULL,
    periodo ENUM('P1','P2') NOT NULL, -- P1: 19:15-20:55, P2: 21:10-22:50
    aberto_ao_publico TINYINT(1) DEFAULT 0,
    estimativa_participantes INT NULL,
    materiais_necessarios TEXT NULL,
    usa_materiais_instituicao TINYINT(1) DEFAULT 0,
    responsavel VARCHAR(120) NOT NULL,
    arbitro VARCHAR(120) NULL,
    lista_participantes_path VARCHAR(255) NULL,
    observacoes TEXT NULL,
    status ENUM('AGENDADO','APROVADO','REJEITADO','CANCELADO','FINALIZADO') DEFAULT 'AGENDADO',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_evento_usuario FOREIGN KEY (usuario_id) REFERENCES users(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uniq_data_periodo (data_evento, periodo) -- trava de calendário    
) ENGINE=InnoDB;