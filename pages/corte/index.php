<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';

$data = $db->get("SELECT 
        c.id AS id
        , c.FechaCorteInicio AS fecha_inicio
        , c.FechaCorteFin AS fecha_fin
        , c.BalanceInicio AS balance_inicial
        , c.BalanceFin AS balance_final
        , c.Efectivo AS efectivo
        , c.Tarjeta AS tarjeta
        , c.Servicio AS servicio
        , c.Gastos AS gastos
        , c.Faltante AS faltante
        , c.EfectivoIngreso AS efectivo_ingreso
        , c.Servicio AS servicio
        , c.Gastos AS gastos
        , c.FechaCrea AS fecha_corte
        , CONCAT(u.Nombre, ' ', u.Apellidos) AS usuario
        , COUNT(DISTINCT co.id) AS comandas
    FROM tb_cortes_caja c
        INNER JOIN tb_usuarios u ON u.id = c.IdUsuarioCrea
        LEFT JOIN tb_comandas co ON co.IdCorteCaja = c.id
    WHERE c.Estatus = 1
    GROUP BY c.id");

$comandas_pendientes = $db->first("SELECT COUNT(id) AS cantidad FROM tb_comandas WHERE OrdenCerrada = 1 AND OrdenPagada = 1 AND IdCorteCaja IS NULL");
$comandas_pendientes = $comandas_pendientes['cantidad'];


$datatables = true;
$datatables_config = array();
showSessionMessage();
?>
<section class="content-header">
    <div class="row">
        <div class="col-md-5">
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
        <div class="col-md-4">
            <a href="<?php echo $current_url ?>cerrar"><button type="button" class="btn btn-primary pull-right">Hacer Corte (<?php echo $comandas_pendientes ?> comandas)</button></a>
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
                                <th>Fecha</th>
                                <th>Comandas</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Balance Inicial</th>
                                <th>Balance Final</th>
                                <th>Efectivo</th>
                                <th>Tarjeta</th>
                                <th>Servicio</th>
                                <th>Gastos</th>
                                <th>Usuario</th>
                                <th style="width: 7%;text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($data as $d) {
                                ?>
                                <tr>
                                    <td><?php echo $d['id'] ?></td>
                                    <td><?php echo dateToString($d['fecha_corte']) ?></td>
                                    <td><?php echo ($d['comandas']) ?></td>

                                    <td><?php echo dateToString($d['fecha_inicio']) ?></td>
                                    <td><?php echo dateToString($d['fecha_fin']) ?></td>
                                    <td class="text-default"><?php echo getMoney($d['balance_inicial']) ?></td>
                                    <td class="text-default"><?php echo getMoney($d['balance_final']) ?></td>
                                    <td class="text-primary"><?php echo getMoney($d['efectivo']) ?></td>
                                    <td class="text-info"><?php echo getMoney($d['tarjeta']) ?></td>
                                    <td class="text-warning"><?php echo getMoney($d['servicio']) ?></td>
                                    <td class="text-danger"><?php echo getMoney($d['gastos']) ?></td>
                                    <td><?php echo ($d['usuario']) ?></td>
                                    <td class="text-center">
                                        <!-- <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-gear tiny-icon"></i> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                <li><a href="<?php echo $current_url . 'editor/' . $d['id'] ?>"><i class="fa fa-pencil tiny-icon"></i> Editar</a></li>
                                                <li><a href="<?php echo $current_url . 'borrar/' . $d['id'] ?>" class="delete"><i class="fa fa-trash tiny-icon"></i> Borrar</a></li>
                                            </ul>
                                        </div> -->
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="width: 1%">SN</th>
                                <th>Fecha</th>
                                <th>Comandas</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Total</th>
                                <th>Efectivo</th>
                                <th>Tarjeta</th>
                                <th>Servicio</th>
                                <th>Gastos</th>
                                <th>Usuario</th>
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