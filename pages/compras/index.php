<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';

$datatables = true;
$datatables_config = array();

$data = $db->get("SELECT
        c.id AS id
        , c.FolioCompra AS folio
        , p.Proveedor as proveedor
        , p.RazonSocial as razon_social
        , c.FechaCompra as fecha_compra
        , c.FechaEntrega as fecha_entrega
        , c.SaldoTotal as total
        , c.SaldoPagado as pagado
        , c.SaldoPendiente as pendiente
        , c.OrdenCerrada as estatus
    FROM tb_compras c
        INNER JOIN tb_proveedores p ON p.id = c.IdProveedor
    WHERE c.Estatus = 1");

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
            <a href="<?php echo $current_url ?>editor"><button type="button" class="btn btn-primary pull-right" style="width:150px">Agregar</button></a>
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
                                <th>Folio</th>
                                <th>Proveedor</th>
                                <th>Fecha Compra</th>
                                <th>Fecha Entrega</th>
                                <th>Total</th>
                                <th>Pagado</th>
                                <th>Pendiente</th>
                                <th>Estatus</th>
                                <th style="width: 7%;text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($data as $d){
                            ?>
                            <tr>
                                <td><?php echo $d['id'] ?></td>
                                <td><?php echo sprintf('%02d',$d['id']) . '-' . $d['folio'] ?></td>
                                <td><?php echo $d['proveedor'] ?><br><small class="text-muted"><?php echo $d['razon_social']?></small></td>
                                <td><?php echo dateToString($d['fecha_compra'])?></td>
                                <td><?php echo dateToString($d['fecha_entrega'])?></td>
                                <td class="text-primary"><?php echo getMoney($d['total']) ?></td>
                                <td class="text-success"><?php echo getMoney($d['pagado'])?></td>
                                <td class="text-danger"><?php  echo getMoney($d['pendiente'])?></td>
                                <td class="">
                                    <i class="fa fa-2x <?php  echo $d['estatus'] == 0 ? 'text-danger fa-times-circle' : 'text-success fa-check-circle'?>"></i>
                                    <?php  echo $d['estatus'] == 0 ? 'Orden Abierta' : 'Orden Cerrada'?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-gear tiny-icon"></i> <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" role="menu"> 
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
                                <th>Folio</th>
                                <th>Proveedor</th>
                                <th>Fecha Compra</th>
                                <th>Fecha Entrega</th>
                                <th>Total</th>
                                <th>Pagado</th>
                                <th>Pendiente</th>
                                <th>Estatus</th>
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
