<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Usuario_model', 'Usuario_model');
        $this->load->model('Monitoria_model', 'Monitoria_model');
        $this->load->model('Aula_model', 'Aula_model');
        $this->load->model('Util_model', 'Util');
    }

    public function index()
    {
    }

    function listar_monitores_view()
    {

        $PERFIL_USUARIO = $this->session->userdata('perfil');
        if ($PERFIL_USUARIO == "Administrador" or $PERFIL_USUARIO == "Professor") {
            //recupera os usuarios do sistema
            $DATA['monitores'] = $this->Usuario_model->getUsuariosMonitor();
            $DATA['alunos'] = $this->Usuario_model->getUsuariosAluno();

            $this->load->view('monitores_listar', $DATA);
        } else {
            $this->Util->telaResultado($this, "Acesso negado.", true);
        }
    }

    function listar_monitores_planilha()
    {
        $id_usuario = $this->session->userdata('id_usuario');
        $PERFIL_USUARIO = $this->session->userdata('perfil');
        if ($PERFIL_USUARIO == "Administrador" or $PERFIL_USUARIO == "Professor") {
            $quant = count($this->Monitoria_model->getMonitoriasLista($PERFIL_USUARIO, $id_usuario));


            //recupera os usuarios do sistema
//            $DADOS['monitorias'] = $this->Aula_model->getRelatorioPlanilha(1);
//            $DADOS['monitorias'] = $this->Aula_model->getRelatorioPlanilha(2);

         //   $DADOS['monitorias'] = $this->Aula_model->getAlunoDadosBancarios();

            $aula['aula'] = $this->Aula_model->getSomatorioAula();
            $atividade['atividade'] = $this->Aula_model->getSomatorioAtividade();

            for($i = 1; $i <= $quant; $i++){

                $teste[$i] = $this->Aula_model->getRelatorioPlanilha($i)[0];


            }
            $DADOS['monitorias'] = $teste;
            var_dump($DADOS);



            $this->load->view('planilha/proplan', $DADOS);
        } else {
            $this->Util->telaResultado($this, "Acesso negado.", true);
        }
    }


    function listar_view()
    {
        $this->Util->verificaPermissao($this, 'Administrador');

        //recupera os usuarios do sistema
        $DATA['usuarios'] = $this->Usuario_model->getUsuarios();

        $this->load->view('usuarios_listar', $DATA);
    }

    public function listar_professor_view(){
        $this->Util->verificaPermissao($this, 'Administrador');
        $DATA['profHabilitado'] = $this->Usuario_model->listarProfessoresHabilitados();
        $DATA['profNaoHabilitado'] = $this->Usuario_model->listarProfessoresNaoHabilitados();
        $this->load->view('professores_listar', $DATA);
    }

    function professor_habilitado_editar_view($id_usuario)
    {
        $this->Util->verificaPermissao($this, 'Administrador');
        //recupera os usuarios do sistema
        $DATA['usuario'] = $this->Usuario_model->setProfessorHabilitado($id_usuario);
        $DATA['usuario'] = $this->Usuario_model->getUsuarioById($id_usuario);


        $DATA['profHabilitado'] = $this->Usuario_model->listarProfessoresHabilitados();
        $DATA['profNaoHabilitado'] = $this->Usuario_model->listarProfessoresNaoHabilitados();
        $this->load->view('professores_listar', $DATA);
    }

    function professor_nao_habilitado_editar_view($id_usuario)
    {
        $this->Util->verificaPermissao($this, 'Administrador');
        //recupera os usuarios do sistema
        $DATA['usuario'] = $this->Usuario_model->setProfessorNaoHabilitado($id_usuario);
        $DATA['usuario'] = $this->Usuario_model->getUsuarioById($id_usuario);


        $DATA['profHabilitado'] = $this->Usuario_model->listarProfessoresHabilitados();
        $DATA['profNaoHabilitado'] = $this->Usuario_model->listarProfessoresNaoHabilitados();
        $this->load->view('professores_listar', $DATA);
    }




    function editar_view($id_usuario)
    {
        //Prepara para inserir um novo usuario

        if ($id_usuario == 'novo') {
            $usuario = new stdClass();
            $usuario->id_usuario = $id_usuario;
            $usuario->matricula = "";
            $usuario->nome = "";
            $usuario->containstitucional = "";
            $usuario->email = "";
            $usuario->telefone = "";
            $usuario->perfil = "";
            $DATA['usuario'] = $usuario;
        } //recupera as informacoes do usuario para editar
        else {
            //recupera os usuarios do sistema
            $DATA['usuario'] = $this->Usuario_model->getUsuarioById($id_usuario);
        }
                $this->load->view('usuarios_edit', $DATA);
    }

    function editar()
    {
        $DATA['id_usuario'] = $this->input->post('id_usuario');
        $DATA['matricula'] = $this->input->post('matricula');
        $DATA['nome'] = $this->input->post('nome');
        $DATA['containstitucional'] = $this->input->post('containstitucional');
        $DATA['email'] = $this->input->post('email');
        $DATA['telefone'] = $this->input->post('telefone');
        $DATA['perfil'] = $this->input->post('perfil');

        //verifica se o usuario esta editando outro usuario. somente o admin pode fazer isso
        $id_usuario = $this->session->userdata('id_usuario');


        //  var_dump($DATA);

        if ($DATA['id_usuario'] != 0) {
            $this->Usuario_model->adicionaEditaUsuario($DATA);
            $this->Util->telaResultado($this, "Informações atualizados!", false, "Usuarios/listar_view");
        } else {
            if (!$this->Monitoria_model->verificaEmail($DATA['email']) and !$this->Monitoria_model->verificaMatricula($DATA['matricula']) and $this->Usuario_model->adicionaEditaUsuario($DATA) != 0) {
                $this->Util->telaResultado($this, "Informações atualizados!", false, "Usuarios/listar_view");
            } else {
                $this->Util->telaResultado($this, "Email ou Matricula ja existentes.", true);
            }
        }


        $this->Usuario_model->adicionaEditaUsuario($DATA);
        $this->Util->telaResultado($this, "Informações atualizados!", false, "Usuarios/listar_view");
    }




    function alterar_senha_view($id_usuario)
    {

        //verifica se o usuario esta editando outro usuario. somente o admin pode fazer isso
        $id_usuario_user = $this->session->userdata('id_usuario');
        if ($id_usuario != $id_usuario_user) {
            $this->Util->verificaPermissao($this, 'Administrador');
        }

        $DATA = $this->Usuario_model->getUsuarioById($id_usuario);
        $this->load->view('usuarios_senha', $DATA);
    }

    function alterar_senha()
    {
        $id_usuario = $this->input->post('id_usuario');

        $senha = md5($this->input->post('senha'));
        $senha2 = md5($this->input->post('senha2'));
        if ($senha == $senha2 && trim($senha) != "") {
            $this->Usuario_model->alterarSenha($id_usuario, $senha);
            $this->Util->telaResultado($this, "Senha alterada!", false, "Home/index");
        } else {
            $this->Util->telaResultado($this, "Erro: As senhas estão diferentes!", true);
        }
    }

    //Entra na model e excluir usuarios do banco de dados
    function excluir_usuario_bd($id_usuario)
    {

        //recupera as disciplinas do sistema
        $this->load->model('Usuario_model', 'usuario');//
        //var_dump($id_monitoria);

        if (!$DATA['monitoria'] = $this->Monitoria_model->verificaExclusaoUsuarioMonitoria($id_usuario)) {
            if ($this->Usuario_model->excluirUsuario($id_usuario) != 0) {
                $this->Util->telaResultado($this, "Usuário excluido com sucesso!", false, "Usuarios/listar_view");
            } else {
                $this->Util->telaResultado($this, "Não foi possivel atualizar os dados. Confira os dados informados e se não existe um periodo ativo ou ja cadastrado.", true);
            }
        } else {
            $this->Util->telaResultado($this, "Você tem dados vinculados a esse usuario.", true);
        }
    }



    public function termos_de_uso()
    {
        $ID_USUARIO = $this->session->userdata('id_usuario');
        $DATA['usuario'] = $this->Usuario_model->verificaAceitoTermo($ID_USUARIO);

        $this->Usuario_model->setTermosDeUso($ID_USUARIO);
       // var_dump($DATA);

        $this->load->view('home',$DATA);
    }
}
