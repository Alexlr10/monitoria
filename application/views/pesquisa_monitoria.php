<?php
//carrega a traducao em portugues para as tabelas
$ci =& get_instance();
$ci->load->model('Util_model');
$datatablesPortugueseBrasil = $ci->Util_model->datatablesPortugueseBrasil();
$PERFIL_USUARIO = $this->session->userdata('perfil');
$id_usuario = $this->session->userdata('id_usuario');

$this->load->view('header_site'); ?>


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
                    <p><b>Semestre:</b> <?= $monitoria->periodo ?>  </p>
                    <hr/>

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

                        </tr>
                        <?php foreach ($horarios as $horario) { ?>
                            <tr>
                                <td>
                                    <?= $horario->dia_semana ?></a>
                                </td>


                                <td><?= date('H:i', strtotime($horario->horario_inicio)) . ' - ' . date('H:i', strtotime($horario->horario_fim)) ?></td>

                                <td><?= $horario->dependencia ?></td>

                            </tr>
                        <?php } ?>
                    </table>

                </div>
            </div>
            <!-- /.box -->

        </div>
        <!-- /.col (right) -->

    </div>
    <!-- /.row -->


    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Atividades trabalhadas em aulas</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Data</th>
                            <th>horário</th>
                            <th>Atividades</th>


                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($aulas as $aula) { ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($aula->data)) ?></td>
                                <td> <?= date('H:i', strtotime($aula->horario_inicio)) . ' - ' . date('H:i', strtotime($aula->horario_fim)) ?> </td>
                                <td> <?= $aula->atividades ?> </td>


                            </tr>
                        <?php } ?>
                        </tbody>

                    </table>
                </div>
                <!-- /.box-body -->



            </div>
            <!-- /.box -->

        </div>
        <!-- /.col -->

    </div>
    <!-- /.row -->






</section>
<!-- /.content -->




<?php $this->load->view('footer_site');?>
