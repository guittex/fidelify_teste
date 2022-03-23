<?php
require_once("../model/banco.php");

date_default_timezone_set('America/Sao_Paulo');

class Controller{

    private $controller;

    public function __construct()
    {
        $this->controller = new Banco();
    }

    public function addPublicacoes($post)
    {
        $values = [
            'texto' => $post['texto'],
            'user_id' => $post['user_id'],
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
        ];

        $result = $this->controller->incluirPublicacao($values);

        if($result){
            echo "<script>document.location='../view/index.php?status=1&msg=Salvo com sucesso'</script>";

        }else{
            echo "<script>document.location='../view/index.php?status=2&msg=Erro ao salvar</script>";

        }
    }

    public function getPublicacoes()
    {
        $result = $this->controller->listarPublicacoes();
       
        if($result != 0){
            return $result;

        }else{
            return 0;

        }
    }

    public function cadastrarUsuario($post)
    {
        $values = [
            'login' => $post['login'],
            'senha' => $post['senha'],
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
        ];

        $result = $this->controller->addUsuario($values);

        if($result != false){           
            session_start();

            $_SESSION['login'] = $post['login'];
            $_SESSION['user_id'] = $result;

            echo "<script>document.location='../view/index.php?status=1&msg=Logado com sucesso'</script>";

        }else{
            echo "<script>document.location='../view/index.php?status=2&msg=Erro ao logar</script>";

        }
        
    }

    public function logoff()
    {
        session_start();

        session_destroy();

        echo "<script>document.location='../view/index.php?status=1&msg=Logoff com sucesso'</script>";
    }

    public function login($post)
    {
        $values = [
            'login' => $post['login'],
            'senha' => $post['senha']
        ];

        $result = $this->controller->checkUser($values);

        if($result != false){           
            session_start();

            $_SESSION['login'] = $post['login'];
            $_SESSION['user_id'] = $result;

            echo "<script>document.location='../view/index.php?status=1&msg=Logado com sucesso'</script>";

        }else{
            echo "<script>document.location='../view/index.php?status=2&msg=Erro ao logar'; alert('Usuario ou Senha inválidos')</script>";

        }
    }

    public function addComentario($post)
    {
        if( $post['user_id'] == 0){
            echo "<script>document.location='../view/index.php?status=2&msg=Erro ao adicionar comentario'; alert('É necessario entrar antes de comentar')</script>";

            return;
        }

        $values = [
            'user_id' => $post['user_id'],
            'publicacao_id' => $post['publicacao_id'],
            'comentario' => $post['comentarioModal'],
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
        ];

        $result = $this->controller->insertComentario($values);

        if($result){
            echo "<script>document.location='../view/index.php?status=1&msg=Comentado com sucesso'; alert('Comentado com sucesso')</script>";

        }else{
            echo "<script>document.location='../view/index.php?status=2&msg=Erro ao adicionar comentario'; alert('Erro ao adicionar comentario')</script>";

        }
    }   

    public function getPublicacaoAjax($get)
    {
        $publicacao_id = $get['publicacao_id'];

        $result = $this->controller->getPublicacaoAjax($publicacao_id);

        if($result != 0){
            echo json_encode($result);

        }else{
            echo 'false';

        }        
    }

    public function checkCurtida($post)
    {
        $checkUserCurtiu = $this->controller->checkCurtida($post['publicacao_id'], $post['user_id']);

        if($checkUserCurtiu){
            echo 'false';

            $saveCurtida = $this->controller->deleteCurtida($post['publicacao_id'], $post['user_id']);

        }else{
            $saveCurtida = $this->controller->saveCurtida($post['publicacao_id'], $post['user_id']);

        }
    }
}

$controller = new Controller();

if(isset($_POST['method']) || isset($_GET['method']) ){
    $metodo = $_POST['method'] ?? $_GET['method'];

    switch ($metodo) {
        case 'add':
            $controller->addPublicacoes($_POST);

            break;
        case 'cadastrarUsuario':
            $controller->cadastrarUsuario($_POST);

            break;    
        
        case "logoff":
            $controller->logoff();

            break;
        case "login":
            $controller->login($_POST);

            break;
        case "addComentario":
            $controller->addComentario($_POST);

            break;
        case 'getPublicacaoAjax':
            $controller->getPublicacaoAjax($_GET);

            break;

        case "checkCurtida":
            $controller->checkCurtida($_POST);

            break;
        default:
            # code...
            break;
    }
}
