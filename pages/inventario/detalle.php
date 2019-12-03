<?php
defined('_PUBLIC_ACCESS') or die();

$titulo = 'Detalle de inventario';

$id = (isset($_GET['id']) && $_GET['id'] > 0) ? $_GET['id'] : 0;
$datatables = true;
$datatables_config = array();

$data = $db->get("SELECT * 
FROM (
    SELECT  
        'Entrada' AS movimiento
        , 'Compra' AS tipo_movimiento
        , c.id AS id_movimiento
        , i.id AS id_ingrediente
        , i.Ingrediente AS ingrediente
        -- , i.IdUnidadSalida AS IdUnidad
        , p.Cantidad * p.FactorConversion AS cantidad
        , u.Abreviacion AS unidad
        , CAST(c.FechaCrea AS DATE) AS fecha
    FROM tb_compras_detalle p
        INNER JOIN tb_compras c ON c.id = p.IdCompra
        INNER JOIN tb_ingredientes i ON i.id = p.IdItem
        INNER JOIN tb_unidades u ON u.id = i.IdUnidadSalida
    WHERE p.IdItem = $id
    UNION ALL
    SELECT  
        'Salida' AS movimiento
        , 'Comanda' AS tipo_movimiento
        , c.id AS id_movimiento
        , i.id AS id_ingrediente
        , i.Ingrediente AS ingrediente
        -- , i.IdUnidadSalida AS IdUnidad
        , COUNT(DISTINCT p.id) AS cantidad
        , u.Abreviacion AS unidad
        , CAST(c.FechaCrea AS DATE) AS fecha
    FROM tb_comandas_productos p
        INNER JOIN tb_comandas c ON c.id = p.IdComanda
        INNER JOIN tb_preparaciones_ingredientes pi ON pi.IdProducto = p.IdProducto
        INNER JOIN tb_ingredientes i ON i.id = pi.IdIngrediente
        INNER JOIN tb_unidades u ON u.id = i.IdUnidadSalida
    WHERE i.id = $id
    GROUP BY p.IdComanda
) AS x
ORDER BY x.Fecha DESC");

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
        <div class="col-md-2">
            <a href="../"><button type="button" class="btn btn-block btn-primary pull-left">Regresar</button></a>
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
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Movimiento</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th style="width: 7%;text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($data as $d){
                            ?>
                            <tr>
                                <td nowrap><?php echo $d['fecha']?>
                                <td><?php echo $d['movimiento'] ?></td>
                                <td><?php echo $d['tipo_movimiento'] . ' ' . $d['id_movimiento'] ?></td>
                                <td><?php echo $d['ingrediente'] ?></td>
                                <td class="<?php echo $d['movimiento'] == 'Entrada' ? 'text-success': 'text-danger' ?>"><?php echo $d['cantidad'] . ' ' . $d['unidad'] ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-gear tiny-icon"></i> <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" role="menu"> 
                                            <li><a href="<?php echo $current_url . 'editor/' . $d['id_movimiento']?>"><i class="fa fa-pencil tiny-icon"></i> Editar</a></li>
                                            <li><a href="<?php echo $current_url . 'borrar/' . $d['id_movimiento']?>" class="delete" ><i class="fa fa-trash tiny-icon"></i> Borrar</a></li> 
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
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Movimiento</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
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
<script>
dt_options.order = [[ 0, "desc" ]]
</script>
