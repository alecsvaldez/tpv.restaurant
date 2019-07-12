<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';

$editor_error = (!empty($_SESSION['db_message'])) ? $_SESSION['db_message']['submessage'] : false;
unset($_SESSION['db_message']);
?>
<section class="content-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="top-left-header"><?php echo $titulo_editor ?> <span class="text-primary"><?php echo $item['nombre']?></span></h3>  
        </div>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php
            if ($editor_error){
                ?>
            <div class="alert alert-error" style="padding: 5px !important;">
                <p></p><p><?php echo $editor_error ?></p><p></p>
            </div>
                <?php
            }?>
            
            <!-- general form elements -->
            <div class="box box-primary"> 
                <!-- form start -->
                <form action="./" method="post" accept-charset="utf-8">
                    <input type="hidden" name="seccion" value="<?php echo $seccion?>">
                    <input type="hidden" name="subseccion" value="">
                    <input type="hidden" name="action" value="<?php echo $subseccion?>">
                    <input type="hidden" name="id_menu" value="<?php echo $item['id'] ?>">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" placeholder="Filtro" class="form-control filtro-productos">
                                <div class="row mt-5">
                                    <div class="col-sm-10 pr-0">
                                        <select multiple class="form-control select-productos" size="20">
                                        <?php
                                        foreach($productos as $c){
                                            echo '<option value="' . $c['id'] . 
                                            '" data-categoria="' . $c['categoria'] . 
                                            '" data-costo="' . $c['costo'] . 
                                            '" data-precio="' . $c['precio'] . 
                                            '">' . $c['nombre'] . '</option>';
                                        }
                                        ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2" style="margin-top:100px">
                                        <button type="button" class="btn btn-primary" onclick="addSelected()"><i class="fa fa-chevron-right"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">

                            <div class="row">
                                <div class="col-xs-12 ">
                                    <div class="row bg-primary m-0 pt-5 pb-5">
                                        <div class="col-xs-1">SN</div>
                                        <div class="col-xs-4">Producto</div>
                                        <div class="col-xs-3">Costo</div>
                                        <div class="col-xs-3">Precio</div>
                                        <div class="col-xs-1"></div>
                                    </div>
                                </div>
                                <div class="col-xs-12" id="lista-productos" >
                                <?php
                                foreach($item['productos'] as $i){
                                    ?>
                                    <div class="row row-producto m-0">
                                        <input type="hidden" name="productos[<?php echo $i['id_producto']?>][id_registro]" value="<?php echo $i['id']?>">
                                        <input type="hidden" name="productos[<?php echo $i['id_producto']?>][id_producto]" value="<?php echo $i['id_producto']?>">
                                        <div class="col-xs-1"><?php echo $i['id']?></div>
                                        <div class="col-xs-4">
                                            <?php echo $i['producto']?><br>
                                            <small class="text-muted"><?php echo $i['categoria']?></small>
                                        </div>
                                        <div class="col-xs-3"><div class="input-group"><span class="input-group-addon">$</span><input type="text" class="form-control" name="productos[<?php echo $i['id_producto']?>][costo]" value="<?php echo $i['costo']?>"/></div></div>
                                        <div class="col-xs-3"><div class="input-group"><span class="input-group-addon">$</span><input type="text" class="form-control" name="productos[<?php echo $i['id_producto']?>][precio]" value="<?php echo $i['precio']?>"/></div></div>    
                                        <div class="col-xs-1">
                                            <a class="btn btn-danger btn-xs" style="margin-left: 5px; margin-top: 10px;" onclick="deleter();"><i class="fa fa-trash"></i> </a>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row">
                        
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary">Guardar</button>
                    <a href="<?php echo $site_url?>administracion/<?php echo $seccion?>"><button type="button" class="btn btn-primary">Regresar</button></a>
                </div>
                </form>            
            </div>
        </div>
    </div>
</section>
<script>
jQuery(document).ready(function(){
    $('.filtro-productos').on('keyup',function(){
        // Declare variables
        var input, filter, ul, li, a, i, txtValue;
        input = $(this)
        filter = input.val().toLowerCase();
        options = $('.select-productos option')
        console.log(options)
        // Loop through all list items, and hide those who don't match the search query
        for (i = 0; i < options.length; i++) {
            txtValue = options[i].textContent || options[i].innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                options[i].style.display = "";
            } else {
                options[i].style.display = "none";
            }
        }
    })

    $('.select-productos option').on('dblclick',function(){
        addSelected()    
    })
})
function addSelected() {
    var selected = $('.select-productos').find('option:selected')
    var list = $('#lista-productos')
    for (i = 0; i < selected.length; i++){
        var $producto = $(selected[i])
        var attrs = $producto.data()
        var id_producto = $producto.val()
        var div = $('<div class="row row-producto m-0">' + 
            '<input type="hidden" name="productos[' + id_producto + '][id_registro]" value="0">' +
            '<input type="hidden" name="productos[' + id_producto + '][id_producto]" value="' + id_producto + '">' +
            '<div class="col-xs-1">' + id_producto + '</div>' +
            '<div class="col-xs-4">' + $producto.html() + '<br><small class="text-muted">' + attrs.categoria + '</small></div>' +
            '<div class="col-xs-3"><div class="input-group"><span class="input-group-addon">$</span><input type="text" class="form-control" name="productos[' + id_producto + '][costo]" value="' + attrs.costo + '"/></div></div>' +
            '<div class="col-xs-3"><div class="input-group"><span class="input-group-addon">$</span><input type="text" class="form-control" name="productos[' + id_producto + '][precio]" value="' + attrs.precio + '"></div></div>' +
            '<div class="col-xs-1"><a class="btn btn-danger btn-xs" style="margin-left: 5px; margin-top: 10px;" onclick="delete();"><i class="fa fa-trash"></i> </a></div>' +
        '</div>');
        list.append(div)
    }
    $('.select-productos').find('option:selected').remove()
}
</script>
<style>
.select-productos {
    
}
.row-producto {
    padding: 4px 0;
    border-bottom: 1px solid #efefef;
}
</style>