<?php

//Classe dashboard
class Dashboard{

    public $data_inicio;
    public $data_fim;
    public $numeroVendas;
    public $totalVendas;

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo,$valor){
        $this->$atributo = $valor;
        return $this;
    }
}

//Classe conexão
class Conexao{
    private $host = 'localhost';
    private $dbname = 'dashboard';
    private $user = 'root';
    private $pass = '';

    public function conectar(){
        try{
            $conexao = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                "$this->user",
                "$this->pass"
            );
            //conexão com backend deve usar mesma coleção de caracteres

            $conexao->exec('set charset utf8');

            return $conexao;

        }catch(PDOException $e){
            echo '<p>'.$e->getMessage().'</p>';
        }
    }
}

//classe (model)

class Bd{
    private $conexao;
    private $dashboard;

    public function __construct(Conexao $conexao, Dashboard $dashboard){
        $this->conexao = $conexao->conectar();
        $this->dashboard = $dashboard;
    }
    public function getNumeroVendas(){
        $query = '
                select 
                    count(*) as numero_vendas 
                from 
                    tb_vendas 
                where 
                    data_venda between :data_inicio and :data_fim';

        $stmt = $this->conexao->prepare($query); //retorna pdo statement
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();
        //Resultado obtido da query seja retornado como um objeto
        //retornando apenas o atributo numero_vendas
        return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
    }
    public function getTotalVendas(){
        //soma da coluna total 
        $query = '
                select 
                    SUM(total) as total_vendas 
                from 
                    tb_vendas 
                where 
                    data_venda between :data_inicio and :data_fim';

        $stmt = $this->conexao->prepare($query); //retorna pdo statement
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();
        //Resultado obtido da query seja retornado como um objeto
        //retornando apenas o atributo numero_vendas
        return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
    }
}

    //Lógica do script
    $dashboard = new Dashboard();

    $conexao = new Conexao();

    $competencia = explode('-', $_GET['competencia']);
    $ano = $competencia[0];
    $mes = $competencia[1];

    //descobrir quantos dias tem o determinado mês do determinado ano
    $dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

    //setando valores através do método mágico __set()
    $dashboard->__set('data_inicio', $ano.'-'.$mes.'-01');
    $dashboard->__set('data_fim', $ano.'-'.$mes.'-'.$dias_do_mes);


    $bd = new Bd($conexao, $dashboard);

    $dashboard->__set('numeroVendas', $bd->getNumeroVendas());
    $dashboard->__set('totalVendas', $bd->getTotalVendas());
    //encaminha objeto transcrito em json
    echo json_encode($dashboard);

    

?>