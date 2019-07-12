<?php
?>
<div class="login-wrapper">
<div class="login-box">
    <div class="login-logo">
        <a href="#"><img src="<?php echo $site_images?>la_terraza.png"></a>
    </div> 
    
    <!-- /.login-logo -->
    <div class="login-box-body">

        <p class="login-box-msg">Bienvenido</p> 

        <form action="<?php echo $site_url?>login" method="post" accept-charset="utf-8">
            <input type="hidden" name="action" value="login">
            <?php
            if (isset($message)){
                ?>
            <div class="alert alert-error" style="padding: 5px !important;">
                <p></p><p><?php echo $message['message']; ?></p><p></p>
            </div>
                <?php
            }
            ?>
            <div class="form-group has-feedback">
                <input type="hidden" name="csrf_test_name" value="bb76d3d58f808888366e3e671337abc3">
                <input type="text" class="form-control" name="username" placeholder="Usuario">
                <span class="fa fa-envelope form-control-feedback"></span>
            </div>       
     
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="Contraseña">
                <span class="fa fa-lock form-control-feedback"></span>
            </div>
            <div class="row"> 
                <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary btn-block btn-flat">Entrar</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>
    <?php
    if ($config['allow_recovery_password']){
        ?>
    <a href="/forgot-password" class="pull-right">Forgot Password</a>
        <?php
    }?>
    <!-- /.login-box-body --> 
</div>
</div>