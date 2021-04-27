<?php $this->load->view('header'); ?>
<?php
//carrega a traducao em portugues para as tabelas
$ci =& get_instance();
$ci->load->model('Util_model');
$datatablesPortugueseBrasil = $ci->Util_model->datatablesPortugueseBrasil();
$PERFIL_USUARIO = $this->session->userdata('perfil');
$id_usuario = $this->session->userdata('id_usuario');
?>


<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/plugins/timepicker/bootstrap-timepicker.min.css'); ?>">

<!-- DataTables -->
<link rel="stylesheet"
      href="<?= base_url('/AdminLTE-2.4.3/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'); ?>">


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= $monitoria->nomeDisciplina ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('Home/Index') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?= site_url('Monitoria/listar_view/') ?>"></i> Monitoria</a></li>
        <li class="active">Gerenciar</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-md-4">

            <div class="box box-header">
                <div class="box-header">
                    <h3 class="box-title">Informações</h3>
                </div>
                <div class="box-body">
                    <p><b>Curso:</b> <?= $monitoria->curso ?>  </p>
                    <p><b>Professor:</b> <?= $monitoria->professor ?>  </p>
                    <p><b>Monitor:</b> <?= $monitoria->monitor ?>  </p>
                    <p><b>Horas aulas realizadas:</b> <?= substr( $somatorioAula->horario_aula, 0, -3); ?></p>
                    <p><b>Horas demais atividades:</b> <?=substr( $somatorioReuniao->horario_reuniao, 0, -3);  ?></p>
                    <p><b>Carga horária total:</b> <?= substr( $cargaHoraria->carga_horaria, 0, -3); ?></p>
                    <p><b>Semestre:</b> <?= $monitoria->periodo ?>  </p>

                    <hr/>
                    <p><b>Alunos cadastrados:</b> <?= count($alunos); ?>  </p>
                    <p><b>Aulas cadastradas:</b> <?= count($aulas); ?>  </p>
                </div>
            </div>
            <!-- /.box -->
        </div>

        <!-- /.col (left) -->
        <div class="col-md-8">
            <div class="box box-header">
                <div class="box-header">
                    <h3 class="box-title">Horários</h3>
                </div>
                <div class="box-body">
                    <input type="hidden" id="quantHorarios" value="1">
                    <table class="table table-bordered" id="tableHour">
                        <tr>
                            <th> Dia</th>
                            <th> Horário</th>
                            <th> Local</th>
                            <th> Ações</th>
                        </tr>
                        <?php foreach ($horarios as $horario) { ?>
                            <tr>
                                <td>
                                    <?= $horario->dia_semana ?></a>
                                </td>


                                <td><?= date('H:i', strtotime($horario->horario_inicio)) . ' - ' . date('H:i', strtotime($horario->horario_fim)) ?></td>

                                <td><?=  $horario->dependencia . ', ' . $horario->campus ?></td>
                                <td style="vertical-align: middle;">

                                    <a href="<?= site_url('Monitoria/horario_editar_view/' . $horario->id_monitoria . '/' . $horario->id_horario_monitoria) ?>">
                                        <span class="glyphicon glyphicon-edit" title="Editar horario" ></span></a>
                                    <a href="<?= site_url('Monitoria/excluirHorario/' . $horario->id_monitoria . '/' . $horario->id_horario_monitoria) ?>">
                                        <span onclick="return onmessage('Deseja realmente excluir?')"
                                              class="glyphicon glyphicon-trash"title="Excluir horario" ></span></a></td>
                            </tr>
                        <?php } ?>
                    </table>
                    <br/>
                    <div class="col-md-2">
                        <a href="<?= site_url('Monitoria/horario_editar_view/' . $monitoria->id_monitoria . '/novo/') ?>"
                           class="btn btn-default btn-block btn-flat">Adicionar</a>
                    </div>
                </div>
            </div>
            <!-- /.box -->

        </div>
        <!-- /.col (right) -->

    </div>
    <!-- /.row -->


    <div class="row ">
        <div class="col-xs-12 ">
            <div class="box table-responsive ">
                <div class="box-header">
                    <h3 class="box-title">Cadastrar atividades aulas</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Data</th>
                            <th>horário</th>
                            <th>Atividades</th>
                            <th>Alunos</th>
                            <?php if ((strpos("Administrador", $this->session->userdata('perfil')) !== false)
                            or strpos("Professor", $this->session->userdata('perfil'))!== false) {  ?>
                            <th>Atualizado</th>
                            <th>Cadastrado</th>
                            <?php } ?>
                            <th>Ações</th>

                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($aulas as $aula) { ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($aula->data)) ?></td>
                                <td> <?= date('H:i', strtotime($aula->horario_inicio)) . ' - ' . date('H:i', strtotime($aula->horario_fim)) ?> </td>
                                <td> <?= $aula->atividades ?> </td>
                                <td> <?= $aula->quant_alunos ?> </td>
                                <?php if ((strpos("Administrador", $this->session->userdata('perfil')) !== false) or strpos("Professor", $this->session->userdata('perfil'))!== false) {  ?>
                                <td> <?= $ci->Util_model->formatarTimestamp($aula->atualizado); ?> </td>
                                <td> <?= $ci->Util_model->formatarTimestamp($aula->cadastrado); ?> </td>

                                <?php } ?>
                                <td>

                                    <a href="<?= site_url('Monitoria/frequencia_listar_view/' . $monitoria->id_monitoria . '/' . $aula->id_aula) ?>"
                                        <span class="glyphicon glyphicon-plus-sign" title="Acessar Aula" ></span></a>

                                    <?php if ((strpos("Administrador", $this->session->userdata('perfil')) !== false) or strpos("Professor", $this->session->userdata('perfil'))!== false) {  ?>
                                    <a href="<?= site_url('Monitoria/aula_editar_view/' . $monitoria->id_monitoria . '/' . $aula->id_aula) ?>">
                                       <span class="glyphicon glyphicon-edit" title="Editar Aula" ></span></a>
                                    <?php } ?>

                                    <a href="<?= site_url('Monitoria/excluir_aula/' . $monitoria->id_monitoria . '/' . $aula->id_aula) ?>">
                                        <span onclick="return confirm('Deseja realmente excluir?')"
                                              class="glyphicon glyphicon-trash" title="Excluir Aula" ></span>

                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>

                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-body">
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-xs-2">
                            <a href="<?= site_url('Monitoria/aula_editar_view/' . $monitoria->id_monitoria . '/novo/') ?>"
                               class="btn btn-default btn-block btn-flat">Cadastrar aula</a>
                        </div>
                        <!-- /.col -->
                    </div>
                </div>


            </div>
            <!-- /.box -->

        </div>
        <!-- /.col -->

    </div>
    <!-- /.row -->

    <div class="row ">
        <div class="col-xs-12 ">
            <div class="box table-responsive">
                <div class="box-header">
                    <h3 class="box-title">Outras Atividades(reunião professor,etc)</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example3" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Data</th>
                            <th>horário</th>
                            <th>Descrição</th>
                            <?php if ((strpos("Administrador", $this->session->userdata('perfil')) !== false)
                                or strpos("Professor", $this->session->userdata('perfil'))!== false) {  ?>
                                <th>Atualizado</th>
                            <th>Cadastrado</th>
                            <?php } ?>
                            <th>Ações</th>

                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($reuniao as $aula) { ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($aula->data)) ?></td>
                                <td> <?= date('H:i', strtotime($aula->horario_inicio)) . ' - ' . date('H:i', strtotime($aula->horario_fim)) ?> </td>
                                <td> <?= $aula->descricao ?> </td>
                                <?php if ((strpos("Administrador", $this->session->userdata('perfil')) !== false) or strpos("Professor", $this->session->userdata('perfil'))!== false) {  ?>
                                <td> <?= $ci->Util_model->formatarTimestamp($aula->cadastrado); ?> </td>
                                <td> <?= $ci->Util_model->formatarTimestamp($aula->atualizado); ?> </td>
                                <?php } ?>
                                <td>

                                    <?php if ((strpos("Administrador", $this->session->userdata('perfil')) !== false) or strpos("Professor", $this->session->userdata('perfil'))!== false) {  ?>
                                        <a href="<?= site_url('Monitoria/reuniao_editar_view/' . $monitoria->id_monitoria . '/' . $aula->id_atividade) ?>">
                                        <span class="glyphicon glyphicon-edit" title="Editar atividade"></span> </a>
                                    <?php } ?>

                                    <a href="<?= site_url('Monitoria/excluir_reuniao/' . $monitoria->id_monitoria . '/' . $aula->id_atividade) ?>">
                                        <span onclick="return confirm('Deseja realmente excluir?')"
                                              class="glyphicon glyphicon-trash" title="Excluir atividade"></span>

                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>

                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-body">
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-xs-2">
                            <a href="<?= site_url('Monitoria/reuniao_editar_view/' . $monitoria->id_monitoria . '/novo') ?>"
                               class="btn btn-default btn-block btn-flat">Cadastrar reunião</a>
                        </div>
                        <!-- /.col -->
                    </div>
                </div>


            </div>
            <!-- /.box -->

        </div>
        <!-- /.col -->

    </div>
    <!-- /.row -->



    <div class="row ">
        <div class="col-xs-">
            <div class="box table-responsive">
                <div class="box-header">
                    <h3 class="box-title">Alunos</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Nome</th>
                            <th>Frequência</th>

                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($alunos as $aluno) { ?>
                            <tr>
                                <td> <?= $aluno->matricula ?> </td>
                                <td> <?= $aluno->nome ?> </td>
                                <td> <?= $aluno->quant_frequencia ?> </td>

                            </tr>
                        <?php } ?>
                        </tbody>

                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-body">
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-xs-2">
                            <a href="<?= site_url('Monitoria/aluno_listar_view/' . $monitoria->id_monitoria) ?>"
                               class="btn btn-default btn-block btn-flat">Matricular aluno</a>
                        </div>
                        <!-- /.col -->
                    </div>
                </div>


            </div>
            <!-- /.box -->

        </div>
        <!-- /.col -->

    </div>
    <!-- /.row -->
    <!-- /.Bloco Emissão de Relatórios -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box table-responsive">

                <div class="col-xs-13">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Gerar Relatórios</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="box-group" id="accordion">
                                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                <div class="panel box box-primary">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" class="">
                                                Atestado de Frequência Mensal
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="true" style="">
                                        <div class="box-body">
                                    Selecione a Data desejada:
                                            <br>
                                            <br>

                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Semestre/Ano teste</th>
                                                    <th>Data Inicio</th>
                                                    <th>Data Fim</th>
                                                    <th>Ações</th>
                                                </tr>
                                                </thead>
                                                <tbody>


                                                <?php foreach ($atestados as $atestado) { ?>
                                                    <tr>


                                                        <td><?= $atestado->semestre.'/'.$atestado->ano ?></td>
                                                        <td><?= date('d/m/Y', strtotime($atestado->data_inicio)) ?></td>
                                                        <td><?= date('d/m/Y', strtotime($atestado->data_fim)) ?></td>
                                                        <td>

                                                            <a target="_blank" href="<?= site_url('Monitoria/relatorio_mensal/'.$monitoria->id_monitoria.'/'.
                                                                $atestado->id_disciplina.'/'.$atestado->id_atestado_frequencia ) ?>"
                                                                class="glyphicon glyphicon-plus-sign"title="Selecionar Data"></a>
                                                        </td>
                                                    </tr>

                                                <?php }?>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>

                                </div>
                            </div>
                        </div>


                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.box-body -->




            </div>
            <!-- /.box -->



        </div>
        <!-- /.col -->

    </div>

</section>
<!-- /.content -->

<?php $this->load->view('footer'); ?>


<!-- DataTables -->
<script src="<?php echo base_url(); ?>/AdminLTE-2.4.3/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>/AdminLTE-2.4.3/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>


<!-- bootstrap time picker -->
<script src="<?= base_url('/AdminLTE-2.4.3/plugins/timepicker/bootstrap-timepicker.min.js'); ?>"></script>


<!-- page script -->

<script>
    $(function () {

        $('#example1').DataTable({
            'ordering': false
            'language': <?= $datatablesPortugueseBrasil?>
        })
        $('#example2').DataTable({
            'language': <?= $datatablesPortugueseBrasil?>
        })

        $('#example3').DataTable({
            'ordering': false
            'language': <?= $datatablesPortugueseBrasil?>
        })

    })
</script>

