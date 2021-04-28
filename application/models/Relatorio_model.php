<?php

class Relatorio_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }



    public function verificaFrenquencia($ida_aluno_mnnitoria)
    {
        $sql = "select f.id_frequencia from aluno_monitoria a
        join frequencia  f JOIN usuario u where a.idaluno_monitoria = $ida_aluno_mnnitoria
        and f.id_aluno=a.id_aluno;";
        $Query = $this->db->query($sql, $ida_aluno_mnnitoria);
        $result = $Query->result();

        return $result;
    }
//Função para os monitores e professores estão inseridos em algum monitoria
    function verificaExclusaoUsuarioMonitoria($id_usuario)
    {
        $sql = "Select * from usuario join monitoria m join aluno_monitoria a  where $id_usuario = m.id_monitor
        or $id_usuario = m.id_professor or  $id_usuario = a.id_aluno ";
        $Query = $this->db->query($sql, $id_usuario);
        $result = $Query->result();

        return $result;
    }

    public function profMonitoria($id_monitoria){
        $sql = "SELECT * from usuario u join monitoria m on m.id_professor = u.id_usuario and m.id_monitoria = $id_monitoria ";
        $Query = $this->db->query($sql);
        $result = $Query->result();

        return $result[0];
    }

    public function teste($id_monitoria){
        $sql = "select distinct d.unidade_academica, u.nome as professor from monitoria m join disciplina d join usuario u
where m.id_monitoria = $id_monitoria and m.id_disciplina = d.id_disciplina and u.perfil= 'professor' and u.id_usuario = m.id_professor";
        $Query = $this->db->query($sql);
        $result = $Query->result();

        return $result[0];
    }

    public function alunoFrequencia($id_monitoria){

               $sql = "select distinct cadastrado, u.nome from frequencia f join usuario u
                join aluno_monitoria a where a.id_monitoria = $id_monitoria and
                f.id_aluno = u.id_usuario;";

        $Query = $this->db->query($sql);
        $result = $Query->result();

        return $result;

    }


    public function alunoNome($id_monitoria){
        $sql = "select DISTINCT a.data, u.nome from aula a join frequencia f join usuario u where a.id_aula = f.id_aula
and f.id_aluno = u.id_usuario and a.id_monitoria = $id_monitoria";
        $Query = $this->db->query($sql);
        $result = $Query->result();

        return $result;

    }


    public function getAtestadoFrequencia()
    {
        //recupera as informações da tabala atestado_frequencia
        $sql = "SELECT * FROM atestado_frequencia";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }


    //Função para apresentar todas infomações da tabela atestado_frequencia + semestre e ano da tabela periodo
    public function infoAtestadoFrequencia($id_monitoria){
        $sql ="SELECT DISTINCT a.id_atestado_frequencia,m.id_monitoria,m.id_disciplina, a.data_inicio, a.data_fim, p.semestre, p.ano
FROM atestado_frequencia a join periodo p join monitoria m where a.id_periodo = p.id_periodo
and  m.id_monitoria = $id_monitoria and p.ativo = 1;";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    public function listarAtestadoFrequencia(){
        $sql ="SELECT id_atestado_frequencia, data_inicio, data_fim, p.semestre, p.ano
FROM atestado_frequencia a join periodo p where a.id_periodo = p.id_periodo";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }


    public function getRelatorioById($id_atestado_frequencia)
    {
        $idcliente = $this->db->escape($id_atestado_frequencia);

        //recupera os dados do banco de dados
        $sql = "SELECT * FROM atestado_frequencia WHERE id_atestado_frequencia = ?";
        $Query = $this->db->query($sql, $id_atestado_frequencia);
        $result = $Query->result();

        if ($result == null) {
            return null;
        }

        return $result[0];
    }

    public function adicionaAtestadoFrequencia($DADOS){

        //abrindo transacao
        $this->db->trans_start();

        //tenta recuperar o atestado de frequencia
        $this->db->where('id_atestado_frequencia', $DADOS['id_atestado_frequencia']);
        $q = $this->db->get('atestado_frequencia');

        //verifica se existe o atestado de frequencia, caso exista atualiza
        if ($q->num_rows() > 0) {
            $this->db->where('id_atestado_frequencia', $DADOS['id_atestado_frequencia']);
            $this->db->update('atestado_frequencia', $DADOS);
            $id_periodo = $DADOS['id_atestado_frequencia'];
        }
        //caso contrario insere um novo
        else {
            $this->db->insert('atestado_frequencia', $DADOS);
            $id_atestado_frequencia = $this->db->insert_id();
        }

        //$this->db->trans_rollback();
        $this->db->trans_complete();

        return $id_atestado_frequencia;
    }

    function excluirAtestadoFrequencia($id_atestado_frequencia)
    {

        $this->db->where('id_atestado_frequencia', $id_atestado_frequencia);

        return $this->db->delete('atestado_frequencia');
    }

    public function  getContagemFrequencia($id_monitoria){

        $sql = "select a.data , COUNT(f.id_frequencia) as quant from frequencia f join aula a where a.id_aula =f.id_aula and a.id_monitoria = $id_monitoria GROUP by a.data;";
        $query = $this->db->query($sql);
        $result = $query->result();

        return($result);

    }

    public function  getContagemCargaHoraria($id_monitoria, $data_inicio, $data_fim){

        $sql = "(
        SELECT id_monitoria, 'Monitoria' as tipo, `data`,  atividades as descrição, SEC_TO_TIME(TIME_TO_SEC(horario_fim)-TIME_TO_SEC(horario_inicio)) as duracao, COUNT(id_aluno) as quant_alunos FROM `aula`
        LEFT JOIN frequencia using(id_aula)
        WHERE
        id_monitoria = $id_monitoria
        AND DATA >= '$data_inicio'
        AND DATA <= '$data_fim'
        GROUP BY
        `data`, horario_fim, descrição, horario_inicio, id_monitoria, duracao
        )
        UNION
        (
        SELECT id_monitoria, 'Atividade' as tipo, `data`, descricao, SEC_TO_TIME(TIME_TO_SEC(horario_fim)-TIME_TO_SEC(horario_inicio)) as duracao, '-' as quant_alunos FROM `atividade`
        WHERE
        id_monitoria = $id_monitoria
        AND DATA >= '$data_inicio'
        AND DATA <= '$data_fim'
        )";
        $query = $this->db->query($sql);
        $result = $query->result();

        return($result);
    }


    //Função para mostrar data inicio e fim para colocar dentro do relatório de atestado de frequência
    public function dataInicioFim($id_atestado_frequencia){
        $sql ="select id_atestado_frequencia, data_inicio, data_fim from atestado_frequencia where id_atestado_frequencia = $id_atestado_frequencia";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result[0];
    }

    public function getAtestadoById($id_monitoria)
    {
        //recupera os dados do banco de dados
        $sql = "select DISTINCT * from monitoria m join periodo  p join atestado_frequencia af
where m.id_periodo = p.id_periodo and p.id_periodo = af.id_periodo and m.id_monitoria = $id_monitoria;";
        $Query = $this->db->query($sql,array($id_monitoria) );
        $result = $Query->result();

        return $result;
    }

    public function listaAtestadoFinal(){
        //recupera os dados do banco de dados
        $sql = 'SELECT
				m.id_monitoria, m.id_disciplina, m.id_professor, m.id_monitor, m.id_periodo,m.monitoria_remunerada,
				 m.carga_horaria, m.carga_horaria_aulas,m.numero_edital, m.data_inicio, m.data_fim,
				 m.banco, m.agencia, m.conta, m.cpf,
				CONCAT(d.codigo, " - ", d.nome) AS nomeDisciplina, d.curso, d.campus, d.unidade_academica,
				CONCAT(p.semestre, "/", p.ano) AS periodo,
				prof.nome AS professor, mon.nome AS monitor
			FROM
				monitoria AS m
				JOIN disciplina AS d USING(id_disciplina)
				JOIN periodo AS p USING(id_periodo)
				LEFT JOIN usuario AS prof ON(prof.id_usuario = m.id_professor)
				LEFT JOIN usuario AS mon ON(mon.id_usuario = m.id_monitor) ';

        $Query = $this->db->query($sql);
        $result = $Query->result();

        return $result;
    }
}
