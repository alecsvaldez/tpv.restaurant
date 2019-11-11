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
    <title>Instalación de TPV Restaurant</title>
    <!-- =-=-=-=-=-=-= Mobile Specific =-=-=-=-=-=-= -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- =-=-=-=-=-=-= Favicons Icon =-=-=-=-=-=-= -->
    <link rel="icon" href="<?php echo $site_images; ?>favicon.ico" type="image/x-icon" />
    <!-- =-=-=-=-=-=-= Bootstrap CSS Style =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="<?php echo $site_css; ?>bootstrap.css">
    <!-- =-=-=-=-=-=-= Template CSS Style =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="<?php echo $site_css; ?>css.css">
    <!-- =-=-=-=-=-=-= Font Awesome =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="<?php echo $site_css; ?>font-awesome.css" type="text/css">
    <!-- =-=-=-=-=-=-= Responsive Media =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="<?php echo $site_css; ?>responsive-media.css">
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
    <!-- Bootstrap Core Css  -->
    <script src="<?php echo $site_js; ?>bootstrap.min.js"></script>
</head>

<body class="skin-blue">
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
        </header>
        <div class="content-wrapper">
            Hola Mundo
        </div>
        <footer class="main-footer">
            <div class="row">
                <div class="col-md-12" style="text-align: center;">
                    <strong>TPV</strong>, Restaurant TPV + Inventario by @alecsvaldez
                </div>
            </div>
        </footer>
    </div>
    <script src="<?php echo $site_js; ?>lte/plugins.js"></script>
    <script src="<?php echo $site_js; ?>lte/adminlte.js"></script>
    <script src="<?php echo $site_js; ?>custom.js"></script>
    <!-- Include a polyfill for ES6 Promises (optional) for IE11 and Android browser -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
    <!-- Include a polyfill for ES6 Promises (optional) for IE11 -->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>
    <script>
        jQuery(document).ready(function($) {
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
        });
    </script>
</body>

</html>