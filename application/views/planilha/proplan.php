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


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Monitores
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('Home/Index/') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Monitores</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">

            <div class="box table-responsive">
                <div class="box-header">
                    <h3 class="box-title">Monitores cadastrados</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Bolsista</th>
                            <th>Matricula</th>
                            <th>CPF</th>
                            <th>Unid/Curso</th>
                            <th>Banco</th>
                            <th>Agência</th>
                            <th>Conta</th>
                            <th>Data</th>
                            <th>Total</th>

                        </tr>
                        </thead>
                        <tbody>


                            <tr>
                                <?php foreach ($monitorias as  $usuario) { ?>
                                <td> <?= $usuario->nome ?></td>
                                <td> <?= $usuario->matricula ?></td>
                                <td> <?= $usuario->cpf ?></td>
                                <td><?= $usuario->unidade_academica ?></td>
                                <td><?= $usuario->banco ?></td>
                                <td><?= $usuario->agencia ?></td>
                                <td><?= $usuario->conta ?></td>
                                <td><?= $usuario->data ?></td>
                                <td> <?= (int)$usuario->somatorioHorario ?></td>
                                <td>R$ <?= round(8.33333*(int)$usuario->somatorioHorario) ?></td>

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
                            <a href="<?= site_url('usuarios/editar_view/novo') ?>"
                               class="btn btn-default btn-block btn-flat">Novo Usuário</a>
                        </div>
                        <!-- /.col -->
                    </div>
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


<?php $this->load->view('footer'); ?>


<!-- DataTables -->
<script
    src="<?php echo base_url(); ?>/AdminLTE-2.4.3/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script
    src="<?php echo base_url(); ?>/AdminLTE-2.4.3/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>


<!-- page script -->
<script>
    $(function () {
        $('#example1').DataTable({
            'language': <?= $datatablesPortugueseBrasil?>
        })
    })
</script>
