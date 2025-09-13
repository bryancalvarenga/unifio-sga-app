<?php

namespace App\Models;

use PDO;
use PDOException;

/*
- Classe Event
- Representa um evento no sistema e fornece métodos para interagir com a tabela 'events' do DB
*/
class Event {
    private $db; // Conexão DB

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Cria um novo evento
    public function create($usuario_id, $categoria, $subtipo_esportivo, $subtipo_nao_esportivo, $finalidade, $data_evento, $periodo, $aberto_ao_publico, $estimativa_participantes, $materiais_necessarios, $usa_materiais_instituicao, $responsavel, $arbitro = null, $lista_participantes_path = null, $observacoes = null) {

        try {
            $sql = "INSERT INTO events 
                (usuario_id, categoria, subtipo_esportivo, subtipo_nao_esportivo, finalidade, 
                data_evento, periodo, aberto_ao_publico, estimativa_participantes, 
                materiais_necessarios, usa_materiais_instituicao, responsavel, 
                arbitro, lista_participantes_path, observacoes)
                VALUES
                (:usuario_id, :categoria, :subtipo_esportivo, :subtipo_nao_esportivo, :finalidade, 
                :data_evento, :periodo, :aberto_ao_publico, :estimativa_participantes, 
                :materiais_necessarios, :usa_materiais_instituicao, :responsavel, 
                :arbitro, :lista_participantes_path, :observacoes)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':subtipo_esportivo', $subtipo_esportivo);
            $stmt->bindParam(':subtipo_nao_esportivo', $subtipo_nao_esportivo);
            $stmt->bindParam(':finalidade', $finalidade);
            $stmt->bindParam(':data_evento', $data_evento);
            $stmt->bindParam(':periodo', $periodo);
            $stmt->bindParam(':aberto_ao_publico', $aberto_ao_publico);
            $stmt->bindParam(':estimativa_participantes', $estimativa_participantes);
            $stmt->bindParam(':materiais_necessarios', $materiais_necessarios);
            $stmt->bindParam(':usa_materiais_instituicao', $usa_materiais_instituicao);
            $stmt->bindParam(':responsavel', $responsavel);
            $stmt->bindParam(':arbitro', $arbitro);
            $stmt->bindParam(':lista_participantes_path', $lista_participantes_path);
            $stmt->bindParam(':observacoes', $observacoes);

            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                die("Já existe um evento cadastrado para essa data e período.");
            } die ("Erro no create(): " . $e->getMessage());
        }
    }
    
    // Lista todos os eventos
        public function getAll(){
        $sql = "SELECT * FROM events ORDER BY data_evento DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Busca um evento pelo ID
    public function findById($id){
        $sql = "SELECT * FROM events WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualiza um evento existente
    public function update($id, $dados) {
        $sql = "UPDATE events SET 
            categoria = :categoria,
            subtipo_esportivo = :subtipo_esportivo,
            subtipo_nao_esportivo = :subtipo_nao_esportivo,
            finalidade = :finalidade,
            data_evento = :data_evento,
            periodo = :periodo,
            aberto_ao_publico = :aberto_ao_publico,
            estimativa_participantes = :estimativa_participantes,
            materiais_necessarios = :materiais_necessarios,
            usa_materiais_instituicao = :usa_materiais_instituicao,
            responsavel = :responsavel,
            arbitro = :arbitro,
            lista_participantes_path = :lista_participantes_path,
            observacoes = :observacoes,
            status = :status
            WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array_merge($dados, ['id' => $id]));
    }

    // Exclui um evento pelo ID
    public function delete($id) {
        $sql = "DELETE FROM events WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}