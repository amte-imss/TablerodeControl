<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>assets/tablero_tpl/img/apple-icon.png" />
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/tablero_tpl/img/favicon.ico" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

        <title>
            <?php echo (!is_null($title)) ? "{$title}&nbsp;|" : "" ?>
            <?php echo (!is_null($main_title)) ? $main_title : "Tablero de control" ?>
        </title>

        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta name="viewport" content="width=device-width" />

        <!-- Bootstrap core CSS     -->
        <link href="<?php echo base_url(); ?>assets/tablero_tpl/css/bootstrap.min.css" rel="stylesheet" />

        <!--  Material Dashboard CSS    -->
        <link href="<?php echo base_url(); ?>assets/tablero_tpl/css/material-dashboard.css" rel="stylesheet"/>

        <!--  CSS for Demo Purpose, don't include it in your project     -->
        <link href="<?php echo base_url(); ?>assets/tablero_tpl/css/demo.css" rel="stylesheet" />

        <!--     Fonts and icons     -->
        <link href="<?php echo base_url(); ?>assets/third-party/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet' type='text/css'>
        <script type="text/javascript">
            var url = "<?php echo base_url(); ?>";
            var site_url = "<?php echo site_url(); ?>";
        </script>
        <!--   Core JS Files   -->
        <script src="<?php echo base_url(); ?>assets/tablero_tpl/js/jquery-3.1.0.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/tablero_tpl/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/tablero_tpl/js/material.min.js" type="text/javascript"></script>
        <?php
        if (isset($css_files) && !empty(($css_files)))
        {
            foreach ($css_files as $key => $css)
            {
                echo css($css);
            }
        }
        if (isset($js_files) && !empty(($js_files)))
        {
            foreach ($js_files as $key => $js)
            {
                echo js($js);
            }
        }
        ?>
    </head>

    <body>
        <div id="overlay">
            <img src="<?php echo base_url(); ?>assets/tablero_tpl/img/loader.gif" alt="Loading" /><br/>
            Cargando...
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <?php
                    if (isset($cuerpo_modal))
                    {
                        echo $cuerpo_modal;
                    }
                    ?>                    
                </div>
            </div>
        </div>

        <div class="wrapper">
            <div class="sidebar" 
                 data-color="purple" 
                 data-image="<?php echo base_url(); ?>assets/tablero_tpl/img/Escultura-Ortiz.jpg">
                <!--
                    Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"
                    Tip 2: you can also add an image using data-image tag
                -->
                <div class="logo">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <img src="<?php echo base_url(); ?>assets/tablero_tpl/img/ces.png" />
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <img src="<?php echo base_url(); ?>assets/tablero_tpl/img/imss.png" />
                        </div>
                    </div>
                    <!--a href="http://www.creative-tim.com" class="simple-text">
                            Coordinación de Educación en Salud
                    </a-->
                </div>

                <div class="sidebar-wrapper">
                    <?php
                    if (isset($menu))
                    {
                        echo render_menu($menu);
                    }
                    ?>
                </div>
            </div>

            <div class="main-panel">
                <nav class="navbar navbar-transparent navbar-absolute">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#">Tablero de control</a>
                        </div>
                        <div class="collapse navbar-collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li>
                                    <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="material-icons">dashboard</i>
                                        <p class="hidden-lg hidden-md">Dashboard</p>
                                    </a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="material-icons">notifications</i>
                                        <span class="notification">5</span>
                                        <p class="hidden-lg hidden-md">Notifications</p>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Mike John responded to your email</a></li>
                                        <li><a href="#">You have 5 new tasks</a></li>
                                        <li><a href="#">You're now friend with Andrew</a></li>
                                        <li><a href="#">Another Notification</a></li>
                                        <li><a href="#">Another One</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="material-icons">person</i>
                                        <p class="hidden-lg hidden-md">Profile</p>
                                    </a>
                                    
                                    <?php if(isset($perfil_usuario)){
                                       echo $perfil_usuario;
                                    }
                                    ?>
                                </li>
                            </ul>
                            <form class="navbar-form navbar-right" role="search">
                                <div class="form-group  is-empty">
                                    <input type="text" class="form-control" placeholder="Search">
                                    <span class="material-input"></span>
                                </div>
                                <button type="submit" class="btn btn-white btn-round btn-just-icon">
                                    <i class="material-icons">search</i><div class="ripple-container"></div>
                                </button>
                            </form>
                        </div>
                    </div>
                </nav>
                <div class="content">
                    <div class="container-fluid">
                        <?php
                        if (isset($blank))
                        {
                            ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <?php
                                    echo $blank;
                                    ?>   
                                </div>
                            </div>
                        <?php } //fin blank zone?>

                        <?php
                        if (isset($main_content))
                        {
                            ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="card">
                                        <?php
                                        if (isset($sub_title) && !empty($sub_title))
                                        {
                                            ?>
                                            <div class="card-header" data-background-color="purple">
                                                <h4 class="title">
                                                    <?php echo $sub_title; ?>
                                                </h4>
                                                <?php
                                                if (isset($descripcion) && !empty($descripcion))
                                                {
                                                    ?>
                                                    <p class="category">
                                                        <?php echo $descripcion ?>
                                                    </p>
                                                <?php } ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <div class="card-content">
                                            <?php
                                            echo $main_content;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } //fin content card ?>
                    </div>
                </div>

                <footer class="footer">
                    <div class="container-fluid">
                        <nav class="pull-left">
                            <ul>

                            </ul>
                        </nav>
                        <p class="copyright pull-right">
                            <script>document.write(new Date().getFullYear())</script> 
                            <a href="http://educacionensalud.imss.gob.mx" target="_blank">Coordinación de Educación en Salud</a>
                        </p>
                    </div>
                </footer>
            </div>
        </div>
    </body>


    <!--  Charts Plugin -->
    <script src="<?php echo base_url(); ?>assets/tablero_tpl/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="<?php echo base_url(); ?>assets/tablero_tpl/js/bootstrap-notify.js"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

    <!-- Material Dashboard javascript methods -->
    <script src="<?php echo base_url(); ?>assets/tablero_tpl/js/material-dashboard.js"></script>

    <!-- Material Dashboard DEMO methods, don't include it in your project! -->
    <script src="<?php echo base_url(); ?>assets/tablero_tpl/js/demo.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/general.js"></script>

    <script type="text/javascript">
            $(document).ready(function () {
                // Javascript method's body can be found in assets/js/demos.js
                demo.initDashboardPageCharts();
            });
    </script>

</html>
