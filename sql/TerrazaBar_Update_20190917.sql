
ALTER TABLE TerrazaBar.tb_comandas
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

