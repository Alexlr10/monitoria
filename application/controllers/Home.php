<?php
defined('BASEPATH') or exit('No direct script access allowed');

use GraphqlClient\GraphqlRequest\AuthGraphqlRequest;

class Home extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Usuario_model', 'Usuario_model');
        $this->load->model('Aula_model', 'Aula_model');
        $this->load->model('Relatorio_model', 'Relatorio_model');
        $this->load->model('Monitoria_model', 'Monitoria_model');
        $this->load->model('Util_model', 'Util');
    }

    public function index()
    {
        //verifica se o usuario esta logado para rediricionar ele para o painel
        if ($this->session->userdata('logged_in')) {
            $this->view_home();
            $DATA['usuarios'] = $this->Usuario_model->getUsuarios();
            return;
        }


        //recupera as monitorias
        $dias_semana = "";
        $arraySemana = ["Sun" => "Domingo", "Mon" => "Segunda-Feira", "Tue" => "Terça-Feira", "Wed" => "Quarta-feira", "Thu" => "Quinta-feira", "Fri" => "Sexta-feira", "Sat" => "Sábado"];
        if (date("D") == "Sat") {
            $dias_semana = $arraySemana[date("D")] . "|" . $arraySemana["Sun"];
        } else {
            $dias_semana = $arraySemana[date("D")] . "|" . $arraySemana[date('D', strtotime('+1 days'))];
        }

        //$dias_semana = "Segunda-feira|Terça-feira";
        $monitorias = $this->Monitoria_model->getMonitoriasListaBydia($dias_semana);

        //para prencher o campo para buscar monitorias é necessario dois arrays em javascript, um com os nomes e outros com id
        $busca_data = $this->get_data_pesquisar();

       // $qtd = strlen($monitoria->nomeDisciplina);


        $DATA['busca_id'] = $busca_data['busca_id'];
        $DATA['busca_nome'] = $busca_data['busca_nome'];
        $DATA['monitorias'] = $monitorias;

        $this->load->view('index', $DATA);

    }

    public function get_data_pesquisar()
    {

        //$dias_semana = "Domingo|Segunda-Feira|Terça-Feira|Quarta-feira|Quinta-feira|Sexta-feira|Sábado";
        $monitorias = $this->Monitoria_model->getMonitoriasListaBydia('');

        //para prencher o campo para buscar monitorias é necessario dois arrays em javascript, um com os nomes e outros com id
        $busca_id = "[";
        $busca_nome = "[";
        $id_adicionado = array();
        foreach ($monitorias as $monitoria) {
            if (in_array($monitoria->id_monitoria, $id_adicionado))
                continue;
            $id_adicionado[] = $monitoria->id_monitoria;
            $busca_id .= "'" . addslashes($monitoria->id_monitoria) . "',";
            $busca_nome .= "'" . addslashes(mb_strtoupper($monitoria->nomeDisciplina)) . "',";
        }
        $busca_id .= "]";
        $busca_nome .= "]";

        $DATA['busca_id'] = $busca_id;
        $DATA['busca_nome'] = $busca_nome;

        return $DATA;
    }

    public function view_home()
    {
        $ID_USUARIO = $this->session->userdata('id_usuario');
        $DATA['usuario'] = $this->Usuario_model->verificaAceitoTermo($ID_USUARIO);
       // var_dump($DATA);
        $this->load->view('home', $DATA);
    }

    public function login()
    {
        //verifica se o usuario esta logado para rediricionar ele para o painel
        if ($this->session->userdata('logged_in')) {
            $this->view_home();

            return;
        }

        //recupera os dados do formulario
        $containstitucional = $this->input->post('containstitucional');
        $senha = $this->input->post('senha');

        try {
            $request = new stdClass();

            if(is_null($containstitucional) or is_null($senha)){
                throw new \Exception('Usuário ou senha não informados');
            }

            $request->containstitucional = $containstitucional;
            $request->password = $senha;


            // Carrega a classe de autenticação
            $authGraphqlRequest = new AuthGraphqlRequest();

            // Tenta realizar o login na Conta Institucional
             $authGraphqlRequest->loginContaInstitucional($request);

            // Recupera as informações do usuário logado
            // Dados pessoais e vinculos (aluno, docente, tae, coordenador de curso, etc) com a UFVJM
            $usuarioLogadoInfo = $authGraphqlRequest->usuarioLogadoInfo();

            $DATA = $this->Usuario_model->getUsuarioByContaInstitucional($containstitucional);



            if(is_null($DATA)){
                throw new \Exception('Não existe usuário cadastrado com a conta institucional '. $containstitucional);
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }


            //caso encontre um usuario quer dizer que ele esta registrado no sistema

            if (isset($DATA) && $DATA != null) {

                    $newdata = array(
                        'id_usuario' => $DATA->id_usuario,
                        'nome' => $DATA->nome,
                        'perfil' => $DATA->perfil,
                        'logged_in' => true
                    );


                    if (($DATA->perfil == 'Administrador' or $DATA->perfil == 'Monitor' or ($DATA->perfil == 'Professor') and $this->Usuario_model->verificaProfessorHabilitado($DATA->id_usuario))) {
                        $this->session->set_userdata($newdata);

                            $this->view_home($DATA->id_usuario);

                    } else {
                        $DADOS['msg'] = 'Professor ainda não habilitado no sistema - Solicite habilitação na PROGRAD - DAA';
                        $this->load->view('login', $DADOS);
                        //$this->index();
                    }
                } //caso o usuario digitou um matricula  e uma senha e nao esteja no BD envia uma msg de erro
                elseif (($containstitucional != null || $senha != null) ) {
                    $DADOS['msg'] = $errorMessage;
                    $this->load->view('login', $DADOS);
                } //caso contrario, mostra a tela de login
                else {
                    $DADOS['msg'] = 'Entre para iniciar sua sessão';
                    $this->load->view('login', $DADOS);
                }
            }




    function logout()
    {
        $this->session->unset_userdata('id_usuario');
        $this->session->unset_userdata('nome');
        $this->session->unset_userdata('perfil');
        $this->session->unset_userdata('logged_in');
        session_destroy();
        redirect('Home', 'refresh');
    }

    function alterar_senha_deslogado_view()
    {
        $this->load->view('esqueci_senha');
    }

    function alterar_senha()
    {
        $id_usuario = $this->input->post('id_usuario');

        $senha = md5($this->input->post('senha'));
        $senha2 = md5($this->input->post('senha2'));


        if ($senha == $senha2 && trim($senha) != "") {
            $this->Usuario_model->alterarSenhaDeslogado($id_usuario, $senha);
            redirect('Home', 'refresh');
        } else {
            redirect('Home/recuperaSenha/', 'refresh');
        }
    }

    function recuperaSenha()
    {
        $email = $this->input->post('email');
        $DATA = $this->Usuario_model->recuperaSenha($email);

        if ($this->Usuario_model->recuperaSenha($email)) {
            $this->load->view('trocar_senha', $DATA);
        } else {
            redirect('Home/alterar_senha_deslogado_view/', 'refresh');
        }
    }

    function pesquisar_monitoria($id_monitoria)
    {
        //recupera os periodos
        $DATA['monitoria'] = $this->Monitoria_model->getMonitoriaById($id_monitoria);
        $DATA['aulas'] = $this->Aula_model->getAulasByMonitoria($id_monitoria);
        $DATA['reuniao'] = $this->Aula_model->getReuniaoByMonitoria($id_monitoria);
        $DATA['horarios'] = $this->Monitoria_model->getMonitoriaHorarios($id_monitoria);
        $DATA['alunos'] = $this->Monitoria_model->getAlunosByMonitoria($id_monitoria);
        $DATA['atestados'] = $this->Relatorio_model->infoAtestadoFrequencia($id_monitoria);


        $busca_data = $this->get_data_pesquisar();
        $DATA['busca_id'] = $busca_data["busca_id"];
        $DATA['busca_nome'] = $busca_data["busca_nome"];

        $this->load->view('pesquisa_monitoria', $DATA);

    }
    //Função para chamar página em construção
    function tela_construcao(){

        $this->load->view('tela_construcao');
    }

}
