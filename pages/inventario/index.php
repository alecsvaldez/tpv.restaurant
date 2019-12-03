<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';

$datatables = true;
$datatables_config = array();

$data = $db->get("SELECT 
        IdTipoItem AS id_tipo_item
        , IdItem AS id_item
        , a.Ingrediente AS item
        , a.Estatus AS estatus_item
        -- , i.IdUnidad AS id_unidad
        , Existencia AS existencia
        , u.Unidad AS unidad
        , u.Abreviacion AS unidad_ab
        , Min AS min
        , Max AS max
        , TipoMovimiento AS tipo_movimiento
        , Movimiento AS movimiento
        , UltimoIngreso AS ultimo_ingreso
        , IdUltimoIngreso AS id_ultimo_ingreso
        , UltimaSalida AS ultima_salida
        , IdUltimaSalida AS id_ultima_salida
        , RegistroManual AS registro_manual
        , Comentarios AS comentarios
    FROM tb_inventario i
        INNER JOIN tb_ingredientes AS a ON a.id = i.IdItem
        INNER JOIN tb_unidades AS u ON u.id = i.IdUnidad
    WHERE a.Estatus = 1 OR i.Existencia > 0");

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
                                <th>Existencia</th>
                                <th>Última Entrada</th>
                                <th>Última Salida</th>
                                <th>Mínimo</th>
                                <th>Máximo</th>
                                <th style="width: 7%;text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($data as $d){
                            ?>
                            <tr>
                                <td><?php echo $d['id_item'] ?></td>
                                <td><a href="/inventario/detalle/<?php echo $d['id_item']?>"><?php echo $d['item'] ?></a><br><small class="text-muted"><?php echo $d['id_tipo_item']?></small></td>
                                <td><?php echo $d['existencia'] . ' ' . $d['unidad_ab'] ?></td>
                                <td><a href="<?php echo $site_url?>compras/editor/<?php echo $d['id_ultimo_ingreso']?>"><?php echo dateToString($d['ultimo_ingreso'])?></a>
                                <?php
                                if ($d['tipo_movimiento'] == 1){
                                    echo '<br><small class="text-success">+ ' . $d['movimiento'] . ' ' . $d['unidad_ab'] . '</small>';
                                }
                                ?>
                                </td>
                                <td><?php echo dateToString($d['ultima_salida'])?>
                                <?php
                                if ($d['tipo_movimiento'] == 0){
                                    echo '<br><small class="text-danger">- ' . $d['movimiento'] . ' ' . $d['unidad_ab'] . '</small>';
                                }
                                ?>
                                </td>
                                <td><?php echo $d['min'] . ' ' . $d['unidad_ab'] ?></td>
                                <td><?php echo $d['max'] . ' ' . $d['unidad_ab'] ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-gear tiny-icon"></i> <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" role="menu"> 
                                            <li><a href="<?php echo $current_url . 'editor/' . $d['id_item']?>"><i class="fa fa-pencil tiny-icon"></i> Editar</a></li>
                                            <li><a href="<?php echo $current_url . 'borrar/' . $d['id_item']?>" class="delete" ><i class="fa fa-trash tiny-icon"></i> Borrar</a></li> 
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
                                <th>Existencia</th>
                                <th>Última Entrada</th>
                                <th>Última Salida</th>
                                <th>Mínimo</th>
                                <th>Máximo</th>
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
