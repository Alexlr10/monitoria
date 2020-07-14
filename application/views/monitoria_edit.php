<?php $this->load->view('header'); ?>
<?php
//carrega a traducao em portugues para as tabelas
$ci =& get_instance();
$ci->load->model('Util_model');
$datatablesPortugueseBrasil = $ci->Util_model->datatablesPortugueseBrasil();

$ID_USUARIO = $this->session->userdata('id_usuario');
$NOME_USUARIO = $this->session->userdata('nome');
$PERFIL_USUARIO = $this->session->userdata('perfil');
$ID_USUARIO = $this->session->userdata('id_usuario');

if ($PERFIL_USUARIO == "Administrador" or $PERFIL_USUARIO == "Professor") {
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
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.js"></script>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Cadastro de Monitoria
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('Home/Index') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?= site_url('Monitoria/listar_view/') ?>"></i> Monitoria</a></li>
            <li class="active">Editar</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">

                        <h3 class="box-title"> Cadastrar Monitoria</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- antes ia para o usuarios/editar -->
                        <form role="form" ondblclick="habilitar()"
                              action="<?php echo site_url('monitoria/cadastro_monitoria/' . $monitoria->id_monitoria); ?>"
                              method="post">

                            <div class="box-body">
                                <div class="form-group">
                                    <label>Selecione a Disciplina</label>
                                    <select name="id_disciplina" class="form-control select2" style="width: 100%;">
                                        <?php foreach ($disciplinas as $disciplina) { ?>
                                            <option
                                                value="<?= $disciplina->id_disciplina ?>" <?= $disciplina->id_disciplina == $monitoria->id_disciplina ? 'selected="selected"' : '' ?>>
                                                <?= $disciplina->nome . ', ' . $disciplina->curso ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <?php if ($monitoria->id_monitoria == "novo") { ?>
                                        <div class="form-group">
                                            <label>Selecione o Monitor</label>
                                            <select name="id_monitor" class="form-control select2" style="width: 100%;">

                                                <?php foreach ($usuarios as $usuario) { ?>
                                                    <option
                                                        value="<?= $usuario->id_usuario ?>" <?= $usuario->id_usuario == $monitoria->id_monitor ? 'selected="selected"' : '' ?>>
                                                        <?= $usuario->nome ?>
                                                    </option>
                                                <?php } ?>


                                            </select>
                                        </div>

                                        <?php if ($PERFIL_USUARIO == 'Administrador') { ?>
                                            <div class="form-group">
                                                <label>Selecione o Professor</label>
                                                <select name="id_professor" class="form-control select2"
                                                        style="width: 100%;">
                                                    <?php foreach ($usuariosP as $usuario) { ?>
                                                        <option
                                                            value="<?= $usuario->id_usuario ?>" <?= $usuario->id_usuario == $monitoria->id_professor ?>>
                                                            <?= $usuario->nome ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                        <?php } else { ?>
                                            <div class="form-group">
                                                <label>Selecione o Professor</label>
                                                <select name="id_professor" class="form-control select2"
                                                        style="width: 100%;">
                                                    <option
                                                        value="<?= $ID_USUARIO ?>" <?= $ID_USUARIO == $usuario->id_usuario ? 'selected="selected"' : '' ?>>
                                                        <?= $NOME_USUARIO ?>
                                                    </option>

                                                </select>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="form-group ">
                                            <label>Selecione o Monitor</label>
                                            <select name="id_monitor" id="id_monitor" class="form-control select2 "
                                                    style="width: 100%;"
                                                    disabled>

                                                <?php foreach ($usuarios as $usuario) { ?>
                                                    <option readonly="readonly"
                                                            value="<?= $usuario->id_usuario ?>" <?= $usuario->id_usuario == $monitoria->id_monitor ? 'selected="selected"' : '' ?>>
                                                        <?= $usuario->nome ?>
                                                    </option>
                                                <?php } ?>


                                            </select>
                                        </div>

                                        <?php if ($PERFIL_USUARIO == 'Administrador') { ?>
                                            <div class="form-group">
                                                <label>Selecione o Professor</label>
                                                <select name="id_professor" class="form-control select2"
                                                        style="width: 100%;" disabled>
                                                    <?php foreach ($usuariosP as $usuario) { ?>
                                                        <option
                                                            value="<?= $usuario->id_usuario ?>" <?= $usuario->id_usuario == $monitoria->id_professor ?>>
                                                            <?= $usuario->nome ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                        <?php } else { ?>
                                            <div class="form-group">
                                                <label>Selecione o Professor</label>
                                                <select name="id_professor" class="form-control select2"
                                                        style="width: 100%;">
                                                    <option
                                                        value="<?= $ID_USUARIO ?>" <?= $ID_USUARIO == $usuario->id_usuario ? 'selected="selected"' : '' ?>>
                                                        <?= $NOME_USUARIO ?>
                                                    </option>

                                                </select>
                                            </div>
                                        <?php } ?>


                                    <?php } ?>


                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Edital Vinculado</label>
                                                <input type="text" style="text-transform:uppercase" class="form-control"
                                                       name="numero_edital" placeholder="Digite o Edital"
                                                       value="<?= $monitoria->numero_edital ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Selecione o Periodo</label>
                                                <select name="id_periodo" class="form-control select2"
                                                        style="width: 100%;">
                                                    <?php foreach ($periodos as $periodo) { ?>
                                                        <option
                                                            value="<?= $periodo->id_periodo ?>" <?= $periodo->id_periodo == $monitoria->id_periodo ? 'selected="selected"' : '' ?>>
                                                            <?= $periodo->ano . '/' . $periodo->semestre ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Remunerada:</label>
                                                <div class="input-group text">
                                                    <div class="input-group-addon">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <select class="form-control select2" checked="checked"
                                                            id="monitoria_remunerada"
                                                            name="monitoria_remunerada"
                                                            value="<?= $monitoria->monitoria_remunerada ?>">

                                                        <option id="Sim"
                                                                value="Sim"<?= $monitoria->monitoria_remunerada == "Sim" ? 'selected="selected"' : '' ?>>
                                                            Sim
                                                        </option>
                                                        <option id="Nao"
                                                                value="Nao"<?= $monitoria->monitoria_remunerada == "Nao" ? 'selected="selected"' : '' ?>>
                                                            Não
                                                        </option>
                                                    </select>


                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <!-- /.col -->


                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Data Inicio:</label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon" id="data_inicio">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input class="form-control pull-right"
                                                           value="<?php echo date('Y-m-d'); ?>" type="date"
                                                           name="data_inicio" required>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Data Fim:</label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon" id="data_fim">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input class="form-control pull-right"
                                                           value="<?= $monitoria->data_fim ?>" type="date"
                                                           name="data_fim" required>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Banco:</label>


                                                <select class="form-control select2" id="banco"
                                                        name="banco">

                                                    <option value="">---</option>
                                                    <option
                                                        value="001" <?= $monitoria->banco == "001" ? "selected" : "" ?>>
                                                        Banco do Brasil
                                                    </option>
                                                    <option
                                                        value="104"<?= $monitoria->banco == "104" ? "selected" : "" ?>>
                                                        Banco Caixa
                                                    </option>
                                                    <option
                                                        value="341"<?= $monitoria->banco == "341" ? "selected" : "" ?>>
                                                        Banco Itaú
                                                    </option>
                                                    <option
                                                        value="237"<?= $monitoria->banco == "237" ? "selected" : "" ?>>
                                                        Banco Bradesco
                                                    </option>
                                                    <option
                                                        value="033"<?= $monitoria->banco == "033" ? "selected" : "" ?>>
                                                        Banco Santader
                                                    </option>
                                                    <option
                                                        value="077"<?= $monitoria->banco == "077" ? "selected" : "" ?>>
                                                        Banco Inter
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.row -->
                                    <div class="row">
                                        <!-- /.col -->


                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Agência:</label>
                                                <input type="text" style="text-transform:uppercase" id="agencia"
                                                       class="form-control" name="agencia"
                                                       placeholder="Número da agência"
                                                       value="<?= $monitoria->agencia ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Conta:</label>
                                                <input type="text" style="text-transform:uppercase" id="conta"
                                                       class="form-control" name="conta" placeholder="Número da conta"
                                                       value="<?= $monitoria->conta ?>">
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>CPF:</label>

                                                <input type="text" style="text-transform:uppercase" id="cpf"
                                                       class="form-control" name="cpf"
                                                       maxlength="11" placeholder="___-___-___."
                                                       value="<?= $monitoria->cpf ?>">

                                            </div>
                                        </div>

                                    </div>

                                    <div>
                                        <label>Plano de Aula:</label>
                                        <textarea class="form-control" id="plano_aula" name="plano_aula"
                                                  value="<?= $monitoria->plano_aula ?>"><?= $monitoria->plano_aula ?></textarea>
                                    </div>

                                    <br>

                                    <div class="form-check" >
                                        <input type="checkbox" required="true" class="form-check-input" id="exampleCheck1">
                                        <label class="form-check-label" for="exampleCheck1">Certifico que o aluno está
                                            presente e concorda com o plano de aula/monitoria apresentado</label>
                                    </div>


                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-default" name="opcao" value="salvar">Salvar
                                        </button>
                                        <a href="<?= site_url('Monitoria/listar_view/' . $PERFIL_USUARIO . '/' . $ID_USUARIO) ?>"
                                           class="btn btn-default">Voltar</a>
                                    </div>


                                </div>


                            </div>
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
<script
    src="<?= base_url('/AdminLTE-2.4.3/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js'); ?>"></script>


<!-- InputMask -->
<script src="<?= base_url('/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.js'); ?>"></script>
<script src="<?= base_url('/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>
<script src="<?= base_url('/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.extensions.js'); ?>"></script>
<!-- date-range-picker -->
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/moment/min/moment.min.js'); ?>"></script>
<script
    src="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-daterangepicker/daterangepicker.js'); ?>"></script>
<!-- bootstrap datepicker -->
<script
    src="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'); ?>"></script>
<!-- bootstrap color picker -->
<script
    src="<?= base_url('/AdminLTE-2.4.3/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js'); ?>"></script>
<!-- bootstrap time picker -->
<script src="<?= base_url('/AdminLTE-2.4.3/plugins/timepicker/bootstrap-timepicker.min.js'); ?>"></script>

<!-- page script -->

<!-- CK Editor -->
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/ckeditor/ckeditor.js') ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?= base_url('/AdminLTE-2.4.3/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') ?>"></script>
<script>
    CKEDITOR.replace('plano_aula', {
        toolbar: 'Basic',

    });
    CKEDITOR.instances.editor1.setData('', function () {
        this.checkDirty();  // true
    });
</script>

<script>
    $("#id_professor").css("pointer-events", "none");

</script>
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
            timePicker24Hour: false,
            showMeridian: false,
            showInputs: false,
            showSeconds: false,
            interval: 10,
            startTime: 12,
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

<script type="text/javascript">
    $(function () {
        $("#monitoria_remunerada").change(function () {
            var st = $(this).val();
            if (st == 'Nao') {
                $("#agencia").prop("disabled", true);
                $("#banco").prop("disabled", true);
                $("#conta").prop("disabled", true);
                $("#cpf").prop("disabled", true);
            } else {
                $("#agencia").prop("disabled", false);
                $("#banco").prop("disabled", false);
                $("#conta").prop("disabled", false);
                $("#cpf").prop("disabled", false);
            }
        })
    });
</script>

