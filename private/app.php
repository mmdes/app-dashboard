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
        $stmt->bindValue(':data_inicio','2018-08-01');
        $stmt->bindValue(':data_fim','2018-08-31');
        $stmt->execute();
        //Resultado obtido da query seja retornado como um objeto
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}

    //Lógica do script
    $dashboard = new Dashboard();

    $conexao = new Conexao();

    $bd = new Bd($conexao, $dashboard);
    print_r($bd->getNumeroVendas());


?>
