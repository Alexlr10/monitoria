<?php
$this->load->helper('url');
$this->load->library('session');
$ID_USUARIO = $this->session->userdata('id_usuario');
$NOME_USUARIO = $this->session->userdata('nome');
$PERFIL_USUARIO = $this->session->userdata('perfil');

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Gestão de Monitorias</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=0.9, maximum-scale=0.8, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?=base_url('/AdminLTE-2.4.3/bower_components/bootstrap/dist/css/bootstrap.min.css');?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?=base_url('/AdminLTE-2.4.3/bower_components/font-awesome/css/font-awesome.min.css');?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?=base_url('/AdminLTE-2.4.3/bower_components/Ionicons/css/ionicons.min.css');?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=base_url('/AdminLTE-2.4.3/dist/css/AdminLTE.min.css');?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?=base_url('/AdminLTE-2.4.3/dist/css/skins/_all-skins.min.css');?>">
    <!-- Morris chart -->
    <link rel="stylesheet" href="<?=base_url('/AdminLTE-2.4.3/bower_components/morris.js/morris.css');?>">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?=base_url('/AdminLTE-2.4.3/bower_components/jvectormap/jquery-jvectormap.css');?>">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?=base_url('/AdminLTE-2.4.3/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css');?>">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?=base_url('/AdminLTE-2.4.3/bower_components/bootstrap-daterangepicker/daterangepicker.css');?>">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="<?=base_url('/AdminLTE-2.4.3/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css');?>">


    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="sidebar-mini wysihtml5-supported skin-black-light">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="<?= site_url('Home/Index') ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>G</b>M</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">Gestão de Monitorias</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?=base_url('/AdminLTE-2.4.3/dist/img/user2-160x160.jpg');?>" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?=$NOME_USUARIO?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="<?=base_url('/AdminLTE-2.4.3/dist/img/user2-160x160.jpg');?>" class="img-circle" alt="User Image">

                                <p>
                                    <?=$NOME_USUARIO?>
                                </p>
                            </li>

                            <li class="user-footer">
                                <div class="pull-left">

                                    <form id="form_edit_user" action="<?php echo site_url('usuarios/editar_view/'.$ID_USUARIO); ?>" method="post">
                                        <input type="hidden" name='id_usuario' value="<?php $ID_USUARIO?>">
                                        <a  href="#" onclick="document.getElementById('form_edit_user').submit()" class="btn btn-default btn-flat">Editar</a>
                                    </form>
                                </div>

                                <div class="pull-right">
                                    <a href="<?=site_url('Home/logout'); ?>" class="btn btn-default btn-flat">Sair</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?=base_url('/AdminLTE-2.4.3/dist/img/user2-160x160.jpg');?>" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p><?=$NOME_USUARIO?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> <?=$PERFIL_USUARIO?></a>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MENU DE NAVEGAÇÃO</li>



                <?php if (strpos("Administrador", $this->session->userdata('perfil')) !== false) {  ?>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-dashboard"></i> <span>Gerenciar</span>
                            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?=site_url('Periodo/listar_view')?>"><i class="fa fa-circle-o"></i> Semestre letivo</a></li>
                            <li><a href="<?= site_url('Disciplinas/listar_view')?>"><i class="fa fa-circle-o"></i> Disciplinas</a></li>
                            <li><a href="<?= site_url('Local/listar_view') ?>"><i class="fa fa-circle-o"></i> Local</a></li>
                            <li><a href="<?=site_url('Usuarios/listar_view')?>"><i class="fa fa-circle-o"></i> Usuários</a></li>

                        </ul>
                    </li>
                    <li><a href="<?=site_url('Usuarios/listar_monitores_planilha')?>"><i class="fa fa-circle-o"></i>Planilha Monitores</a></li>

                    <li><a href="<?=site_url('Relatorio/listar_view')?>"><i class="fa fa-calendar-o"></i> Atestado de Frequência</a></li>
                    <li><a href="<?=site_url('Usuarios/listar_professor_view')?>"><i class="fa fa-users"></i> <span>Professores</span></a></li>

                <?php } ?>

                <?php if (strpos("Administrador", $this->session->userdata('perfil')) !== false or strpos("Professor", $this->session->userdata('perfil')) !== false) {  ?>
                    <li><a href="<?=site_url('Usuarios/listar_monitores_view')?>"><i class="fa  fa-graduation-cap"></i> <span>Monitores</span></a></li>
                <?php } ?>

                <li><a href="<?=site_url('Monitoria/listar_view')?>"><i class="fa  fa-book"></i> <span>Monitorias</span></a></li>


            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
