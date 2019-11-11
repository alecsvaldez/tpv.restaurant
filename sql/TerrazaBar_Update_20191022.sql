ALTER TABLE tb_ingredientes
  ADD COLUMN IdUnidadEntrada INT(11) DEFAULT NULL AFTER IdUnidad,
  ADD COLUMN FactorConversion DECIMAL(10, 2) DEFAULT NULL AFTER IdUnidadEntrada ,
  ADD COLUMN IdUnidadSalida INT(11) DEFAULT NULL AFTER FactorConversion;
