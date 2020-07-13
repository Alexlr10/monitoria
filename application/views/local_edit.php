<?php $this->load->view('header'); ?>
<?php
//carrega a traducao em portugues para as tabelas
$ci =& get_instance();
$ci->load->model('Util_model');
$datatablesPortugueseBrasil = $ci->Util_model->datatablesPortugueseBrasil();

$PERFIL_USUARIO = $this->session->userdata('perfil');

if ($PERFIL_USUARIO == "Administrador") {?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'); ?>">

<!-- daterange picker -->
<link rel="stylesheet"href="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-daterangepicker/daterangepicker.css'); ?>">
<!-- bootstrap datepicker -->
<link rel="stylesheet"href="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css'); ?>">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/plugins/iCheck/all.css'); ?>">
<!-- Bootstrap Color Picker -->
<link rel="stylesheet"href="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css'); ?>">
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
        Local
    </h1>
    <ol class="breadcrumb">
        <li><a  href="<?= site_url('Home/Index/') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a  href="<?= site_url('Local/listar_view') ?>"> Local</a></li>
        <li class="active">Editar</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= $local->id_local == "" ? "Novo" : "Editar" ?> Local</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form role="form" action="<?php echo site_url('Local/editar'); ?>" method="post">
                        <div class="box-body">
                            <input type="hidden" name="id_local" value="<?=$local->id_local?>">

                            <div class="form-group">
                                <label>Campus</label>
                                <select class="form-control select2"  name="campus" style="width: 100%;">
                                    <option  value="Campus JK"<?= $local->campus == "Campus JK" ? 'selected="selected"' : ''?>>Campus JK</option>
                                    <option  value="Campus 1" <?= $local->campus == "Campus 1" ? 'selected="selected"' : ''?>>Campus 1</option>
                                    <option  value="Campus Janaúba" <?= $local->campus == "Campus Janaúba" ? 'selected="selected"' : ''?>>Campus Janaúba</option>
                                    <option  value="Campus Unaí"<?= $local->campus == "Campus Unaí" ? 'selected="selected"' : ''?>>Campus Unaí</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Dependencias <i class="fa fa-fw fa-question-circle" title=" Ex: PREDIO II - LAB. ANATOMIA - SALA 40 / PAVILHAO DE AULAS III - SALA 306 "></i> </label>
                                <input type="text" class="form-control" name="dependencia" placeholder="PREDIO - LABORATORIO - SALA" value="<?=$local->dependencia?>" required>
                            </div>


                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-default" name="opcao" value="salvar" >Salvar</button>
                            <a href="<?=site_url('Local/listar_view/')?>" class="btn btn-default">Voltar</a>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->

                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

    <?php $this->load->view('footer'); ?>
<?php } ?>



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

<!-- page script -->
<script>
    $(function () {
        $('#example1').DataTable({
            'language': <?= $datatablesPortugueseBrasil?>
        })
    })
</script>

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
        $('#carga_horaria').timepicker({
            showMeridian: false,
            showInputs: false,
            showSeconds: false,
            interval: 10
        });

        //Timepicker
        $('#carga_horaria_aulas').timepicker({
            showMeridian: false,
            showInputs: false,
            showSeconds: false,
            interval: 10
        });
    })
</script>
