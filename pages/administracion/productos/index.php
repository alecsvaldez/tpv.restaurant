<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';

$datatables = true;
$datatables_config = array();

$data = $db->select($table, $columns_list, $conditions_list );

showSessionMessage();
?>
<section class="content-header">
    <div class="row">
        <div class="col-md-6">
            <h2 class="top-left-header"><?php echo $titulo ?></h2>
        </div>
        <div class="col-md-3">
            <!-- filtro -->
            <!-- <form action="./categorias" method="post" accept-charset="utf-8">
                <input type="hidden" name="csrf_test_name" value="e3141a4e4ad52594d733b1e2d969d559">
                <select name="category_id" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option value="">Categorías</option>
                </select>
            </form> -->
        </div>
        <div class="hidden-md hidden-lg" style="height: 20px;"></div>
        <div class="col-md-1">
            <!-- <button type="submit" name="submit" value="submit" class="btn btn-block btn-primary pull-left">Filtrar</button> -->
        </div>
        <div class="hidden-md hidden-lg" style="height: 50px;"></div>
        <div class="col-md-offset-4 col-md-2">
            <a href="<?php echo $current_url ?>editor"><button type="button" class="btn btn-block btn-primary pull-right">Agregar</button></a>
        </div>
    </div> 
</section>
<!-- datatables -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary"> 
                <!-- /.box-header -->
                <div class="box-body table-responsive"> 
                    <table id="datatable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 1%">SN</th>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th>Tipo</th>
                                <th>Agregado por</th>
                                <th style="width: 7%;text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($data as $d){
                            ?>
                            <tr>
                                <td><?php echo $d['id'] ?></td>
                                <td><?php echo $d['nombre'] ?></td>
                                <td><?php echo $d['descripcion'] ?></td>
                                <td><?php echo idToString($d['id_categoria'], $categorias_indexed)?></td>
                                <td><?php echo idToString($d['id_tipo_producto'], $tipos_producto_indexed)?></td>
                                <td></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-gear tiny-icon"></i> <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" role="menu"> 
                                            <li><a href="<?php echo $current_url . 'preparacion/' . $d['id']?>" ><i class="fa fa-list tiny-icon"></i> Preparación</a></li> 
                                            <li role="separator" class="divider"></li>
                                            <li><a href="<?php echo $current_url . 'editor/' . $d['id']?>"><i class="fa fa-pencil tiny-icon"></i> Editar</a></li>
                                            <li><a href="<?php echo $current_url . 'borrar/' . $d['id']?>" class="delete" ><i class="fa fa-trash tiny-icon"></i> Borrar</a></li> 
                                        </ul> 
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>     
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="width: 1%">SN</th>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th>Tipo</th>
                                <th>Agregado por</th>
                                <th style="width: 7%;text-align: center">Acciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div> 
        </div> 
    </div> 
</section>
