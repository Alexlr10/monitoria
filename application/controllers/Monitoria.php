<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoria extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Usuario_model', 'Usuario_model');
        $this->load->model('Disciplina_model', 'Disciplina_model');
        $this->load->model('Monitoria_model', 'Monitoria_model');
        $this->load->model('Periodo_model', 'Periodo_model');
        $this->load->model('Frequencia_model', 'Frequencia_model');
        $this->load->model('Relatorio_model', 'Relatorio_model');
        $this->load->model('Aula_model', 'Aula_model');
        $this->load->model('Local_model', 'Local_model');
        $this->load->model('Util_model', 'Util');
        $this->load->helper('date');
    }

    public function index()
    {
    }


    function listar_view()
    {
        //recupera os periodos
        $id_usuario = $this->session->userdata('id_usuario');
        $PERFIL_USUARIO = $this->session->userdata('perfil');
            $DATA['monitorias'] = $this->Monitoria_model->getMonitoriasLista($PERFIL_USUARIO, $id_usuario);

            $this->load->view('monitoria_listar', $DATA);
        }



    function aluno_listar_view($id_monitoria)
    {
        $ID_USUARIO = $this->session->userdata('id_usuario');
        $PERFIL_USUARIO = $this->session->userdata('perfil');
        if (@$this->Monitoria_model->verificaIDMonitor($ID_USUARIO, $id_monitoria) or @$this->Monitoria_model->verificaIDProf($ID_USUARIO, $id_monitoria) or $PERFIL_USUARIO == "Administrador") {
            //recupera os periodos
            $DATA['alunos'] = $this->Usuario_model->getAlunoMatricula($id_monitoria);
            $DATA['matriculados'] = $this->Monitoria_model->getAlunosMonitoria($id_monitoria);
            $DATA['monitoria'] = $this->Monitoria_model->getMonitoriaById($id_monitoria);
            $this->load->view('alunos_listar', $DATA);
        } else {
            $this->Util->telaResultado($this, "Acesso negado.", true);
        }
    }

    function frequencia_listar_view($id_monitoria, $id_aula)
    {
        $ID_USUARIO = $this->session->userdata('id_usuario');
        $PERFIL_USUARIO = $this->session->userdata('perfil');
        if (@$this->Monitoria_model->verificaIDMonitor($ID_USUARIO, $id_monitoria) or @$this->Monitoria_model->verificaIDProf($ID_USUARIO, $id_monitoria) or $PERFIL_USUARIO == "Administrador")
        {
            $DATA['aula'] = $this->Aula_model->getAulaById($id_aula);
            $DATA['frequencias'] = $this->Frequencia_model->getFrequenciasByAula($id_aula);
            $DATA['matriculados'] = $this->Aula_model->getAlunosSemFrequenciaNaAula($id_aula);
            $this->load->view('frequencia_edit', $DATA);
        }else{
            $this->Util->telaResultado($this, "Acesso negado.", true);
        }
    }

    function gerenciar($id_monitoria)
    {
        $ID_USUARIO = $this->session->userdata('id_usuario');
        $PERFIL_USUARIO = $this->session->userdata('perfil');


        if (@$this->Monitoria_model->verificaIDMonitor($ID_USUARIO, $id_monitoria) or @$this->Monitoria_model->verificaIDProf($ID_USUARIO, $id_monitoria) or $PERFIL_USUARIO == "Administrador") {
            //recupera os periodos
            $DATA['monitoria'] = $this->Monitoria_model->getMonitoriaById($id_monitoria);
            $DATA['aulas'] = $this->Aula_model->getAulasByMonitoria($id_monitoria);
            $DATA['reuniao'] = $this->Aula_model->getReuniaoByMonitoria($id_monitoria);
            $DATA['horarios'] = $this->Monitoria_model->getMonitoriaHorarios($id_monitoria);
            $DATA['alunos'] = $this->Monitoria_model->getAlunosByMonitoria($id_monitoria);
            $DATA['atestados'] = $this->Relatorio_model->infoAtestadoFrequencia($id_monitoria);


            $DATA['cargaHoraria'] = $this->Aula_model->somatorioCargaHoraria($id_monitoria);

            $DATA['somatorioAula'] = $this->Aula_model->somatorioHorarioAula($id_monitoria);
            $DATA['somatorioReuniao'] = $this->Aula_model->somatorioHorarioReuniao($id_monitoria);


            //$DATA['idAtestado'] = $this->Relatorio_model->getAtestadoById(13);

            $this->load->view('monitoria_dados', $DATA);
        } else {
            $this->Util->telaResultado($this, "Acesso negado.", true);
        }
    }

    function desmatricular_matricular_aluno($id_monitoria, $idaluno_monitoria)
    {

        if (!$DATA['FRENQUENCIA']=$this->Monitoria_model->verificaFrenquencia($idaluno_monitoria)) {
            if ($this->Monitoria_model->desmatriculaAluno($idaluno_monitoria) != 0) {
                redirect('Monitoria/aluno_listar_view/' . $id_monitoria, 'refresh');
            } else {
                $this->Util->telaResultado($this, "Não foi possivel atualizar os dados. Confira os dados informados e se n�o existe um períoodo ativo ou já cadastrado.", true);
            }
        } else {
            $this->Util->telaResultado($this, "O aluno tem frenquencia cadastrada.", true);
        }
    }

    function matricular_aluno($id_monitoria, $id_aluno)
    {
        $DATA['idaluno_monitoria'] = null;
        $DATA['id_aluno'] = $id_aluno;
        $DATA['id_monitoria'] = $id_monitoria;


        // var_dump($DATA);
        if ($this->Monitoria_model->matriculaAluno($DATA) != 0) {
            redirect('Monitoria/aluno_listar_view/'.$id_monitoria, 'refresh');
        } else {
            $this->Util->telaResultado($this, "Não foi possivel atualizar os dados. Confira os dados informados e se não existe um período ativo ou já cadastrado.", true);
        }
    }

    function aula_editar_view($id_monitoria, $id_aula)
    {

        $ID_USUARIO = $this->session->userdata('id_usuario');
        $PERFIL_USUARIO = $this->session->userdata('perfil');
        if (@$this->Monitoria_model->verificaIDMonitor($ID_USUARIO, $id_monitoria) or @$this->Monitoria_model->verificaIDProf($ID_USUARIO, $id_monitoria) or $PERFIL_USUARIO == "Administrador") {
            $DATA['locais'] = $this->Local_model->getLocais();
            $DATA['frequencias'] = $this->Frequencia_model->getFrequenciasByAula($id_aula);
            $DATA['matriculados'] = $this->Aula_model->getAlunosSemFrequenciaNaAula($id_aula);

        //Prepara para inserir uma nova aula
            if ($id_aula == 'novo') {
                $aula = new stdClass();
                $aula->id_aula = "";
                $aula->id_monitoria = $id_monitoria;
                $aula->data = "";
                $aula->horario_inicio = "";
                $aula->horario_fim = "";
                $aula->atividades = "";
                $aula->id_local = "";
                $aula->cadastrado = "";
                $aula->atualizado = "";
                $DATA['aula'] = $aula;

                //carregar html auxiliar
                $this->load->view('aula_edit2', $DATA);
            } //recupera as informacoes da aula para editar
            else {
                $DATA['monitoria'] = $this->Monitoria_model->getMonitoriaById($id_monitoria);
                $DATA['aula'] = $this->Aula_model->getAulaById($id_aula);
                $DATA['matriculados'] = $this->Aula_model->getAlunosSemFrequenciaNaAula($id_aula);
                //carregar html completo com frequencia
                $this->load->view('aula_edit2', $DATA);

                if ($DATA['aula'] == null) {
                    $this->Util->telaResultado($this, "Entrada Invalida!", true);
                }
            }
        } else {
            $this->Util->telaResultado($this, "Acesso negado.", true);
        }
    }

    function aula_editar($id_monitoria)
    {

        $DATA['id_aula'] = $this->input->post('id_aula');
        $DATA['id_monitoria'] = $id_monitoria;
        $DATA['id_local'] = $this->input->post('id_local');
        $DATA['data'] = $this->input->post('data');
        $DATA['horario_inicio'] = $this->input->post('horario_inicio');
        $DATA['horario_fim'] = $this->input->post('horario_fim');
        $DATA['atividades'] = $this->input->post('atividades');
        $PERFIL_USUARIO = $this->session->userdata('perfil');

        //Se for Monitor não será possivel fazer cadastro de atividade aula após 5 dias que foi executada
        if ($PERFIL_USUARIO == "Monitor" or $PERFIL_USUARIO == "Professor") {
            $diaAtual = date('Y/m/d', strtotime('today'));
            $somaDias = date('Y/m/d', strtotime('+5 days', strtotime($DATA['data'])));
            //var_dump($somaDias);

            //Condição para não conseguir fazer edição após 5 dias de cadastro das atividades e adicionar nova monitoria
            if (strtotime($somaDias) >= strtotime($diaAtual) && $this->Aula_model->adicionaEditaAulaMonitoria($DATA) != 0) {
                $this->Util->telaResultado($this, "Informações atualizadas!",
                    false, "Monitoria/gerenciar/". $id_monitoria);

            } else {
                $this->Util->telaResultado($this, "Não é possível cadastrar, já se passaram 5 dias após a atividade executada. Procure a Prograd", true);
            }
        }

        //Perfil Adminsitrador altera data independente de tempo
        else if ($PERFIL_USUARIO == "Administrador") {
            if($this->Aula_model->adicionaEditaAulaMonitoria($DATA) != 0) {
                $this->Util->telaResultado($this, "Informações atualizadas!",
                    false, "Monitoria/gerenciar" . '/' . $id_monitoria);

            }else {
                $this->Util->telaResultado($this, "Não é possível fazer a edição !", true);
            }

        }
    }

    function reuniao_editar_view($id_monitoria, $id_atividade)
    {
        $ID_USUARIO = $this->session->userdata('id_usuario');
        $PERFIL_USUARIO = $this->session->userdata('perfil');
        if (@$this->Monitoria_model->verificaIDMonitor($ID_USUARIO, $id_monitoria) or @$this->Monitoria_model->verificaIDProf($ID_USUARIO, $id_monitoria) or $PERFIL_USUARIO == "Administrador") {
            //Prepara para inserir uma nova aula
            if ($id_atividade == 'novo') {
                $aula = new stdClass();
                $aula->id_atividade = "";
                $aula->id_monitoria = $id_monitoria;
                $aula->data = "";
                $aula->horario_inicio = "";
                $aula->horario_fim = "";
                $aula->descricao = "";
                $aula->cadastrado = "";
                $aula->atualizado = "";
                $DATA['aula'] = $aula;

                //carregar html auxiliar
                $this->load->view('reuniao_edit', $DATA);
            } //recupera as informacoes da aula para editar
            else {
                $DATA['aula'] = $this->Aula_model->getReuniaoById($id_atividade);
                //carregar html completo com frequencia
                $this->load->view('reuniao_edit', $DATA);

                if ($DATA['aula'] == null) {
                    $this->Util->telaResultado($this, "Entrada Invalida!", true);
                }
            }
        } else {
                $this->Util->telaResultado($this, "Acesso negado.", true);
        }
    }

    function reuniao_editar($id_monitoria)
    {


        $DATA['id_atividade'] = $this->input->post('id_atividade');
        $DATA['id_monitoria'] = $id_monitoria;
        $DATA['data'] = $this->input->post('data');
        $DATA['horario_inicio'] = $this->input->post('horario_inicio');
        $DATA['horario_fim'] = $this->input->post('horario_fim');
        $DATA['descricao'] = $this->input->post('descricao');


     //   var_dump($DATA);
        if ($this->Aula_model->adicionaEditaReuniaoMonitoria($DATA) != 0) {
            $this->Util->telaResultado($this, "Informações atualizados!", false, "Monitoria/gerenciar" . '/' . $id_monitoria);
        } else {
            $this->Util->telaResultado($this, "Não foi possivel atualizar os dados. Confira os dados informados e se não existe um período ativo ou já cadastrado.", true);
        }
    }

    function excluir_reuniao($id_monitoria, $id_atividade)
    {

        $this->load->model('Aula_model', 'atividade');//

        if ($this->Aula_model->excluir_Reuniao($id_atividade) != 0) {
            $this->Util->telaResultado($this, "Atividades excluida com sucesso!", false, "Monitoria/gerenciar" . '/' . $id_monitoria);
        } else {
            $this->Util->telaResultado($this, "Não foi possivel atualizar os dados. Confira os dados informados e se não existe um periodo ativo ou já cadastrado.", true);
        }
    }

    function excluir_aula($id_monitoria, $id_aula)
    {

        $this->load->model('Aula_model', 'aula');//

        if (!$this->Frequencia_model->getVerificaFrequencia($id_aula)) {

            if ($this->Aula_model->excluir_Aula($id_aula) != 0) {
                $this->Util->telaResultado($this, "Aula excluida com sucesso!", false, "Monitoria/gerenciar" . '/' . $id_monitoria);
            } else {
                $this->Util->telaResultado($this, "Não foi possivel atualizar os dados. Confira os dados informados e se não existe um periodo ativo ou já cadastrado.", true);
            }
        }else {
                $this->Util->telaResultado($this, "Vocẽ tem frequência cadastrada a essa aula.", true);
            }
        }


        function horario_editar_view($id_monitoria, $id_horario_monitoria)
        {
            $ID_USUARIO = $this->session->userdata('id_usuario');
            $PERFIL_USUARIO = $this->session->userdata('perfil');

            if (@$this->Monitoria_model->verificaIDMonitor($ID_USUARIO, $id_monitoria) or @$this->Monitoria_model->verificaIDProf($ID_USUARIO, $id_monitoria) or $PERFIL_USUARIO == "Administrador") {
                $DATA['locais'] = $this->Local_model->getLocais();

                //Prepara para inserir uma nova aula
                if ($id_horario_monitoria == 'novo') {
                    $aula = new stdClass();
                    $aula->id_horario_monitoria = "";
                    $aula->id_monitoria = $id_monitoria;
                    $aula->id_local = "";
                    $aula->dia_semana = "";
                    $aula->horario_inicio = "";
                    $aula->horario_fim = "";
                    $aula->cadastrado = "";
                    $aula->observacao = "";

                    $DATA['horario'] = $aula;
                } //recupera as informacoes da aula para editar
                else {
                    $DATA['horario'] = $this->Monitoria_model->getMonitoriaHorarioById($id_horario_monitoria);
                    //var_dump($DATA['horario']);
                    if ($DATA['horario'] == null) {
                        $this->Util->telaResultado($this, "Entrada Invalida!", true);
                    }
                }

                //var_dump($DATA);
                $this->load->view('horarios_edit', $DATA);
            } else {
                $this->Util->telaResultado($this, "Acesso negado.", true);
            }
        }

        function horario_editar($id_monitoria)
        {
            $DATA['id_horario_monitoria'] = $this->input->post('id_horario_monitoria');
            $DATA['dia_semana'] = $this->input->post('dia_semana');
            $DATA['horario_inicio'] = $this->input->post('horario_inicio');
            $DATA['horario_fim'] = $this->input->post('horario_fim');
            $DATA['id_monitoria'] = $id_monitoria;
            $DATA['id_local'] = $this->input->post('id_local');
            $DATA['observacao'] = $this->input->post('observacao');


            // var_dump($DATA);
            if ($this->Monitoria_model->adicionaEditaHorarioMonitoria($DATA) != 0) {
                $this->Util->telaResultado($this, "Informações atualizados!", false, "Monitoria/gerenciar" . '/' . $id_monitoria);
            } else {
                $this->Util->telaResultado($this, "Não foi possivel atualizar os dados. Confira os dados informados e se não existe um período ativo ou não cadastrado.", true);
            }
        }

        public
        function excluirHorario($id_monitoria, $id_horario_monitoria)
        {

            $this->load->model('Monitoria_model', 'horario_monitoria');//

            if ($this->Monitoria_model->excluirHorario($id_horario_monitoria) != 0) {
                $this->Util->telaResultado($this, "Horario excluido com sucesso!", false, "Monitoria/gerenciar" . '/' . $id_monitoria);
            } else {
                $this->Util->telaResultado($this, "Não foi possivel atualizar os dados.
                Confira os dados informados e se não existe um período ativo ou já cadastrado.", true);
            }
        }

        function aluno_monitor_editar_view($id_usuario)
        {

            //recupera os usuarios do sistema
            $DATA['usuario'] = $this->Usuario_model->getUsuarioAlunoMonitor($id_usuario);
            $DATA['usuario'] = $this->Usuario_model->getUsuarioById($id_usuario);


            $DATA['alunos'] = $this->Usuario_model->getUsuariosAluno();
            $DATA['monitores'] = $this->Usuario_model->getUsuariosMonitor();
            $this->load->view('monitores_listar', $DATA);
        }


        function monitor_aluno_editar_view($id_usuario)
        {
            $DATA['alunos'] = $this->Usuario_model->getUsuariosAluno();
            $DATA['monitores'] = $this->Usuario_model->getUsuariosMonitor();
            $perfil = $this->session->userdata('perfil');

            if($this->Usuario_model->verificaAlunoDesmatriculado($id_usuario) or $perfil == "Administrador" ){

            //recupera os usuarios do sistema
            $DATA['usuario'] = $this->Usuario_model->getUsuarioMonitorAluno($id_usuario);
            $DATA['usuario'] = $this->Usuario_model->getUsuarioById($id_usuario);

            }else {
                $this->Util->telaResultado($this, "Você não pode mudar o perfil de um monitor que não seja de sua disciplina.", true);
            }


            $DATA['alunos'] = $this->Usuario_model->getUsuariosAluno();
            $DATA['monitores'] = $this->Usuario_model->getUsuariosMonitor();
            $this->load->view('monitores_listar', $DATA);
        }


        function aluno_editar_view($id_monitoria, $id_usuario)
        {

            //Prepara para inserir um novo usuario
            if ($id_usuario == 'novo') {
                $usuario = new stdClass();
                $usuario->id_usuario = $id_usuario;
                $usuario->id_monitoria = $id_monitoria;
                $usuario->matricula = "";
                $usuario->nome = "";
                $usuario->email = "";
                $usuario->senha = "";
                $usuario->telefone = "";
                $usuario->perfil = "Aluno";

                $DATA['usuario'] = $usuario;
                $DATA['monitoria'] = $this->Monitoria_model->getMonitoriaById($id_monitoria);
            } //recupera as informacoes do usuario para editar
            else {
                //recupera os usuarios do sistema
                $DATA['usuario'] = $this->Usuario_model->getUsuarioById($id_usuario);
                $DATA['monitoria'] = $this->Monitoria_model->getMonitoriaById($id_monitoria);

                if ($DATA['usuario'] == null) {
                    $this->Util->telaResultado($this, "Entrada Invalida!", true);
                }
            }

            $this->load->view('alunos_edit', $DATA);
        }


        function aluno_editar($id_monitoria)
        {
            //teste de commit
            $DATA['id_usuario'] = $this->input->post('id_usuario');
            $DATA['matricula'] = $this->input->post('matricula');
            $DATA['nome'] = $this->input->post('nome');
            $DATA['email'] = $this->input->post('email');
            $DATA['telefone'] = $this->input->post('telefone');
            $DATA['perfil'] = 'Aluno';

            // var_dump($DATA);


            if (!$this->Monitoria_model->verificaEmail($DATA['email']) and !$this->Monitoria_model->verificaMatricula($DATA['matricula']) and $this->Usuario_model->adicionaEditaUsuario($DATA) != 0) {
                $this->Util->telaResultado($this, "Informações atualizadas!", false, "Monitoria/aluno_listar_view/" . $id_monitoria);
            } else {
                $this->Util->telaResultado($this, "Email ou Matricula ja existentes.", true);
            }
        }


        function aluno_editar_view_2($id_usuario)
        {

            //Prepara para inserir um novo usuario
            if ($id_usuario == 'novo') {
                $usuario = new stdClass();
                $usuario->id_usuario = $id_usuario;
              //  $usuario->id_monitoria = $id_monitoria;
                $usuario->matricula = "";
                $usuario->nome = "";
                $usuario->email = "";
                $usuario->senha = "";
                $usuario->telefone = "";
                $usuario->perfil = "Aluno";

                $DATA['usuario'] = $usuario;
            } //recupera as informacoes do usuario para editar
            else {
                //recupera os usuarios do sistema
                $DATA['usuario'] = $this->Usuario_model->getUsuarioById($id_usuario);


                if ($DATA['usuario'] == null) {
                    $this->Util->telaResultado($this, "Entrada Invalida!", true);
                }
            }

            $this->load->view('alunos_edit2', $DATA);
        }


        function aluno_editar_2($id_monitoria)
        {

            //teste de commit
            $DATA['id_usuario'] = $this->input->post('id_usuario');
            $DATA['matricula'] = $this->input->post('matricula');
            $DATA['nome'] = $this->input->post('nome');
            $DATA['email'] = $this->input->post('email');
            $DATA['telefone'] = $this->input->post('telefone');
            $DATA['perfil'] = 'Aluno';


            if (!$this->Monitoria_model->verificaEmail($DATA['email']) and !$this->Monitoria_model->verificaMatricula($DATA['matricula']) and $this->Usuario_model->adicionaEditaUsuario($DATA) != 0) {
                $this->Util->telaResultado($this, "Informações atualizadas!", false, "Usuarios/listar_monitores_view");
            } else {
                $this->Util->telaResultado($this, "Email ou Matricula ja existentes.", true);
            }
        }


        function cadastro_monitoria($id_monitoria)
        {
            $ID_USUARIO = $this->session->userdata('id_usuario');
            $PERFIL_USUARIO = $this->session->userdata('perfil');

            if ($id_monitoria == "novo") {
                $DATA['id_monitoria'] = $id_monitoria;
                $DATA['id_disciplina'] = $this->input->post('id_disciplina');
                $DATA['id_monitor'] = $this->input->post('id_monitor');
                $DATA['id_professor'] = $this->input->post('id_professor');
                $DATA['id_periodo'] = $this->input->post('id_periodo');
                $DATA['monitoria_remunerada'] = $this->input->post('monitoria_remunerada');
                $DATA['numero_edital'] = $this->input->post('numero_edital');
                $DATA['data_inicio'] = $this->input->post('data_inicio');
                $DATA['data_fim'] = $this->input->post('data_fim');
                $DATA['banco'] = $this->input->post('banco');
                $DATA['agencia'] = $this->input->post('agencia');
                $DATA['conta'] = $this->input->post('conta');
                $DATA['cpf'] = $this->input->post('cpf');
                $DATA['plano_aula'] = $this->input->post('plano_aula');

              //  var_dump($DATA);
            } else {
                $DATA['id_monitoria'] = $id_monitoria;
                $DATA['id_disciplina'] = $this->input->post('id_disciplina');
                $DATA['id_periodo'] = $this->input->post('id_periodo');
                $DATA['monitoria_remunerada'] = $this->input->post('monitoria_remunerada');
                $DATA['numero_edital'] = $this->input->post('numero_edital');
                $DATA['data_inicio'] = $this->input->post('data_inicio');
                $DATA['data_fim'] = $this->input->post('data_fim');
                $DATA['banco'] = $this->input->post('banco');
                $DATA['agencia'] = $this->input->post('agencia');
                $DATA['conta'] = $this->input->post('conta');
                $DATA['plano_aula'] = $this->input->post('plano_aula');
                $DATA['cpf'] = $this->input->post('cpf');

             //   var_dump($DATA);
            }

            if ($this->Monitoria_model->adicionaMonitoria($DATA) != 0) {
                $this->Util->telaResultado($this, "Informações atualizadas!", false, "Monitoria/listar_view/" . $PERFIL_USUARIO . '/' . $ID_USUARIO);
            } else {
                $this->Util->telaResultado($this, "Não foi possivel atualizar os dados. Confira os dados informados e se não existe um período ativo ou já cadastrado.", true);
            }
        }

        function cadastro_monitoria_view($id_monitoria)
        {

            $PERFIL_USUARIO = $this->session->userdata('perfil');

            if ($PERFIL_USUARIO == "Administrador" or $PERFIL_USUARIO == "Professor") {
                //recupera os periodos do sistema
                $DATA['disciplinas'] = $this->Disciplina_model->getDisciplinas();
                $DATA['usuarios'] = $this->Usuario_model->getUsuariosMonitor();
                $DATA['usuariosP'] = $this->Usuario_model->getUsuariosProfessor();
                $DATA['periodos'] = $this->Periodo_model->getPeriodos();
                $DATA['monitoria'] = $this->Monitoria_model->getMonitoriaById($id_monitoria);
                //Prepara para inserir uma nova aula
                if ($id_monitoria == 'novo') {
                    $aula = new stdClass();
                    $aula->id_monitoria = $id_monitoria;
                    $aula->id_local = "";
                    $aula->id_disciplina = "";
                    $aula->id_professor = "";
                    $aula->id_periodo = "";
                    $aula->id_monitor = "";
                    $aula->monitoria_remunerada = "";
                    $aula->plano_aula = "";
                    $aula->numero_edital = "";
                    $aula->data_inicio = "";
                    $aula->data_fim = "";
                    $aula->banco = "";
                    $aula->agencia = "";
                    $aula->conta = "";
                    $aula->cpf = "";


                    $DATA['monitoria'] = $aula;
                } //recupera as informacoes da aula para editar

                else {
                    $DATA['monitoria'] = $this->Monitoria_model->getMonitoriaById($id_monitoria);


                    if ($DATA['monitoria'] == null) {
                        $this->Util->telaResultado($this, "Entrada Invalido!", true);
                    }
                }
               // var_dump($DATA['monitoria']);
                $this->load->view('monitoria_edit', $DATA);
            } else {
                $this->Util->telaResultado($this, "Acesso negado.", true);
            }
        }

        function excluir_monitoria_bd($id_monitoria)
        {

            $ID_USUARIO = $this->session->userdata('id_usuario');
            $PERFIL_USUARIO = $this->session->userdata('perfil');
            //recupera as disciplinas do sistema
            $this->load->model('Monitoria_model', 'monitoria');//
            //var_dump($id_monitoria);


            if (!$DATA['aulas'] = $this->Aula_model->getAulasByMonitoria($id_monitoria) and !$DATA['matriculados'] = $this->Monitoria_model->getAlunosMonitoria($id_monitoria) and !$DATA['horarios'] = $this->Monitoria_model->getMonitoriaHorarios($id_monitoria) and !$DATA['reuniao'] = $this->Aula_model->getReuniaoByMonitoria($id_monitoria)) {
                if ($this->Monitoria_model->excluirMonitoria($id_monitoria) != 0) {
                    $this->Util->telaResultado($this, "Monitoria excluida com sucesso!", false, "Monitoria/listar_view/" . $PERFIL_USUARIO . '/' . $ID_USUARIO);
                } else {
                    $this->Util->telaResultado($this, "Não foi possivel atualizar os dados. Confira os dados informados e se não existe um periodo ativo ou já cadastrado.", true);
                }
            } else {
                $this->Util->telaResultado($this, "Você tem dados vinculados a esta Monitoria.", true);
            }
        }

        function cadastrar_frequencia_view($id_monitoria, $id_aula, $id_frequencia)
        {

            $DATA['matriculados'] = $this->Monitoria_model->getAlunosMonitoria($id_monitoria);
            $DATA['aulas'] = $this->Aula_model->getAulasByMonitoria($id_monitoria);
            //Prepara para inserir uma nova aula
            //$id_frequencia = 'novo';
            if ($id_frequencia == 'novo') {
                $aula = new stdClass();
                $aula->id_frequencia = "";
                $aula->id_aula = $id_aula;
                $aula->id_aluno = "";
                $aula->cadastrado = "";

                $DATA['frequencia'] = $aula;
            } //recupera as informacoes da aula para editar
            else {
                $DATA['frequencia'] = $this->Frequencia_model->adicionaEditaFrequenciaMonitoria($id_frequencia);
                //var_dump($DATA['horario']);
                if ($DATA['frequencia'] == null) {
                    $this->Util->telaResultado($this, "Entrada Inv�lido!", true);
                }
            }

            //var_dump($DATA);
            $this->load->view('frequencia_edit', $DATA);
        }

        function frequencia_editar($id_monitoria, $id_aula)
        {


            $DATA['id_frequencia'] = null;
            $DATA['id_aluno'] = $this->input->post('id_aluno');
            $DATA['id_aula'] = $id_aula;

            //  var_dump($DATA);
            if ($this->Frequencia_model->adicionaEditaFrequenciaMonitoria($DATA) != 0) {
                $this->Util->telaResultado($this, "Informações atualizadas!", false, "Monitoria/frequencia_listar_view/" . $id_monitoria . '/' . $id_aula);
            } else {
                $this->Util->telaResultado($this, "Não foi possivel atualizar os dados. Confira os dados informados e se não existe um período ativo ou já cadastrado.", true);
            }
        }

        function presenca_listar_view($id_monitoria, $id_aula)
        {

            $DATA['matriculados'] = $this->Aula_model->getAlunosSemFrequenciaNaAula($id_aula);

            $DATA['monitoria'] = $this->Monitoria_model->getMonitoriaById($id_monitoria);
            $DATA['aula'] = $this->Aula_model->getAulaById($id_aula);


            $this->load->view('frequencia_edit', $DATA);
        }

        public
        function excluirFrequencia($id_monitoria, $id_aula, $id_frequencia)
        {

            $this->load->model('Frequencia_model', 'frequencia');


            if ($this->Frequencia_model->excluirFrequencia($id_frequencia) != 0) {
                $this->Util->telaResultado($this, "Frequência excluida com sucesso!", false, "Monitoria/frequencia_listar_view/" . $id_monitoria . '/' . $id_aula);
            } else {
                $this->Util->telaResultado($this, "Não foi possivel atualizar os dados. Confira os dados informados e se não existe um período ativo ou já cadastrado.", true);
            }
        }

        function relatorio_mensal($id_monitoria, $id_disciplina, $id_atestado_frequencia)
        {

            $PERFIL_USUARIO = "Administrador";


            //Model que pega as informações de datas iniciais e finais de atestado de frequencia para geração de relatório
            $DATA['data'] = $this->Relatorio_model->dataInicioFim($id_atestado_frequencia);

            //Listar nome de Monitores e Numero de  Edital
            $DATA['monitores'] = $this->Usuario_model->getMonitorById($id_monitoria);
            //Listar Nome da disciplina e Unidade Curricular
            $DATA['disciplina'] = $this->Disciplina_model->getDisciplinaById($id_disciplina);

            //Somatorio de carga horaria total do monitor
            $DATA['cargaHoraria'] = $this->Aula_model->somatorioCargaHoraria($id_monitoria);

            //var_dump($DATA['data']->data_inicio);die;
            //Somatorio de carga horaria demais atividades
            $DATA['somatorioReuniao'] = $this->Aula_model->somatorioHorarioReuniao($id_monitoria);

            //Somatorio de carga horaria total sobre atestado de frequência
            $DATA['cargaHorariaAtestadoFrequencia'] = $this->Aula_model->
            somatorioCargaHorariaAtestadoFrequencia($id_monitoria,$DATA['data']->data_inicio, $DATA['data']->data_fim );

            //Somatorio de carga horaria demais atividades sobre atestado de frequência
            $DATA['somatorioReuniaoAtestadoFrequencia'] = $this->Aula_model->
            somatorioHorarioReuniaoAtestadoFrequencia($id_monitoria, $DATA['data']->data_inicio, $DATA['data']->data_fim);
            //var_dump($DATA['somatorioReuniaoAtestadoFrequencia']);die;

            //Somatorio de carga horaria atividades aulas executadas atestado de frequência
            $DATA['somatorioAulaAtestadoFrequencia'] = $this->Aula_model->
            somatorioHorarioAulaAtestadoFrequencia($id_monitoria,$DATA['data']->data_inicio, $DATA['data']->data_fim );


            //Somatorio de carga horaria atividades aulas executadas
            $DATA['somatorioAula'] = $this->Aula_model->somatorioHorarioAula($id_monitoria);

            $id_usuario = $this->session->userdata('id_usuario');

            //$DATA['monitorias'] = $this->Monitoria_model->getMonitoriasLista($PERFIL_USUARIO, $id_usuario);

            //Listar nome professor da monitoria
            $DATA['monitoria'] = $this->Monitoria_model->profMonitoria($id_monitoria);
            // var_dump($DATA);


            $DATA['alunos'] = $this->Relatorio_model->alunoFrequencia($id_monitoria);
            $DATA['nome'] = $this->Relatorio_model->alunoNome($id_monitoria);
            $DATA['frequencia'] = $this->Relatorio_model->getContagemFrequencia($id_monitoria);
            // var_dump(    $DATA['contagem']);



            //$this->load->view('relatorios/lista_frequencia', $DATA);
            $this->load->view('relatorios/atestado_frequencia', $DATA);
        }


        function relatorio_final($id_monitoria, $id_disciplina, $id_atestado_frequencia)
        {

            $PERFIL_USUARIO = "Administrador";

            //Listar nome de Monitores e Numero de  Edital
            $DATA['monitores'] = $this->Usuario_model->getMonitorById($id_monitoria);
            //Listar Nome da disciplina e Unidade Curricular
            $DATA['disciplina'] = $this->Disciplina_model->getDisciplinaById($id_disciplina);

            //Somatorio de carga horaria do monitor em suas atividades
            $DATA['somatorioAula'] = $this->Aula_model->somatorioHorarioAula($id_monitoria);



            $id_usuario = $this->session->userdata('id_usuario');

            //$DATA['monitorias'] = $this->Monitoria_model->getMonitoriasLista($PERFIL_USUARIO, $id_usuario);

            //Listar nome professor da monitoria
            $DATA['monitoria'] = $this->Monitoria_model->profMonitoria($id_monitoria);
            // var_dump($DATA);


            $DATA['alunos'] = $this->Relatorio_model->alunoFrequencia($id_monitoria);
            $DATA['nome'] = $this->Relatorio_model->alunoNome($id_monitoria);
            $DATA['frequencia'] = $this->Relatorio_model->getContagemFrequencia($id_monitoria);
            // var_dump(    $DATA['contagem']);


            //Model que pega as informações de datas iniciais e finais de atestado de frequencia para geração de relatório
            $DATA['data'] = $this->Relatorio_model->dataInicioFim($id_atestado_frequencia);

            $DATA['dados'] = $this->Relatorio_model->listaAtestadoFinal();

            //$this->load->view('relatorios/lista_frequencia', $DATA);
            $this->load->view('relatorios/atestado_frequencia_final', $DATA);
        }



}
