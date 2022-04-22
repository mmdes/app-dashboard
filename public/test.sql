
//Selecionar clientes ativos
'select count(*) from tb_clientes where cliente_ativo = 1';

'select SUM(total) as total_despesas from tb_despesas where data_despesa between :data_inicio and :data_fim';

"select SUM(total) as total_despesas from tb_despesas where data_despesa between '2018-08-20' and '2018-09-01'";
