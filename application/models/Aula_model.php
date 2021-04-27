<?php

class Aula_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function getAulaById($id_aula)
    {
        //recupera as informacoes das aulas de uma monitoria.
        //� realizado um join com a tabela frequencia para saber a quantidade de alunos que foram em uma determinada aula
        $sql = "SELECT a.*, l.*, count(*) as quant_alunos FROM aula a LEFT JOIN frequencia f USING(id_aula)
        LEFT JOIN local as l USING(id_local) WHERE a.id_aula = ? GROUP BY a.id_aula";
        $Query = $this->db->query($sql, array($id_aula));
        $result = $Query->result();

        return $result[0];
    }

    public function getAulasByMonitoria($id_monitoria)
    {
        //recupera as informacoes das aulas de uma monitoria.
        //� realizado um join com a tabela frequencia para saber a quantidade de alunos que foram em uma determinada aula
        $sql = "SELECT a.*, l.*, count(f.id_frequencia) as quant_alunos FROM aula a
LEFT JOIN frequencia f USING(id_aula) LEFT JOIN local as l USING(id_local) WHERE a.id_monitoria = ? GROUP BY a.id_aula";
        $Query = $this->db->query($sql, array($id_monitoria));
        $result = $Query->result();

        return $result;
    }

    public function getReuniaoByMonitoria($id_monitoria)
    {
        $sql = "SELECT * FROM atividade a where a.id_monitoria =  " . $id_monitoria;
        $Query = $this->db->query($sql, array($id_monitoria));
        $result = $Query->result();

        return $result;
    }

    public function getReuniaoById($id_atividade)
    {
        $sql = "SELECT * FROM atividade a where a.id_atividade =  " . $id_atividade;
        $Query = $this->db->query($sql, array($id_atividade));
        $result = $Query->result();

        return $result[0];
    }

    public function getAulasByMonitoriaFrequencia($id_aula)
    {
        $sql = "SELECT * FROM usuario u join aluno_monitoria a on u.id_usuario = a.id_aluno
        join monitoria m on a.id_monitoria = m.id_monitoria join aula au on au.id_aula = " . $id_aula;
        $Query = $this->db->query($sql, array($id_aula));
        $result = $Query->result();

        return $result;
    }

    public function getAlunosSemFrequenciaNaAula($id_aula)
    {
        //mudar nome getAlunosSemFrequenciaNaAula
        $sql = "SELECT DISTINCT * from aluno_monitoria a join usuario u on a.id_aluno = u.id_usuario
join aula au USING(id_monitoria) left join frequencia fr on (a.id_aluno = fr.id_aluno
 and au.id_aula = fr.id_aula) WHERE au.id_aula = ?  and fr.id_frequencia IS NULL";

        $Query = $this->db->query($sql, array($id_aula));
        $result = $Query->result();

        return $result;
    }

    public function adicionaEditaAulaMonitoria($DADOS)
    {
        $id_aula = null;
        $this->db->trans_start();
        $this->db->where('id_aula', $DADOS['id_aula']);
        $q = $this->db->get('aula');

        if ($q->num_rows() > 0) {
            $this->db->where('id_aula', $DADOS['id_aula']);
            $this->db->update('aula', $DADOS);
            $id_aula = $DADOS['id_aula'];
        } //caso contrario insere um novo
        else {
            $this->db->insert('aula', $DADOS);
            $id_aula = $this->db->insert_id();
        }

        //$this->db->trans_rollback();
        $this->db->trans_complete();

        return $id_aula;
    }

    public function adicionaEditaReuniaoMonitoria($DADOS)
    {
        $id_atividade = null;
        $this->db->trans_start();
        $this->db->where('id_atividade', $DADOS['id_atividade']);
        $q = $this->db->get('atividade');

        if ($q->num_rows() > 0) {
            $this->db->where('id_atividade', $DADOS['id_atividade']);
            $this->db->update('atividade', $DADOS);
            $id_atividade = $DADOS['id_atividade'];
        } //caso contrario insere um novo
        else {
            $this->db->insert('atividade', $DADOS);
            $id_atividade = $this->db->insert_id();
        }

        //$this->db->trans_rollback();
        $this->db->trans_complete();

        return $id_atividade;
    }

    public function excluir_Aula($id_aula)
    {

        $this->db->where('id_aula', $id_aula);

        return $this->db->delete('aula');
    }

    public function excluir_Reuniao($id_atividade)
    {

        $this->db->where('id_atividade', $id_atividade);

        return $this->db->delete('atividade');
    }

    public function somatorioCargaHoraria($id_monitoria)
    {

        $sql = "SELECT SEC_TO_TIME (SUM(carga_horaria)) as carga_horaria
        from ( SELECT SUM( time_to_sec(TIMEDIFF(horario_fim,horario_inicio) )) AS carga_horaria
        FROM aula WHERE id_monitoria = $id_monitoria UNION SELECT  SUM( time_to_sec(TIMEDIFF(horario_fim,horario_inicio) )) AS carga_horaria
        FROM atividade WHERE id_monitoria =  $id_monitoria ) as combinacao";

        $Query = $this->db->query($sql, array($id_monitoria));
        $result = $Query->result();
        return $result[0];
    }

    public function somatorioCargaHorariaAtestadoFrequencia($id_monitoria, $data_inicio, $data_fim)
    {

        $sql = 'SELECT SEC_TO_TIME(SUM(duracao_sec)) as total_tempo_todas_atividades  FROM
        (
        SELECT id_monitoria, `data` as data_atividade, horario_inicio, horario_fim, (TIME_TO_SEC(horario_fim)-TIME_TO_SEC(horario_inicio)) as duracao_sec FROM `atividade`
        UNION
        SELECT id_monitoria, `data` as data_atividade, horario_inicio, horario_fim, (TIME_TO_SEC(horario_fim)-TIME_TO_SEC(horario_inicio)) as duracao_sec FROM `aula`
        ) as todas_atividades
        WHERE
        id_monitoria ='.$id_monitoria.'
        AND  data_atividade >= "'.$data_inicio.'"
        AND data_atividade <= "'.$data_fim.'"
        GROUP BY
        id_monitoria';
        //var_dump($sql);
        $Query = $this->db->query($sql);
        $result = $Query->result();
        if( count($result) > 0 )
            return $result[0]->total_tempo_todas_atividades;
        else
            return "00:00:00";
    }

    public function somatorioHorarioReuniao($id_monitoria)
    {

        $sql = "SELECT SEC_TO_TIME( SUM( time_to_sec(TIMEDIFF(horario_fim,horario_inicio) ))) AS horario_reuniao
        FROM atividade WHERE id_monitoria = " . $id_monitoria;

        $Query = $this->db->query($sql, array($id_monitoria));
        $result = $Query->result();
        return $result[0];
    }

    public function somatorioHorarioReuniaoAtestadoFrequencia($id_monitoria, $data_inicio, $data_fim)
    {

        $sql = 'SELECT id_monitoria, SEC_TO_TIME(SUM(duracao_sec)) as total_tempo_atividades  FROM
        (SELECT id_monitoria, `data` as data_atividade, horario_inicio, horario_fim, (TIME_TO_SEC(horario_fim)-TIME_TO_SEC(horario_inicio)) as duracao_sec FROM `atividade`) as todas_atividades
        WHERE
        id_monitoria ='.$id_monitoria.'
        AND  data_atividade >= "'.$data_inicio.'"
        AND data_atividade <= "'.$data_fim.'"
        GROUP BY
        id_monitoria';
        //var_dump($sql);
        $Query = $this->db->query($sql);
        $result = $Query->result();
        if( count($result) > 0 )
            return $result[0]->total_tempo_atividades;
        else
            return "00:00:00";
    }


    public function somatorioHorarioAulaAtestadoFrequencia($id_monitoria, $data_inicio, $data_fim)
    {
        $sql = 'SELECT id_monitoria, SEC_TO_TIME(SUM(duracao_sec)) as total_tempo_aulas  FROM
        (SELECT id_monitoria, `data` as data_atividade, horario_inicio, horario_fim, (TIME_TO_SEC(horario_fim)-TIME_TO_SEC(horario_inicio)) as duracao_sec FROM `aula`) as todas_atividades
        WHERE
        id_monitoria ='.$id_monitoria.'
        AND  data_atividade >= "'.$data_inicio.'"
        AND data_atividade <= "'.$data_fim.'"
        GROUP BY
        id_monitoria';
        //var_dump($sql);
        $Query = $this->db->query($sql);
        $result = $Query->result();
        if( count($result) > 0 )
            return $result[0]->total_tempo_aulas;
        else
            return "00:00:00";
    }


    public function somatorioHorarioAula($id_monitoria)
    {

        $sql = "SELECT SEC_TO_TIME( SUM( time_to_sec(TIMEDIFF(horario_fim,horario_inicio) ))) AS horario_aula
        FROM aula WHERE id_monitoria = " . $id_monitoria;

        $Query = $this->db->query($sql, array($id_monitoria));
        $result = $Query->result();
        return $result[0];
    }

    //Verifica local cadastrado na tabela Aula
    public function getVerificaLocal($id_local)
    {
        $sql = "select a.id_aula from aula a where a.id_local = $id_local;";
        $Query = $this->db->query($sql, $id_local);
        $result = $Query->result();

        return $result;
    }


    public function getRelatorioPlanilha($id_monitoria)
    {
//        $sql = "SELECT DISTINCT u.id_usuario, u.nome, u.matricula,
//                d.unidade_academica, m.banco,m.agencia, m.conta,m.cpf,
//                CONCAT(DATE_FORMAT(af.data_inicio, '%d/%m/%Y'), \" a \", DATE_FORMAT(af.data_fim, '%d/%m/%Y')) AS data,
//                DATEDIFF(af.data_fim, af.data_inicio) as diferencaData,
//                (SELECT DISTINCT  TIME_FORMAT(SUM(carga_horaria), '%h') as carga_horaria
//                from ( SELECT DISTINCT  SUM(TIMEDIFF(horario_fim,horario_inicio))as carga_horaria
//                from atividade  where id_monitoria = $id_monitoria UNION SELECT DISTINCT SUM(TIMEDIFF(horario_fim,horario_inicio)) as carga_horaria
//                from aula  where id_monitoria = $id_monitoria ) as uniao) as somatorioHorario
//                from usuario u join monitoria m join disciplina d
//                join atestado_frequencia af join aula au join atividade at
//                where u.id_usuario = m.id_monitor and m.id_monitoria = $id_monitoria and m.monitoria_remunerada = 'Sim';";
//
        $sql = "SELECT DISTINCT u.id_usuario, u.nome, u.matricula,m.cpf,d.unidade_academica, m.banco,m.agencia, m.conta,
                CONCAT(DATE_FORMAT(af.data_inicio, '%d/%m/%Y'), 'a', DATE_FORMAT(af.data_fim, '%d/%m/%Y')) AS data,
                DATEDIFF(af.data_fim, af.data_inicio) as diferencaData,
                (SELECT DISTINCT  TIME_FORMAT(SUM(carga_horaria), '%h') as carga_horaria
                from ( SELECT DISTINCT  SUM(TIMEDIFF(horario_fim,horario_inicio))as carga_horaria
                from atividade   UNION SELECT DISTINCT SUM(TIMEDIFF(horario_fim,horario_inicio)) as carga_horaria
                from aula  ) as uniao) as somatorioHorario
                from usuario u join monitoria m join disciplina d
                join atestado_frequencia af join aula au join atividade at
                where u.id_usuario = m.id_monitor  and m.monitoria_remunerada = 'Sim';";



        $Query = $this->db->query($sql, $id_monitoria);
        $result = $Query->result();

        return $result;

    }

    public function getSomatorioAtividade()
    {
        $sql = "SELECT DISTINCT m.id_monitoria,at.id_monitoria , TIME_FORMAT(SUM(TIMEDIFF(at.horario_fim,at.horario_inicio)), '%h') as somatorio
                from atividade at join monitoria m
                where m.id_monitoria = at.id_monitoria
                GROUP by m.id_monitoria,at.id_monitoria";


        $Query = $this->db->query($sql);
        $result = $Query->result();

        return $result;

    }

    public function getSomatorioAula()
    {
        $sql = "SELECT DISTINCT m.id_monitoria,a.id_monitoria , TIME_FORMAT(SUM(TIMEDIFF(a.horario_fim,a.horario_inicio)), '%h') as somatorio
                from aula a join monitoria m
                where m.id_monitoria = a.id_monitoria
                GROUP by m.id_monitoria,a.id_monitoria";


        $Query = $this->db->query($sql);
        $result = $Query->result();

        return $result;

    }


    public function getAlunoDadosBancarios()
    {
        $sql = "SELECT DISTINCT u.id_usuario, u.nome, u.matricula,m.cpf,d.unidade_academica, m.banco,m.agencia, m.conta,
                CONCAT(DATE_FORMAT(af.data_inicio, '%d/%m/%Y'), ' - ', DATE_FORMAT(af.data_fim, '%d/%m/%Y')) AS data,
                DATEDIFF(af.data_fim, af.data_inicio) as diferencaData

                from usuario u join monitoria m join disciplina d
                join atestado_frequencia af join aula au join atividade at
                where u.id_usuario = m.id_monitor and m.monitoria_remunerada = 'Sim'";


        $Query = $this->db->query($sql);
        $result = $Query->result();

        return $result;

    }
}
