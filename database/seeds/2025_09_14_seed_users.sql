/* Usuários de teste */
/* SENHA: senha123 */

INSERT INTO users (nome, email, telefone, senha_hash, tipo_participacao)
VALUES 
('Atlética Central', 'atletica@gmail.com', '111111111', '$2y$12$Z1Rn/u5qQf2YBYlZp9cvXeGnWcyfRt.l5YOGEokU9mruFfDyP6P0a', 'ATLETICA'),
('João Professor', 'professor@gmail.com', '222222222', '$2y$12$Z1Rn/u5qQf2YBYlZp9cvXeGnWcyfRt.l5YOGEokU9mruFfDyP6P0a', 'PROFESSOR'),
('Maria Aluna', 'aluna@gmail.com', '333333333', '$2y$12$Z1Rn/u5qQf2YBYlZp9cvXeGnWcyfRt.l5YOGEokU9mruFfDyP6P0a', 'ALUNO'),
('Carlos Comunidade', 'comunidade@gmail.com', '444444444', '$2y$12$Z1Rn/u5qQf2YBYlZp9cvXeGnWcyfRt.l5YOGEokU9mruFfDyP6P0a', 'COMUNIDADE');

INSERT INTO users (nome, email, telefone, senha_hash, tipo_participacao, created_at, updated_at)
VALUES (
    'Coordenação Acadêmica',
    'coordenacao@unifio.edu.br',
    '14999999999',
    -- senha: 123456
    '$2y$12$8XuvcDlI.3Nfwpgh1sphsu8bfVqDceUmFzjTScuWjz/4HPM/UgeNi',
    'COORDENACAO',
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE 
    nome = VALUES(nome),
    telefone = VALUES(telefone),
    updated_at = NOW();
