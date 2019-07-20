<?php

namespace TPV;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <![endif]-->
    <title><?php echo $site_name ?> | <?php echo $site_slogan ?></title>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta name="description" content="<?php echo $site_description ?>">
    <meta name="author" content="<?php echo $site_author; ?>">
    <!-- =-=-=-=-=-=-= Mobile Specific =-=-=-=-=-=-= -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- =-=-=-=-=-=-= Favicons Icon =-=-=-=-=-=-= -->
    <link rel="icon" href="<?php echo $site_images; ?>favicon.ico" type="image/x-icon" />
    <!-- =-=-=-=-=-=-= Bootstrap CSS Style =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="<?php echo $site_css; ?>bootstrap.css">
    <!-- =-=-=-=-=-=-= Template CSS Style =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="<?php echo $site_css; ?>css.css">
    <link rel="stylesheet" href="<?php echo $site_css; ?>skins.css">
    <!-- =-=-=-=-=-=-= Font Awesome =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="<?php echo $site_css; ?>font-awesome.css" type="text/css">
    <!-- =-=-=-=-=-=-= Animation =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="<?php echo $site_css; ?>animate.min.css" type="text/css">
    <!-- =-=-=-=-=-=-= Responsive Media =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="<?php echo $site_css; ?>responsive-media.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo $site_js; ?>select2/css/select2.min.css">
    <!-- toastr -->
    <link rel="stylesheet" href="<?php echo $site_assets ?>plugins/toastr/toastr.min.css" />
    <!-- JavaScripts -->
    <script src="<?php echo $site_js; ?>modernizr.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->

    <!-- =-=-=-=-=-=-= JQUERY =-=-=-=-=-=-= -->
    <script src="<?php echo $site_js; ?>jquery.min.js"></script>
    <!-- <link rel="stylesheet" href="<?php echo $site_assets ?>js/jquery-ui.css"/> -->
    <!-- Moment JS -->
    <script src="<?php echo $site_js; ?>moment.es.js"></script>
    <!-- =-=-=-=-=-=-= ANGULARJS =-=-=-=-=-=-= -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.8/angular.min.js"></script>
    <script src="<?php echo $site_js; ?>angular-moment.min.js"></script>
    <!-- Bootstrap Core Css  -->
    <!-- Bootstrap Core Css  -->
    <script src="<?php echo $site_js; ?>bootstrap.min.js"></script>
    <!-- =-=-=-=-=-=-= ASSETS =-=-=-=-=-=-= -->
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo $site_assets; ?>plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
    <script src="<?php echo $site_assets; ?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo $site_assets; ?>plugins/iCheck/all.css">
    <link rel="stylesheet" href="<?php echo $site_assets; ?>plugins/iCheck/square/blue.css">
    <script src="<?php echo $site_assets; ?>plugins/iCheck/iCheck.min.js"></script>
    <script>
        // variables globales
        var IdUsuario = <?php echo $_SESSION['id'] ?>
    </script>
    <!-- Si hay archivo para la sección , lo cargamos -->
    <?php
    if (file_exists(ROOTPATH . 'scripts/' . $_GET['page'] . '.ctrl.js')) {
        ?>
        <script src="<?php echo $site_scripts . $_GET['page']; ?>.ctrl.js"></script>
        <script src="<?php echo $site_scripts ?>angular.directives.js"></script>
    <?php
    }
    ?>
</head>

<body class="skin-blue sidebar-mini">
    <?php
    if (!is_logged()) {
        // No session --> login page
        include_once $render_page;
    } else {
        ?>
        <div class="wrapper">
            <!-- header -->
            <header class="main-header">
                <!-- Logo -->
                <a href="<?php echo $site_url ?>" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini">LT</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><img src="<?php echo $site_images ?>la_terraza.png"></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>

                    <div class="navbar-custom-menu navbar-left">
                        <ul class="nav navbar-nav">
                            <?php
                            foreach ($menu_top as $name => $menu) {
                                if (in_array($user['rol'], $menu['permissions'])) {
                                    ?>
                                    <li class="dropdown user user-menu">
                                        <a href="<?php echo $menu['url'] ?>">
                                            <i class="<?php echo $menu['icon'] ?>"></i> &nbsp;
                                            <span class="hidden-xs"> <?php echo $menu['label'] ?></span>
                                        </a>
                                    </li>
                                <?php
                                }
                            }
                            if ($_GET['page'] == 'tpv') {
                                ?>
                                <li class="dropdown user user-menu">
                                    <a id="btn-usuario-tpv">
                                        <i class="fa fa-user"></i> &nbsp;
                                        <span class="hidden-xs" id="nombre-atiende"> <?php echo $_SESSION['username'] ?></span>
                                    </a>
                                </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <?php
                            if ($_GET['page'] == 'tpv') {
                                ?>
                                <li class="user user-menu">
                                    <a id="btn-fullscreen" class="enterFullScreen">
                                        <i class="fa fa-arrows-alt"></i>
                                    </a>
                                </li>
                            <?php
                            }
                            ?>
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="/logout"><i class="fa fa-sign-out"></i> Salir</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- aside menu -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar" style="height: auto;">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="<?php echo $site_images ?>chef.png" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p></p>
                            <p><?php echo $user['usuario']; ?></p>
                            <p><?php echo $user['rol']; ?></p>
                        </div>
                    </div>

                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu tree" data-widget="tree">
                        <li class="header">MENÚ PRINCIPAL</li>
                        <li>
                            <a href="<?php echo $site_url ?>"><i class="fa fa-home"></i> <span>Inicio</span></a>
                        </li>
                        <?php
                        if ($user['rol'] == 'ADMIN') {
                            ?>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-database"></i> <span>Administración</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="<?php echo $site_url ?>administracion/unidades"><i class="fa fa-circle-o"></i>Unidades</a></li>
                                    <li><a href="<?php echo $site_url ?>administracion/categorias/ingredientes"><i class="fa fa-circle-o"></i>Cat. Ingredientes</a></li>
                                    <li><a href="<?php echo $site_url ?>administracion/categorias/productos"><i class="fa fa-circle-o"></i>Cat. Productos</a></li>
                                    <li><a href="<?php echo $site_url ?>administracion/tipos/productos"><i class="fa fa-circle-o"></i>Tipo Productos</a></li>
                                    <li><a href="<?php echo $site_url ?>administracion/categorias/menus"><i class="fa fa-circle-o"></i>Cat. Menus</a></li>
                                    <li><a href="<?php echo $site_url ?>administracion/ingredientes"><i class="fa fa-circle-o"></i>Ingredientes</a></li>
                                    <li><a href="<?php echo $site_url ?>administracion/productos"><i class="fa fa-circle-o"></i>Productos</a></li>
                                    <li><a href="<?php echo $site_url ?>administracion/menus"><i class="fa fa-circle-o"></i>Menús</a></li>
                                    <!-- <li><a href="<?php echo $site_url ?>administracion/paquetes"><i class="fa fa-circle-o"></i>Promociones y Paquetes</a></li> -->
                                    <li><a href="<?php echo $site_url ?>administracion/mesas"><i class="fa fa-circle-o"></i>Mesas</a></li>
                                    <li><a href="<?php echo $site_url ?>administracion/proveedores"><i class="fa fa-circle-o"></i>Proveedores</a></li>
                                    <!-- <li><a href="<?php echo $site_url ?>administracion/clientes"><i class="fa fa-circle-o"></i>Clientes</a></li> -->
                                    <li><a href="<?php echo $site_url ?>administracion/empleados"><i class="fa fa-circle-o"></i>Empleados</a></li>
                                    <li><a href="<?php echo $site_url ?>administracion/usuarios"><i class="fa fa-circle-o"></i>Usuarios</a></li>
                                </ul>
                            </li>
                        <?php
                        }
                        ?>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-user"></i> <span>Ajustes de cuenta</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <!-- <li><a href="<?php echo $site_url ?>perfil"><i class="fa fa-circle-o"></i>Mi Perfil</a></li>
                                        <li><a href="<?php echo $site_url ?>cambiar-password"><i class="fa fa-circle-o"></i>Cambiar contraseña</a></li> -->
                                <li><a href="<?php echo $site_url ?>logout"><i class="fa fa-circle-o"></i>Salir</a></li>
                            </ul>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
            <div class="content-wrapper">
                <?php include_once $render_page; ?>
            </div>
            <footer class="main-footer">
                <div class="row">
                    <div class="col-md-12" style="text-align: center;">
                        <strong>TPV</strong>, Restaurant TPV + Inventario by @alecsvaldez
                    </div>
                </div>
            </footer>
        </div>
    <?php
    }
    ?>


    <!-- Jquery Easing -->
    <script src="<?php echo $site_js; ?>easing.js"></script>
    <!-- Numbers Animation   -->
    <script src="<?php echo $site_js; ?>jquery.countTo.js"></script>
    <!-- TimeAgo   -->
    <script src="<?php echo $site_js; ?>jquery.timeago.js"></script>
    <!-- Template Core JS -->
    <script src="<?php echo $site_js; ?>lte/plugins.js"></script>
    <script src="<?php echo $site_js; ?>lte/adminlte.js"></script>
    <script src="<?php echo $site_js; ?>custom.js"></script>
    <!-- Include a polyfill for ES6 Promises (optional) for IE11 and Android browser -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
    <!-- Select2 -->
    <script src="<?php echo $site_js; ?>select2/js/select2.full.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="<?php echo $site_assets ?>plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- Include a polyfill for ES6 Promises (optional) for IE11 -->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>
    <!-- toastr -->
    <script src="<?php echo $site_assets ?>plugins/toastr/toastr.min.js"></script>
    <?php
    if (isset($datatables)) {
        ?>
        <!-- DataTables -->
        <script src="<?php echo $site_js ?>datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo $site_js ?>datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <link href="<?php echo $site_js ?>datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
        <script>
            $(function() {
                $('#datatable').DataTable({
                    'autoWidth': false,
                    'ordering': true,
                    'iDisplayLength': 15
                })
            })
        </script>
    <?php
    }
    ?>
    <script>
        jQuery(document).ready(function($) {
            $("time.timeago").timeago();
            $('.select2').select2()
            $('.datepicker').datepicker({
                format: {
                    /*
                     * Say our UI should display a week ahead,
                     * but textbox should store the actual date.
                     * This is useful if we need UI to select local dates,
                     * but store in UTC
                     */
                    toDisplay: function(date, format, language) {
                        var d = new Date(date);
                        d.setDate(d.getDate());
                        return d.toISOString().substring(0, 10);
                    },
                    toValue: function(date, format, language) {
                        var d = new Date(date);
                        d.setDate(d.getDate());
                        return new Date(d);
                    }
                }
            });
            $('input[type=checkbox]:not(.natural)').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                //increaseArea: '20%' // optional
            });
            $('.numeric').keydown(function(e) {
                var keys = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
                return (
                    keys == 8 ||
                    keys == 9 ||
                    keys == 13 ||
                    keys == 46 ||
                    keys == 110 ||
                    keys == 86 ||
                    keys == 190 ||
                    (keys >= 35 && keys <= 40) ||
                    (keys >= 48 && keys <= 57) ||
                    (keys >= 96 && keys <= 105));
            });
        });
    </script>
</body>

</html>