<?php 
//Import do arquivo para iniciar as dependencias da API
require_once ("vendor/autoload.php");

//Instancia da classe App
$app = new \Slim\App();


//EndPoint para o acesso a raíz da pasta da API
$app->get('/', function($request, $response, $args){
    return $response->getBody()->write('API de Contatos do CRUD');
});

//EndPoint para o acesso a todos os dados de contatos da API
$app->get('/contatos', function($request, $response, $args){
    
    //Import do arquivo que vai buscar no BD
    require_once('../bd/apiContatos.php');
    
    // Recebendo dados da QueryString (Essas variáveis podem ou não chegar na requisição)
    //Existem 2 maneiras de receber uma variavel pela QueryString
        //$_GET[]
        //getQueryParams()
    
    if(isset($request->getQueryParams()['nome']))
    {
                                      //Aqui colocamos a variavel que ser enviada na requisição    
        $nome = $request->getQueryParams()['nome'];
        
        //Função para buscar apenas pelo nome dos Contatos
        $listContatos = buscarContatos($nome);
        
    }
    else
    {
        //Função para Listar todos os Contatos
        $listContatos = listarContatos(0); 
    }

        
    //Valida se houve retorno de dados do banco
    if($listContatos)
        return $response    ->withStatus(200)
                            ->withHeader('Content-Type', 'application/json')
                            ->write($listContatos);
    else
        return $response    ->withStatus(204);
});

//EndPoint para buscar pelo id
$app->get('/contatos/{id}', function($request, $response, $args){
  
    $id = $args['id'];
    
    //Import do arquivo que vai buscar no BD
    require_once('../bd/apiContatos.php');
    
    //Função para Listar todos os Contatos
    $listContatos = listarContatos($id);
    
    //Valida se houve retorno de dados do banco
    if($listContatos)
        return $response    ->withStatus(200)
                            ->withHeader('Content-Type', 'application/json')
                            ->write($listContatos);
    else
        return $response    ->withStatus(204);
    
    
    
});

//EndPoint para receber os dados via POST
$app->post('/contatos', function($request, $response, $args){
    
   
    
    //Recebe o ContentType da requisição
    $contentType = $request->getHeaderLine('Content-Type');
    
    //Valida apenas o tipo de Content Type que esta chegando
    if($contentType == 'application/json')
    {
        //Recebe todos os dados enviados para a API no formato JSON
        $dadosJSON = $request->getParsedBody();
        
        //Valida se os dados recebidos estão nulos
        if($dadosJSON == "" || $dadosJSON == null)
        {
            return $response ->withStatus (400)
                             ->withHeader('Content-Type', 'application/json')
                             ->write('
                                    {
                                        "status": "Fail",
                                        "message": "Dados enviados não podem ser nulos"
                                    }
                                ');
        }
        else
        {
            //Import do arquivo que vai Inserir no BD
            require_once('../bd/apiContatos.php');
            
           
               
            //Valida se os dados foram inseridos corretamente no BD
            $retornoDados = inserirContato($dadosJSON);
            if($retornoDados)
            {
                return $response ->withStatus (201)
                                 ->withHeader('Content-Type', 'application/json')
                                 ->write($retornoDados);
            }
            
        }
            
    }
    else
    {
        //Retorna Erro de Content Type
        return $response ->withStatus (400)
                         ->withHeader('Content-Type', 'application/json')
                         ->write('
                                    {
                                        "status": "Fail",
                                        "message": "Erro no Content Type da Requisição"
                                    }
                                ');
    }
});

//EndPoint para Atualizar a foto via POST (Para receber elementos de file, somente poderá ser enviado via POST, mesmo que seja para fazer um updade)
$app->post('/contatos/{id}', function($request, $response, $args){
    
    //Recebe o ContentType da requisição
    $contentType = $request->getHeaderLine('Content-Type');
    
    if(strstr($contentType, "multipart/form-data"))
    {
        $id = $args['id'];
        $arquivo = $_FILES['file1'];
        
        //Import do arquivo que vai buscar no BD
        require_once('../bd/apiContatos.php');
        
        //chama a função para fazer o upload e o update no banco
        $retornoDados = atualizarFoto($arquivo, $id);
        //echo($retornoDados);
        //die;
        if($retornoDados == "1")
            return $response ->withStatus (201);
        elseif ($retornoDados == "0")
            return $response ->withStatus (400)
                             ->withHeader('Content-Type', 'application/json')
                             ->write('
                                    {
                                        "status": "Fail",
                                        "message": "Não foi possivel realizar o Update"
                                    }
                                ');
        else
            return $response ->withStatus (415)
                             ->withHeader('Content-Type', 'application/json')
                             ->write('
                                    {
                                        "status": "Fail",
                                        "message": "'.$retornoDados.'"
                                    }
                                ');
        
        
            
    }
        
    
    //if()
});


//Carrega todos os EndPoints criados na API
$app->run();


?>