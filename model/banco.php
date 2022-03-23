<?php
class Banco{
    protected $mysqli;

    public function __construct()
    {
        $this->conexao();
    }

    private function conexao()
    {
        $servidor = '127.0.0.1';
        $usuario = "root";
        $senha = "";
        $dbname = "guilhermefelipeoliveira";

        $this->mysqli = new mysqli($servidor, $usuario, $senha, $dbname);
    }

    public function incluirPublicacao($values)
    {
        $con = $this->mysqli->prepare("INSERT INTO publicacoes (`texto`, `user_id`,`created`, `modified`) VALUES (?,?,?,?)");

        $con->bind_param("ssss", $values['texto'], $values['user_id'],$values['created'], $values['modified']);

        if($con->execute() == TRUE){
            return true;

        }else{
            return false;

        }
    }

    public function listarPublicacoes()
    {
        $result = $this->mysqli->query("SELECT p.id AS p_id, p.created AS p_created, p.texto AS p_texto, u.login AS u_login, COUNT(c.id) AS total 
            FROM publicacoes AS p
                JOIN user AS u ON u.id = p.user_id
                    LEFT JOIN comentario_publicacoes AS c ON c.publicacao_id = p.id
                    GROUP BY p.id
                        ORDER BY p.id DESC");

        $result2 = $this->mysqli->query("SELECT COUNT(id) AS total_curtidas, publicacao_id FROM curtidas GROUP by publicacao_id");

        if(mysqli_num_rows($result) >= 1){
            while($row = $result->fetch_array(MYSQLI_ASSOC)){                
                $publicacoes[] = $row;
                    
            }    
            while($row = $result2->fetch_array(MYSQLI_ASSOC)){                
                $curtidas[] = $row;                    
            }

            foreach ($publicacoes as $key => $publicacao) {
                if(isset($curtidas)){
                    foreach ($curtidas as $key2 => $curtida) {
                        if($curtida['publicacao_id'] == $publicacao['p_id']){
                            $publicacoes[$key]['total_curtidas'] = $curtida['total_curtidas'];
                        }
                    }
                }               
            }

            return $publicacoes;
        }else{
            return 0;
        }
    }

    public function addUsuario($values)
    {
        $con = $this->mysqli->prepare("INSERT INTO user (`login`, `senha`,`created`, `modified`) VALUES (?,?,?,?)");

        $con->bind_param("ssss", $values['login'], $values['senha'], $values['created'], $values['modified']);
        

        if($con->execute() == TRUE){
            return mysqli_insert_id($this->mysqli);

        }else{
            return false;

        } 
    }

    public function checkUser($values)
    {
        $login = $values['login'];

        $senha = $values['senha'];

        $result = $this->mysqli->query("SELECT * FROM user WHERE `login` = '$login' AND senha = '$senha' ");

        if(mysqli_num_rows($result) >= 1){
            return $result->fetch_array(MYSQLI_ASSOC)['id'];

        }else{
            return false;

        }
    }

    public function insertComentario($values)
    {
        $con = $this->mysqli->prepare("INSERT INTO comentario_publicacoes (`publicacao_id`, `user_id`, comentario,`created`, `modified`) VALUES (?,?,?,?,?)");

        $con->bind_param("sssss", $values['publicacao_id'], $values['user_id'], $values['comentario'], $values['created'], $values['modified']);
        

        if($con->execute() == TRUE){
            return true;

        }else{
            return false;

        } 
    }

    public function getPublicacaoAjax($id)
    {
        $result = $this->mysqli->query("SELECT c.comentario, DATE_FORMAT(c.created,'%d/%m/%Y %H:%i') as data_cadastro, u.login FROM comentario_publicacoes AS c 
            JOIN user AS u ON c.user_id = u.id 
            WHERE c.publicacao_id = $id");

        if(mysqli_num_rows($result) >= 1){
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $array[] = $row;
            }
    
            return $array;
        }else{
            return 0;
        }
    }

    public function checkCurtida($publicacao_id, $user_id)
    {
        $result = $this->mysqli->query("SELECT * FROM curtidas WHERE `publicacao_id` = $publicacao_id AND `user_id` = $user_id");

        if(mysqli_num_rows($result) >= 1){
            return true;

        }else{
            return false;
        }
    }

    public function saveCurtida($publicacao_id, $user_id)
    {
        $hoje = date('Y-m-d H:i:s');

        $con = $this->mysqli->prepare("INSERT INTO curtidas (`publicacao_id`, `user_id`, `created`, `modified`) VALUES (?,?,?,?)");

        $con->bind_param("ssss", $publicacao_id, $user_id, $hoje, $hoje);

        $con->execute();
    }

    public function deleteCurtida($publicacao_id, $user_id)
    {        
        $this->mysqli->query("DELETE FROM curtidas WHERE `publicacao_id` = $publicacao_id AND `user_id` = $user_id");
    }
}