ALTER TABLE tb_cortes_caja 
  ADD COLUMN Retiro DECIMAL(10, 2) DEFAULT NULL  AFTER Gastos;;

--
-- Create column `Fondo` on table `tb_cortes_caja`
--
ALTER TABLE tb_cortes_caja 
  ADD COLUMN Fondo DECIMAL(10, 2) DEFAULT NULL AFTER Retiro;
