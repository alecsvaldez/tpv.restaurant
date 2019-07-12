<div class="text-center" style="padding: 100px; background: white; min-height: 100%">
<h1>404: la página solicitada no existe.</h1>
<i><?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
<br>
<img src="<?php echo $site_images?>broken-bottle.jpg">
</div>