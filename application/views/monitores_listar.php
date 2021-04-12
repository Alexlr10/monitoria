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
<meta content="width=device-width, initial-scale=1, maximum-scale=0.5, user-scalable=no" name="viewport">

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Monitores
    </h1>
    <ol class="breadcrumb">
        <li><a  href="<?= site_url('Home/Index/') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Monitores</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">

            <div class="box table-responsive">
                <div class="box-header">
                    <h3 class="box-title">Alunos cadastrados</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>

                            <th>Matrícula</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Perfil</th>
                            <th>Aluno</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($alunos as $usuario) { ?>
                            <tr>

                                <td><?= $usuario->matricula ?></td>
                                <td><?= $usuario->nome ?></td>
                                <td><?= $usuario->email ?></td>
                                <td><?= $usuario->perfil ?></td>
                                <td>
                                    <a href="<?= site_url('Monitoria/aluno_monitor_editar_view/'.$usuario->id_usuario) ?>"
                                       class="btn btn-primary">Virar Monitor</a>
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
                            <a href="<?= site_url('Monitoria/aluno_editar_view_2/novo') ?>"
                               class="btn btn-default btn-block btn-flat">Cadastrar Aluno</a>
                        </div>

                        <!-- /.col -->
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            <?php if (strpos("Administrador", $this->session->userdata('perfil')) !== false) {  ?>
            <div class="box table-responsive">
                <div class="box-header">
                    <h3 class="box-title">Monitores cadastrados</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>

                            <th>Matrícula</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Perfil</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($monitores as $usuario) { ?>
                            <tr>

                                <td> <?= $usuario->matricula ?></a></td>
                                <td><?= $usuario->nome ?></td>
                                <td><?= $usuario->email ?></td>
                                <td><?= $usuario->perfil ?></td>
                                <td>
                                    <a href="<?= site_url('Monitoria/monitor_aluno_editar_view/'.$usuario->id_usuario) ?>"
                                       class="btn btn-primary">Desvincular Monitor</a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>

                    </table>
                </div>
                <!-- /.box-body -->
                <?php } ?>

                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->


<?php $this->load->view('footer'); ?>


<!-- DataTables -->
<script src="<?php echo base_url(); ?>/AdminLTE-2.4.3/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>/AdminLTE-2.4.3/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>/https://code.jquery.com/jquery-3.3.1.js"></script>

<!-- page script -->
<script>
    $(function () {
        $('#example1').DataTable({
            'language': <?= $datatablesPortugueseBrasil?>
        })
    })
</script>
