<?php

class Monitoria_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function getMonitoriasLista($PERFIL_USUARIO, $id_usuario)
    {

        if ($PERFIL_USUARIO == "Administrador") {
            $result = $this->getMonitoriasADM();
        } elseif ($PERFIL_USUARIO == "Professor") {
            $result = $this->getMonitoriasPROF($id_usuario);
        } elseif ($PERFIL_USUARIO == "Monitor") {
            $result = $this->getMonitoriasMON($id_usuario);
        }

        return $result;
    }

    //retorna a lista de monitorias do semestre ativo nos dias de de semana especificado
    //Ex para recuperar as monitorias nas segundas e tercas: getMonitoriasListaBydia('Segunda-feira|Terça-feira');
    public function getMonitoriasListaBydia($dias_semana)
    {
        $dias_semana = $this->db->escape($dias_semana);
        $dias_semana = substr($dias_semana, 1);
        $dias_semana = substr($dias_semana, 0, -1);

        //recupera os dados do banco de dados
        $where = ' WHERE p.ativo = 1 ';
        if ($dias_semana != '') {
            $where .= " AND dia_semana in ( ";
            $ary = explode('|', $dias_semana);
            foreach ($ary as $key => $val) {
                $where .= " '" . $ary[$key] . "', ";
            }
            $where .= " '')";
        }

        $sql = 'SELECT
                    m.id_monitoria, m.id_disciplina, m.id_professor, m.id_monitor, m.id_periodo,
                    CONCAT(d.codigo, " - ", d.nome) AS nomeDisciplina,d.nome,d.codigo, d.curso, d.campus,
                    CONCAT(p.semestre, "/", p.ano) AS periodo,
                    prof.nome AS professor, mon.nome AS monitor,
                    hm.*, l.*
                FROM
                    monitoria AS m
                    JOIN disciplina AS d USING(id_disciplina)
                    JOIN periodo AS p USING(id_periodo)
                    JOIN horario_monitoria AS hm USING (id_monitoria)
                    JOIN local AS l USING(id_local)
                    LEFT JOIN usuario AS prof ON(prof.id_usuario = m.id_professor)
                    LEFT JOIN usuario AS mon ON(mon.id_usuario = m.id_monitor) ' . $where . 'ORDER BY horario_inicio';

        $Query = $this->db->query($sql);
        $result = $Query->result();


        //ordena as monitorias pelo dia da semana


        usort($result,  function ($a, $b)
        {
            $a->dia_semana = strtoupper($a->dia_semana);
            $b->dia_semana = strtoupper($b->dia_semana);
            $arraySemana = ["SEGUNDA-FEIRA" => 1, "TERCA-FEIRA" => 2, "QUARTA-FEIRA" => 3, "QUINTA-FEIRA" => 4, "SEXTA-FEIRA" => 5, "SÁBADO" => 6, "DOMINGO" => 7];
            @$x = $arraySemana[$a->dia_semana];
            @$y = $arraySemana[$b->dia_semana];
            $w = $a->horario_inicio;
            $z = $b->horario_inicio;
            if ($x == $y) {
                if ($w == $z) {
                    return 0;
                }
                return ($w < $z) ? -1 : 1;
            }
            return ($x < $y) ? -1 : 1;
        });

        return $result;
    }


    public function getMonitoriaById($id_monitoria)
    {
        $id_monitoria = $this->db->escape($id_monitoria);

        //recupera os dados do banco de dados
        $where = " WHERE id_monitoria = " . $id_monitoria;

        $result = $this->getMonitoriasADM($where);

        if ($result == null) {
            return null;
        }

        return $result[0];
    }


    private function getMonitoriasADM($where = "")
    {
        //recupera os dados do banco de dados
        $sql = 'SELECT
				m.id_monitoria, m.id_disciplina, m.id_professor, m.id_monitor, m.id_periodo,m.monitoria_remunerada
				,m.numero_edital, m.data_inicio, m.data_fim,m.plano_aula,
				 m.banco, m.agencia, m.conta, m.cpf,
				CONCAT(d.codigo, " - ", d.nome) AS nomeDisciplina, d.curso, d.campus, d.unidade_academica,
				CONCAT(p.semestre, "/", p.ano) AS periodo,
				prof.nome AS professor, mon.nome AS monitor
			FROM
				monitoria AS m
				JOIN disciplina AS d USING(id_disciplina)
				JOIN periodo AS p USING(id_periodo)
				LEFT JOIN usuario AS prof ON(prof.id_usuario = m.id_professor)
				LEFT JOIN usuario AS mon ON(mon.id_usuario = m.id_monitor) ' . $where;

        $Query = $this->db->query($sql);
        $result = $Query->result();

        return $result;
    }

    private function getMonitoriasPROF($id_usuario)
    {

        //recupera os dados do banco de dados
        $sql = 'SELECT
				m.id_monitoria, m.id_disciplina, m.id_professor, m.id_monitor, m.id_periodo,m.monitoria_remunerada,m.numero_edital, m.data_inicio, m.data_fim,
				 m.banco, m.agencia, m.conta, m.cpf,m.plano_aula,
				CONCAT(d.codigo, " - ", d.nome) AS nomeDisciplina, d.curso, d.campus, d.unidade_academica,
				CONCAT(p.semestre, "/", p.ano) AS periodo,
				prof.nome AS professor, mon.nome AS monitor
			FROM
				monitoria AS m
				JOIN disciplina AS d USING(id_disciplina)
				JOIN periodo AS p USING(id_periodo)
				LEFT JOIN usuario AS prof ON(prof.id_usuario = m.id_professor)
				LEFT JOIN usuario AS mon ON(mon.id_usuario = m.id_monitor) where  p.ativo = 1 AND prof.id_usuario = ' . $id_usuario;

        $Query = $this->db->query($sql);
        $result = $Query->result();

        return $result;
    }

    private function getMonitoriasMON($id_usuario)
    {

        //recupera os dados do banco de dados
        $sql = 'SELECT
				m.id_monitoria, m.id_disciplina, m.id_professor, m.id_monitor, m.id_periodo,m.carga_horaria_aulas,m.numero_edital, m.data_inicio, m.data_fim,
				 m.banco, m.agencia, m.conta, m.cpf,m.plano_aula,
				CONCAT(d.codigo, " - ", d.nome) AS nomeDisciplina, d.curso, d.campus, d.unidade_academica,
				CONCAT(p.semestre, "/", p.ano) AS periodo,
				prof.nome AS professor, mon.nome AS monitor
			FROM
				monitoria AS m
				JOIN disciplina AS d USING(id_disciplina)
				JOIN periodo AS p USING(id_periodo)
				LEFT JOIN usuario AS prof ON(prof.id_usuario = m.id_professor)
				LEFT JOIN usuario AS mon ON(mon.id_usuario = m.id_monitor) where p.ativo = 1 AND mon.id_usuario = ' . $id_usuario;

        $Query = $this->db->query($sql);
        $result = $Query->result();

        return $result;
    }

    public function getMonitoriaHorarios($id_monitoria)
    {
        //recupera os dados do banco de dados
        $sql = "SELECT * FROM `horario_monitoria`JOIN local USING(id_local) WHERE id_monitoria = ? ";
        $Query = $this->db->query($sql, $id_monitoria);
        $result = $Query->result();

        return $result;
    }

    public function getMonitoriaHorarioById($id_horario_monitoria)
    {
        //recupera os dados do banco de dados
        $sql = "SELECT * FROM `horario_monitoria` WHERE id_horario_monitoria = ?";
        $Query = $this->db->query($sql, $id_horario_monitoria);
        $result = $Query->result();

        return $result[0];
    }

    public function getAlunosByMonitoria($id_monitoria)
    {
        //recupera os dados do banco de dados
        $sql = "SELECT DISTINCT a.*, u.matricula, u.nome, count(freq.id_aula) as quant_frequencia
        FROM aluno_monitoria a JOIN usuario u on (a.id_aluno = u.id_usuario)
		LEFT JOIN (SELECT id_aula, id_aluno, id_monitoria FROM `frequencia` JOIN `aula` USING (id_aula)) as freq USING(id_aluno, id_monitoria)
		WHERE a.id_monitoria = ? GROUP BY a.idaluno_monitoria";

        $Query = $this->db->query($sql, $id_monitoria);
        $result = $Query->result();
        return $result;
    }

    public function matriculaAluno($DADOS)
    {
        $idaluno_monitoria = null;
        $this->db->trans_start();
        $this->db->where('idaluno_monitoria', $DADOS['idaluno_monitoria']);
        $q = $this->db->get('aluno_monitoria');

        if ($q->num_rows() > 0) {
            $this->db->where('idaluno_monitoria', $DADOS['idaluno_monitoria']);
            $this->db->update('aluno_monitoria', $DADOS);
            $idaluno_monitoria = $DADOS['idaluno_monitoria'];
        } //caso contrario insere um novo
        else {
            $this->db->insert('aluno_monitoria', $DADOS);
            $idaluno_monitoria = $this->db->insert_id();
        }

        $this->db->trans_complete();

        return $idaluno_monitoria;
    }

    public function desmatriculaAluno($idaluno_monitoria)
    {
        $this->db->where('idaluno_monitoria', $idaluno_monitoria);

        return $this->db->delete('aluno_monitoria');
    }

    public function adicionaEditaHorarioMonitoria($DADOS)
    {
        $id_horario_monitoria = null;
        $this->db->trans_start();
        $this->db->where('id_horario_monitoria', $DADOS['id_horario_monitoria']);
        $q = $this->db->get('horario_monitoria');

        if ($q->num_rows() > 0) {
            $this->db->where('id_horario_monitoria', $DADOS['id_horario_monitoria']);
            $this->db->update('horario_monitoria', $DADOS);
            $id_horario_monitoria = $DADOS['id_horario_monitoria'];
        } //caso contrario insere um novo
        else {
            $this->db->insert('horario_monitoria', $DADOS);
            $id_horario_monitoria = $this->db->insert_id();
        }

        //$this->db->trans_rollback();
        $this->db->trans_complete();

        return $id_horario_monitoria;
    }


    public function adicionaMonitoria($DADOS)
    {
        $id_monitoria = null;
        $this->db->trans_start();
        $this->db->where('id_monitoria', $DADOS['id_monitoria']);
        $q = $this->db->get('monitoria');

        if ($q->num_rows() > 0) {
            $this->db->where('id_monitoria', $DADOS['id_monitoria']);
            $this->db->update('monitoria', $DADOS);
            $id_monitoria = $DADOS['id_monitoria'];
        } //caso contrario insere um novo
        else {
            $this->db->insert('monitoria', $DADOS);
            $id_monitoria = $this->db->insert_id();
        }

        //$this->db->trans_rollback();
        $this->db->trans_complete();

        return $id_monitoria;
    }

    public function excluirHorario($id_horario_monitoria)
    {
        $this->db->where('id_horario_monitoria', $id_horario_monitoria);

        return $this->db->delete('horario_monitoria');
    }

    // Acho que nao está sendo utilizada em nenhum lugar by- Mateus
    public function excluirAluno($id_usuario)
    {
        $this->db->where('id_usuario', $id_usuario);

        return $this->db->delete('usuario');
    }


    public function excluirMonitoria($id_monitoria)
    {
        $this->db->where('id_monitoria', $id_monitoria);

        return $this->db->delete('monitoria');
    }

    public function getAlunosMonitoria($id_monitoria)
    {

        //recupera os dados do banco de dados
        $sql = "SELECT DISTINCT u.matricula, u.nome,u.email, u.perfil,u.id_usuario, a.id_monitoria, a.idaluno_monitoria
 FROM usuario u join aluno_monitoria a on u.id_usuario = a.id_aluno
       join monitoria m on a.id_monitoria = " . $id_monitoria;

        $Query = $this->db->query($sql);
        $result = $Query->result();


        return $result;
    }

    public function verificaEmail($email)
    {

        $this->db->where('email', $email);

        return $this->db->get('usuario')->row_array();
    }

    public function verificaMatricula($matricula)
    {

        $this->db->where('matricula', $matricula);

        return $this->db->get('usuario')->row_array();
    }

    public function verificaIDMonitor($id_usuario, $id_monitoria)
    {

        $sql = "SELECT DISTINCT m.id_monitoria FROM monitoria m join usuario u where m.id_monitor = $id_usuario and m.id_monitoria = $id_monitoria;";
        $Query = $this->db->query($sql, $id_monitoria);
        $result = $Query->result();

        return $result[0];
    }
    public function verificaIDProf($id_usuario, $id_monitoria)
    {

        $sql = "SELECT DISTINCT m.id_monitoria FROM monitoria m join usuario u where m.id_professor = $id_usuario and m.id_monitoria = $id_monitoria;";
        $Query = $this->db->query($sql, $id_monitoria);
        $result = $Query->result();

        return $result[0];
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

    public  function quantMonitoriasRemuneradas(){
        $sql = "SELECT * from usuario u join monitoria m on m.id_professor = u.id_usuario and m.id_monitoria = $id_monitoria ";
        $Query = $this->db->query($sql);
        $result = $Query->result();

        return $result[0];
    }

}
