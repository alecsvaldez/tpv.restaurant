<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';

$editor_error = (!empty($_SESSION['db_message'])) ? $_SESSION['db_message']['submessage'] : false;
unset($_SESSION['db_message']);

$fecha_inicio = date('Y-m-d');
$fecha_fin = date('Y-m-d');

if ($id > 0) {
    $item = $db->find($table, $columns_item, $conditions_item);

    $item['items'] = $db->select('tb_compras_detalle', array(
        'id_registro' => 'id',
        'id_item' => 'IdItem',
        'id_unidad' => 'IdUnidad',
        'id_unidad_original' => 'IdUnidadOriginal',
        'precio' => 'Precio',
        'cantidad' => 'Cantidad',
        'total' => 'Total',
    ), array(
        'IdCompra = ' . $id
    ));
} else {
    // vamos por las comandas cerraras
    $data = $db->get("SELECT 
    c.id
    , c.Comanda AS comanda
    , m.Mesa AS mesa
    , c.FechaCrea AS fecha_crea
    , c.FechaCobra AS fecha_cobra
    , c.Total AS total
    , c.Efectivo AS efectivo
    , c.Tarjeta AS tarjeta
    , c.Pagado AS pagado
    , c.Cambio AS cambio
    , CONCAT(ug.Nombre, ' ', ug.Apellidos) AS usuario_genera
    , CONCAT(ua.Nombre, ' ', ua.Apellidos) AS usuario_atiende
    , CONCAT(u.Nombre, ' ', u.Apellidos) AS usuario_cobra
FROM tb_comandas c
    LEFT JOIN tb_mesas m ON m.id = c.IdMesa
    INNER JOIN tb_usuarios ua ON ua.id = c.IdAtiende
    INNER JOIN tb_usuarios ug ON ug.id = c.IdUsuarioCrea
    INNER JOIN tb_usuarios u ON u.id = c.IdUsuarioCobra
WHERE OrdenCerrada = 1 
    AND ORdenPagada = 1
    AND IdCorteCaja IS NULL 
    -- Restringimos a cuentas pendientes
    -- AND CAST(FechaCobra AS DATE) BETWEEN '$fecha_inicio' AND '$fecha_fin'
");
}

$cantidad_comandas = 0;
$total = 0;
$efectivo = 0;
$tarjeta = 0;
$servicio = 0;
$gastos = 0;
$retiro = 0;
$balance_inicial = $db->first("SELECT Fondo FROM tb_cortes_caja ORDER BY id DESC LIMIT 1");
$balance_inicial = $balance_inicial['Fondo'];
if(is_null($balance_inicial )){
    $balance_inicial = 0;
}
?>
<section class="content-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="top-left-header"><?php echo $titulo_editor ?></h3>
        </div>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <!-- form start -->
            <form action="./" method="post" accept-charset="utf-8">
                <input type="hidden" name="seccion" value="<?php echo $seccion ?>">
                <input type="hidden" name="action" value="corte_caja">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 1%">
                                        <label class="label-checkbox check-all checked">
                                            <input type="checkbox" class="natural" checked>
                                            SN
                                        </label>
                                    </th>
                                    <th>Fecha</th>
                                    <th>Comanda</th>
                                    <th>Total</th>
                                    <th>Efectivo</th>
                                    <th>Tarjeta</th>
                                    <th>Servicio</th>
                                    <th>Usuario</th>
                                    <th style="width: 7%;text-align: center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($data) > 0){

                                    foreach ($data as $d) {
                                        $d['servicio'] = $d['total'] / 10.999;
                                        ?>
                                        <tr>
                                            <td>
                                                <label class="label-checkbox label-close-order checked">
                                                    <input type="checkbox" class="natural check-close-order" name="corte[<?php echo $d['id'] ?>]" checked>
                                                    <?php echo $d['id'] ?>
                                                </label>
                                            </td>
                                            <td nowrap><?php echo dateToString($d['fecha_cobra']) ?><br><small><?php echo dateToString($d['fecha_crea']) ?></small></td>
                                            <td><?php echo $d['comanda'] ?><br><small class="text-muted"><?php echo $d['mesa'] ?></small></td>
                                            <td class="text-success"><?php echo getMoney($d['total']) ?></td>
                                            <td class="text-primary"><?php echo getMoney($d['efectivo']) ?></td>
                                            <td class="text-warning"><?php echo getMoney($d['tarjeta']) ?></td>
                                            <td class="text-danger"><?php echo getMoney($d['servicio']) ?> </td>
                                            <td><?php echo $d['usuario_cobra'] ?></td>
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
                                        // para los totales:
                                        $cantidad_comandas++;
                                        $total += $d['total'];
                                        $efectivo += $d['efectivo'];
                                        $tarjeta += $d['tarjeta'];
                                        $servicio += $d['servicio'];
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="width: 1%">SN</th>
                                    <th>Fecha</th>
                                    <th>Comanda</th>
                                    <th>Total</th>
                                    <th>Efectivo</th>
                                    <th>Tarjeta</th>
                                    <th>Servicio</th>
                                    <th>Usuario</th>
                                    <th style="width: 7%;text-align: center">Acciones</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 1%"></th>
                                    <th>&nbsp;</th>
                                    <th>Comandas</th>
                                    <th>Total</th>
                                    <th>Efectivo</th>
                                    <th>Tarjeta</th>
                                    <th>Servicio</th>
                                    <th></th>
                                    <th style="width: 7%;text-align: center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>&nbsp; </td>
                                    <td>&nbsp; </td>
                                    <th><?php echo $cantidad_comandas ?></th>
                                    <th class="text-success"><?php echo getMoney($total) ?></th>
                                    <th class="text-primary"><?php echo getMoney($efectivo) ?></th>
                                    <th class="text-warning"><?php echo getMoney($tarjeta) ?></th>
                                    <th class="text-danger"><?php echo getMoney($servicio) ?></th>
                                    <th>&nbsp;</th>
                                    <th style="width: 7%;text-align: center">&nbsp;</th>
                                </tr>
                            </tbody>
                        </table>
                        <div class="dr"></div>
                    </div>
                    <div class="box-body">
                        <p class="text-strong"><b>Balance</b></p>
                        <div class="row">
                            <div class="col-xs-4 col-sm-3 col-lg-2">
                                <label>Balance Inicial</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control" readonly value="<?php echo $balance_inicial; ?>" id="balance-inicial" name="balance_inicial">
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-3 col-lg-2">
                                <label>Total Venta</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control numeric" value="<?php echo round($efectivo, 2) ?>" onkeyup="calculaBalance()" id="efectivo-ingreso" name="efectivo-ingreso" readonly>
                                </div>
                                <p class="help-block"></p>
                            </div>
                            <div class="col-xs-4 col-sm-3 col-lg-2">
                                <label>Gastos</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control numeric" value="<?php echo $gastos ?>" onkeyup="calculaBalance()" id="gastos" name="gastos">
                                </div>
                                <p class="help-block">&nbsp;</p>

                                <label>Servicio</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control numeric" value="<?php echo round($servicio, 2) ?>" onkeyup="calculaBalance()" id="servicio" name="servicio">
                                </div>
                                <p class="help-block">10% Venta = <?php echo getMoney($servicio) ?></p>
                            </div>
                            <div class="col-xs-4 col-sm-3 col-lg-2">
                                <label>Balance Final</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control numeric" id="balance-final" onkeyup="calculaFaltante()" name="balance_final" value="">
                                </div>
                                <p class="help-block">Calculado: $<span id="balance-final-calculado">$</span></p>

                                <label>Faltante</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control" readonly id="faltante" name="faltante" tabindex="-1">
                                    <span id="faltante-ok" class="input-group-addon bg-success" style="display:none"><i class="fa fa-check"></i></span>
                                    <span id="faltante-not" class="input-group-addon bg-danger" style="display:none"><i class="fa fa-times"></i></span>
                                </div>
                                <p class="help-block">Si es $0 o menos, está ok</p>

                            </div>
                            <div class="col-xs-4 col-sm-3 col-lg-2">
                                <label>Retiro</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control numeric" id="retiro" name="retiro" onkeyup="calculaFondo()"  value="<?php echo $retiro ?>">
                                </div>
                                <p class="help-block">Efectivo retirado de caja</p>
                            </div>
                            <div class="col-xs-4 col-sm-3 col-lg-2">
                                <label>Fondo</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control" readonly id="fondo" name="fondo">
                                </div>
                                <p class="help-block">Disponible día siguiente</p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="efectivo" value="<?php echo $efectivo ?>">
                    <input type="hidden" name="tarjeta" value="<?php echo $tarjeta ?>">
                    
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" name="submit" value="submit" class="btn btn-primary">Guardar</button>
                        <a href="<?php echo $site_url ?><?php echo $seccion ?>"><button type="button" class="btn btn-default">Regresar</button></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $('.label-checkbox.check-all').click(function() {
            if ($(this).find('input[type="checkbox"]').is(':checked')) {
                $(this).addClass('checked')
                $('.check-close-order').each(function(i, item) {
                    $(item).attr('checked', 'checked')
                    $(item).closest('.label-close-order').addClass('checked')
                })
            } else {
                $(this).removeClass('checked')
                $('.check-close-order').each(function(item) {
                    $(this).removeAttr('checked')
                    $(this).closest('.label-close-order').removeClass('checked')
                })
            }
        })
        $('.label-checkbox:not(.check-all)').click(function() {
            if ($(this).find('input[type="checkbox"]').is(':checked')) {
                $(this).addClass('checked')
                //$(this).find('input[type="checkbox"]').removeAttr('checked')
            } else {
                $(this).removeClass('checked')
                //$(this).find('input[type="checkbox"]').attr('checked', 'checked')
            }
        })

        calculaBalance()
        $('#balance-final').focus()
    })

    function calculaBalance() {
        
        var balance_inicial = $('#balance-inicial').val()
        var efectivo = $('#efectivo-ingreso').val()
        var servicio = $('#servicio').val()
        var gastos = $('#gastos').val()
        var balance_final = parseFloat(balance_inicial) + parseFloat(efectivo) - parseFloat(servicio) - parseFloat(gastos)
        
        var faltante = 0
        
        
        
        $('#balance-final').val(balance_final.toFixed(2))
        $('#balance-final-calculado').html(balance_final.toFixed(2))
        
        calculaFaltante()
        calculaFondo()
    }
    function calculaFaltante() {
        
        var faltante = $('#balance-final-calculado').html() - $('#balance-final').val()
        
        $('#faltante').val(faltante.toFixed(2))
        if (faltante <= 0) {
            $('#faltante-ok').show()
            $('#faltante-not').hide()
        } else {
            $('#faltante-ok').hide()
            $('#faltante-not').show()
        }
    }
    function calculaFondo() {
        var balance_final = $('#balance-final').val()
        var retiro = $('#retiro').val()
        $('#fondo').val( (balance_final - retiro).toFixed(2) )
    }
</script>
<style>
    #faltante-ok.input-group-addon {
        background-color: #00a200;
        color: white;
    }

    #faltante-not.input-group-addon {
        background-color: #ff3f3f;
        color: white;
    }
</style>
