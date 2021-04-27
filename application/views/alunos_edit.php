<?php $this->load->view('header');?>



  <!-- DataTables -->
  <link rel="stylesheet" href="<?= base_url('/AdminLTE-2.4.3/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');?>">


    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Alunos
      </h1>
      <ol class="breadcrumb">
          <li><a href="<?= site_url('Home/Index') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?= site_url('Monitoria/listar_view/') ?>"></i> Monitoria</a></li>
          <li><a href="<?= site_url('Monitoria/gerenciar/'.$monitoria->id_monitoria) ?>"></i> Gerenciar</a></li>
          <li><a href="<?= site_url('Monitoria/aluno_listar_view/'.$monitoria->id_monitoria) ?>"></i> Matricular</a></li>
          <li class="active">Aluno</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?= $usuario->id_usuario == "" ? "Novo" : "Editar" ?> Aluno</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form role="form" action="<?php echo site_url('Monitoria/aluno_editar/'.$monitoria->id_monitoria); ?>" method="post">
                    <div class="box-body">
                        <input type="hidden" name="id_usuario" value="<?=$usuario->id_usuario?>">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="nome" placeholder="Entre com o nome" value="<?=$usuario->nome?>" >
                        </div>

                        <div class="form-group">
                            <label>Matricula</label>
                            <input type="text" class="form-control" name="matricula" placeholder="Entre com a matricula" value="<?=$usuario->matricula?>" >
                        </div>

                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="text" class="form-control" name="email" placeholder="Entre com o e-mail" value="<?=$usuario->email?>" >
                        </div>

                        <div class="form-group">
                            <label>Telefonee</label>
                            <input type="text" class="form-control" name="telefone" placeholder="Entre com o telefone" value="<?=$usuario->telefone?>" >
                        </div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-default" name="opcao" value="salvar" >Salvar</button>
                        <a href="<?=site_url('Monitoria/aluno_listar_view/'.$monitoria->id_monitoria)?>" class="btn btn-default">Voltar</a>
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


<!-- DataTables -->
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/datatables.net/js/jquery.dataTables.min.js');?>"></script>
<script src="<?= base_url('/AdminLTE-2.4.3/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js');?>"></script>


<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable({
      'language': <?= $datatablesPortugueseBrasil?>
    })
  })
</script>
