<?php $this->load->view('header');?>
<?php
    //carrega a traducao em portugues para as tabelas
    $ci =& get_instance();
    $ci->load->model('Util_model');
    $datatablesPortugueseBrasil = $ci->Util_model->datatablesPortugueseBrasil();
?>

  <!-- DataTables -->
  <link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');?>">

  <!-- daterange picker -->
  <link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-daterangepicker/daterangepicker.css');?>">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css');?>">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/plugins/iCheck/all.css');?>">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css');?>">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/plugins/timepicker/bootstrap-timepicker.min.css');?>">

  <!-- Select2 -->
  <link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/bower_components/select2/dist/css/select2.min.css');?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/dist/css/AdminLTE.min.css');?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/dist/css/skins/_all-skins.min.css');?>">


   <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Aula <?= date('d/m/Y', strtotime($aula->data)) ?>
      </h1>
      <ol class="breadcrumb">
          <li><a href="<?= site_url('Home/Index') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?= site_url('Monitoria/listar_view/') ?>"></i> Monitoria</a></li>
          <li><a href="<?= site_url('Monitoria/gerenciar/'.$aula->id_monitoria) ?>"></i> Gerenciar</a></li>
          <li class="active">Editar</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?= $aula->id_aula == "" ? "Novo" : "Editar" ?> Aula</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form role="form" action="<?php echo site_url('Monitoria/aula_editar/'.$aula->id_monitoria ); ?>" method="post">
                    <div class="box-body">
                        <input type="hidden" name="id_aula" value="<?=$aula->id_aula?>">
                        <input type="hidden" name="id_monitoria" value="<?=$aula->id_monitoria?>">


                        <?php if ($aula->id_aula != "") { ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Aula cadastrada em:</label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                      <input type="text" class="form-control pull-right" id="cadastrado" name="cadastrado" value="<?= $ci->Util_model->formatarTimestamp($aula->cadastrado); ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Aula atualizada em:</label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                      <input type="text" class="form-control pull-right" id="atualizado" name="atualizado" value="<?= $ci->Util_model->formatarTimestamp($aula->atualizado); ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                        <?php } ?>

                        <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Data:</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon" id="data" >
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input class="form-control pull-right"value="<?php echo date('Y-m-d');?>"
                                                   type="date"  name="data" required>

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
                                        <input type="text" class="form-control timepicker" name="horario_inicio" id="horario_inicio" value="<?=$aula->horario_inicio?>">
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
                                        <input type="text" class="form-control timepicker" name="horario_fim" id="horario_fim" value="<?=$aula->horario_fim?>">
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col -->

                        </div>
                        <!-- /.row -->


                        <div class="form-group">
                            <label>Atividades</label>
                            <input type="text" class="form-control" name="atividades" placeholder="Entre com a descrição da atividade realizada" value="<?=$aula->atividades?>">
                        </div>

                        <div class="form-group">
                            <label>Local</label>
                            <select class="form-control select2" style="width: 100%;" name="id_local">
                                <?php foreach ($locais as $local) { ?>
                                    <option value="<?=$local->id_local?>" <?= $aula->id_local == $local->id_local? 'selected="selected"' : '' ?>>
                                        <?= $local->dependencia . ', ' . $local->campus ?>
                                    </option>
                                <?php }?>
                            </select>
                        </div>

                    </div>
                    <!-- /.box-body -->
                    <?php $sei = 2;
                   // $aula = $this->Aula_model->getAulaById($id_aula);
                    $diaAtual= date('Y/m/d',strtotime('today'));
                    $somaDias=date('Y/m/d', strtotime('+5 days', strtotime($diaAtual)));
                    //var_dump($somaDias);

                    //Condição para não conseguir fazer edição após 3 dias de cadastro das atividades e adicionar nova monitoria


                    if (strtotime($somaDias) >= strtotime($diaAtual)) { ?>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-default" name="opcao" id="opcao" value="salvar" >Salvar</button>
                        <a href="<?=site_url('Monitoria/gerenciar/'.$aula->id_monitoria)?>" class="btn btn-default">Voltar</a>
                    </div>
                    <?php } else {?>

                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                            Não é possível adicionar adicionar atividades de aula após 5 dias que a atividade ocorreu.
                            Por favor procure a DAA - PROGRAD.
                        </div>


                    <?php } ?>
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


<!-- DataTables -->
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/datatables.net/js/jquery.dataTables.min.js');?>"></script>
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js');?>"></script>


<!-- InputMask -->
<script src="<?= base_url('/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.js');?>"></script>
<script src="<?= base_url('/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.date.extensions.js');?>"></script>
<script src="<?= base_url('/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.extensions.js');?>"></script>
<!-- date-range-picker -->
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/moment/min/moment.min.js');?>"></script>
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-daterangepicker/daterangepicker.js');?>"></script>
<!-- bootstrap datepicker -->
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');?>"></script>
<!-- bootstrap color picker -->
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js');?>"></script>
<!-- bootstrap time picker -->
<script src="<?= base_url('/AdminLTE-2.4.3/plugins/timepicker/bootstrap-timepicker.min.js');?>"></script>

<script src="<?= base_url('/jquery.js');?>"</script>
<script src="<?= base_url('/build/jquery.datetimepicker.full.min.js');?>"></script>

<script>
  $(function () {

    //Initialize Select2 Elements
    $('.select2').select2();



   $('#example1').DataTable({
        'language': <?= $datatablesPortugueseBrasil?>
    });


    //Timepicker
    $('#horario_inicio').timepicker({
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
      $(function () {
          $('#data').datetimepicker({
              inline: true,
              sideBySide: true
          });
      });

  })

</script>

<script type="text/javascript">
    $(function () {
        $("#data").change(function () {
            var st = $(this).val();
            if(st == '12-03-2020'){
                $("#opcao").prop("disabled", true);
                $("#banco").prop("disabled", true);
                $("#conta").prop("disabled", true);
                $("#cpf").prop("disabled", true);
            }
            else {
                $("#agencia").prop("disabled", false);
                $("#banco").prop("disabled", false);
                $("#conta").prop("disabled", false);
                $("#cpf").prop("disabled", false);
            }
        })
    });
</script>

