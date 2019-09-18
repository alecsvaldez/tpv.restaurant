
﻿ALTER TABLE TerrazaBar.tb_comandas
  ADD COLUMN Cambio DECIMAL(10,2) NULL AFTER Pagado,
  ADD COLUMN Efectivo DECIMAL(10,2) NULL AFTER OrdenPagada,
  ADD COLUMN Tarjeta DECIMAL(10,2) NULL AFTER Efectivo,
  ADD COLUMN Banco VARCHAR(255) NULL AFTER Tarjeta,
  ADD COLUMN NoTarjeta SMALLINT NULL AFTER Banco,
  ADD COLUMN IdUsuarioCierra int NULL AFTER FechaModifica,
  ADD COLUMN FechaCierra datetime NULL AFTER IdUsuarioCierra,
  ADD COLUMN IdUsuarioCobra int NULL AFTER FechaCierra ,
  ADD COLUMN FechaCobra datetime NULL AFTER IdUsuarioCobra,
  ADD COLUMN IdUsuarioCancela int NULL AFTER FechaCierra ,
  ADD COLUMN FechaCancela datetime NULL AFTER IdUsuarioCancela,
  ADD COLUMN IdCorteCaja int NULL AFTER OrdenPagada
;

CREATE TABLE TerrazaBar.tb_cortes_caja (
  id INT(11) NOT NULL AUTO_INCREMENT,
  FechaCorteInicio DATETIME DEFAULT NULL,
  FechaCorteFin DATETIME DEFAULT NULL,
  BalanceInicio DECIMAL(10, 2) DEFAULT 0,
  BalanceFin DECIMAL(10, 2) DEFAULT 0,
  EfectivoIngreso DECIMAL(10, 2) DEFAULT 0,
  Faltante DECIMAL(10, 2) DEFAULT 0,
  Servicio DECIMAL(10, 2) DEFAULT 0,
  Gastos DECIMAL(10, 2) DEFAULT 0,
  Efectivo DECIMAL(10, 2) DEFAULT 0,
  Tarjeta DECIMAL(10, 2) DEFAULT 0,
  Estatus BOOL DEFAULT 1,
  IdUsuarioCrea INT(11) NOT NULL,
  FechaCrea DATETIME NOT NULL,
  PRIMARY KEY (id)
)
