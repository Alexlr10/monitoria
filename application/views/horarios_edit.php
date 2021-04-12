<?php $this->load->view('header'); ?>
<?php
//carrega a traducao em portugues para as tabelas
$ci =& get_instance();
$ci->load->model('Util_model');
$datatablesPortugueseBrasil = $ci->Util_model->datatablesPortugueseBrasil();
?>

<!-- DataTables -->
<link rel="stylesheet"
      href="<?= base_url('/AdminLTE-2.4.3/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'); ?>">

<!-- daterange picker -->
<link rel="stylesheet"
      href="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-daterangepicker/daterangepicker.css'); ?>">
<!-- bootstrap datepicker -->
<link rel="stylesheet"
      href="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css'); ?>">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/plugins/iCheck/all.css'); ?>">
<!-- Bootstrap Color Picker -->
<link rel="stylesheet"
      href="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css'); ?>">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/plugins/timepicker/bootstrap-timepicker.min.css'); ?>">

<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/bower_components/select2/dist/css/select2.min.css'); ?>">
<!-- Theme style -->
<link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/dist/css/AdminLTE.min.css'); ?>">
<!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/dist/css/skins/_all-skins.min.css'); ?>">


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Horários
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('Home/Index') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?= site_url('Monitoria/listar_view/') ?>"></i> Monitoria</a></li>
        <li><a href="<?= site_url('Monitoria/gerenciar/'.$horario->id_monitoria) ?>"></i> Gerenciar</a></li>
        <li class="active">Editar</li>

    </ol>
</section>


<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= $horario->id_horario_monitoria == "" ? "Novo" : "Editar" ?> Horário</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form role="form" action="<?php echo site_url('Monitoria/horario_editar/'.$horario->id_monitoria); ?>" method="post">
                        <div class="box-body">
                            <input type="hidden" name="id_horario_monitoria" value="<?=$horario->id_horario_monitoria?>">

                            <input type="hidden" name="id_monitoria" value="<?=$horario->id_monitoria?>">


                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Data:</label>
                                        <div class="input-group text">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <select type="text" class="form-control select2" id="dia_semana"
                                                    name="dia_semana" value="dia_semana">
                                                <option  value="SEGUNDA-FEIRA"<?= $horario->dia_semana == "SEGUNDA-FEIRA" ? 'selected="selected"' : ''?>>SEGUNDA-FEIRA</option>
                                                <option  value="TERÇA-FEIRA"<?= $horario->dia_semana == "TERÇA-FEIRA" ? 'selected="selected"' : ''?> >TERÇA-FEIRA</option>
                                                <option  value="QUARTA-FEIRA"<?= $horario->dia_semana == "QUARTA-FEIRA" ? 'selected="selected"' : ''?>>QUARTA-FEIRA</option>
                                                <option  value="QUINTA-FEIRA"<?= $horario->dia_semana == "QUINTA-FEIRA" ? 'selected="selected"' : ''?>>QUINTA-FEIRA</option>
                                                <option  value="SEXTA-FEIRA"<?= $horario->dia_semana == "SEXTA-FEIRA" ? 'selected="selected"' : ''?>>SEXTA-FEIRA</option>
                                                <option  value="SÁBADO"<?= $horario->dia_semana == "SÁBADO" ? 'selected="selected"' : ''?>>SÁBADO</option>
                                            </select>


                                        </div>
                                    </div>
                                </div>
                                <!-- /.col -->

                                <div class="col-md-4">
                                    <div class="bootstrap-timepicker">
                                        <div class="form-group">
                                            <label>Inicio:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                                <input type="text" class="form-control timepicker" name="horario_inicio"
                                                       id="horario_inicio" value="<?= $horario->horario_inicio ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col -->

                                <div class="col-md-4">
                                    <div class="bootstrap-timepicker">
                                        <div class="form-group">
                                            <label>Fim:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                                <input type="text" class="form-control timepicker" name="horario_fim"
                                                       id="horario_fim" value="<?= $horario->horario_fim ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col -->

                            </div>
                            <!-- /.row -->


                            <div class="form-group">
                                <label>Local</label>
                                <select class="form-control select2"  name="id_local" style="width: 100%;">
                                    <?php foreach ($locais as $local) { ?>
                                        <option value="<?= $local->id_local ?>" <?= $horario->id_local == $local->id_local ? 'selected="selected"' : '' ?>>
                                            <?= $local->dependencia . ', ' . $local->campus ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label style="margin-bottom: 0px;">Observação</label><br><small style="color: #777;">
                                    Adicione informações complementares sobre local ou link da plataforma de videoconferecia
                                    para monitoria on-line</small>
                                <input type="text" class="form-control" name="observacao"
                                       placeholder=""
                                       value="<?= $horario->observacao ?>">
                            </div>






                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-default" name="opcao" value="salvar" >Salvar</button>
                            <a href="<?=site_url('Monitoria/gerenciar/'.$horario->id_monitoria)?>" class="btn btn-default">Voltar</a>
                        </div>
                    </form>
                    <!-- /.box-body -->

                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->


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
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js'); ?>"></script>


<!-- InputMask -->
<script src="<?= base_url('/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.js'); ?>"></script>
<script src="<?= base_url('/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>
<script src="<?= base_url('/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.extensions.js'); ?>"></script>
<!-- date-range-picker -->
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/moment/min/moment.min.js'); ?>"></script>
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-daterangepicker/daterangepicker.js'); ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'); ?>"></script>
<!-- bootstrap color picker -->
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js'); ?>"></script>
<!-- bootstrap time picker -->
<script src="<?= base_url('/AdminLTE-2.4.3/plugins/timepicker/bootstrap-timepicker.min.js'); ?>"></script>


<script>
    $(function () {

        //Initialize Select2 Elements
        $('.select2').select2();

        //Date picker
        $('#data').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
        });

        $('#example1').DataTable({
            'language': <?= $datatablesPortugueseBrasil?>
        });


        //Timepicker
        $('#horario_inicio').timepicker({
            startTime: '12:00',
            showMeridian: false,
            showInputs: false,
            showSeconds: false,
            interval: 15
        });

        //Timepicker
        $('#horario_fim').timepicker({
            showMeridian: false,
            showInputs: false,
            showSeconds: false,
            interval: 15
        });
    })
</script>
