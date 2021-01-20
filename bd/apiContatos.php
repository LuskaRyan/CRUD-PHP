<?php 

    function listarContatos($id)
    {
        
        /*Abre a conexão com o BD*/

        //Import do arquivo de Variaveis e Constantes
        require_once('../modulo/config.php');

        //Import do arquivo de função para conectar no BD
        require_once('conexaoMysql.php');

        //chama a função que vai estabelecer a conexão com o BD
        if(!$conex = conexaoMysql())
        {
            echo("<script> alert('".ERRO_CONEX_BD_MYSQL."'); </script>");
            //die; //Finaliza a interpretação da página
        }

            $sql = "select tblcontatos.*, tblestados.sigla 
                    from tblcontatos, tblestados
                    where tblcontatos.idEstado = tblestados.idEstado
                    and tblcontatos.statusContato = 1
                    ";
        
            //Validação para filtrar pelo ID
            if($id > 0)
                $sql = $sql . " and tblcontatos.idContato=".$id;
                       
            
            $sql = $sql . " order by tblcontatos.nome asc";
                  

            $select = mysqli_query($conex, $sql);
        
            

            while($rsContatos = mysqli_fetch_assoc($select))
            {
                
                $dados [] = array (
                
                        "idContato"      => $rsContatos['idContato'],
                        "nome"           => $rsContatos['nome'],
                        "celular"        => $rsContatos['celular'],
                        "email"          => $rsContatos['email'],
                        "idEstado"       => $rsContatos['idEstado'],
                        "sigla"          => $rsContatos['sigla'],
                        "dataNascimento" => $rsContatos['dataNascimento'],
                        "sexo"           => $rsContatos['sexo'],
                        "obs"            => $rsContatos['obs'],
                        "foto"           => $rsContatos['foto'],
                        "statusContato"  => $rsContatos['statusContato']
                
                    );
                

            }
        /*
            $headerDados = array (
                "status"    => "success",
                "data"      => "2020-11-25",
                "contatos"  => $dados
            
            ); 
            */
            
        if(isset($dados))
            $listContatosJSON = convertJSON($dados);
        else
            return false;
        
                
        
        //Verifica se foi gerado um arquivo JSON 
        if(isset($listContatosJSON))
            return $listContatosJSON;
        else
            return false;
     
    }

    function buscarContatos($nome)
    {
        /*Abre a conexão com o BD*/

        //Import do arquivo de Variaveis e Constantes
        require_once('../modulo/config.php');

        //Import do arquivo de função para conectar no BD
        require_once('conexaoMysql.php');

        //chama a função que vai estabelecer a conexão com o BD
        if(!$conex = conexaoMysql())
        {
            echo("<script> alert('".ERRO_CONEX_BD_MYSQL."'); </script>");
            //die; //Finaliza a interpretação da página
        }
        
        $sql = "select tblcontatos.*, tblestados.sigla 
                    from tblcontatos, tblestados
                    where tblcontatos.idEstado = tblestados.idEstado
                    and tblcontatos.statusContato = 1
                    and tblcontatos.nome like '%".$nome."%'";
        
        $select = mysqli_query($conex, $sql);

            while($rsContatos = mysqli_fetch_assoc($select))
            {
                
                $dados[] = array (
                
                        "idContato"      => $rsContatos['idContato'],
                        "nome"           => $rsContatos['nome'],
                        "celular"        => $rsContatos['celular'],
                        "email"          => $rsContatos['email'],
                        "idEstado"       => $rsContatos['idEstado'],
                        "sigla"          => $rsContatos['sigla'],
                        "dataNascimento" => $rsContatos['dataNascimento'],
                        "sexo"           => $rsContatos['sexo'],
                        "obs"            => $rsContatos['obs'],
                        "foto"           => $rsContatos['foto'],
                        "statusContato"  => $rsContatos['statusContato']
                
                    );
                

            }

            
        if(isset($dados))
            $listContatosJSON = convertJSON($dados);
        else
            return false;
        
                
        
        //Verifica se foi gerado um arquivo JSON 
        if(isset($listContatosJSON))
            return $listContatosJSON;
        else
            return false;
        
        
    }

    function inserirContato($dadosContato)
    {
       /*Abre a conexão com o BD*/

    //Import do arquivo de Variaveis e Constantes
    require_once('../modulo/config.php');

    //Import do arquivo de função para conectar no BD
    require_once('conexaoMysql.php');


    //chama a função que vai estabelecer a conexão com o BD
    if(!$conex = conexaoMysql())
    {
        echo("<script> alert('".ERRO_CONEX_BD_MYSQL."'); </script>");
        //die; //Finaliza a interpretação da página
    }

        /*Variaveis*/
        $nome = (string) null;
        $celular = (string) null;
        $email = (string) null;
        $estado = (int) null;
        $dataNascimento = (string) null;
        $sexo = (string) null;
        $obs = (string) null;
        $foto = (string) "semFoto.png";
        $statusContato = (integer) 0;
        
        //Converte o formato JSON para um Array de dados
        //$dadosContato = convertArray($dados);

        /*Recebe todos os dados da API*/
        $nome = $dadosContato['nome'];
        $celular = $dadosContato['celular'];
        $email = $dadosContato['email'];
        $estado = $dadosContato['estado'];
        $dataNascimento = $dadosContato['dataNascimento'];
        $sexo = $dadosContato['sexo'];
        $obs = $dadosContato['obs'];

        $sql = "insert into tblcontatos 
                    (
                        nome, 
                        celular, 
                        email, 
                        idEstado, 
                        dataNascimento, 
                        sexo, 
                        obs,
                        foto,
                        statusContato
                    )
                    values
                    (
                        '". $nome ."',
                        '". $celular ."',
                        '". $email ."', 
                         ".$estado.",
                        '". $dataNascimento ."',
                        '". $sexo ."', 
                        '". $obs ."', 
                        '". $foto ."',
                        '". $statusContato ."'

                    )
                ";

        //echo($sql);
        //die;

        //Executa no BD o Script SQL

        if (mysqli_query($conex, $sql))
        {
            $dados = convertJSON($dadosContato);
            return $dados;
        }
        else
            return false;
    }

    function atualizarContato($dadosContato)
    {
       /*Abre a conexão com o BD*/

    //Import do arquivo de Variaveis e Constantes
    require_once('../modulo/config.php');

    //Import do arquivo de função para conectar no BD
    require_once('conexaoMysql.php');


    //chama a função que vai estabelecer a conexão com o BD
    if(!$conex = conexaoMysql())
    {
        echo("<script> alert('".ERRO_CONEX_BD_MYSQL."'); </script>");
        //die; //Finaliza a interpretação da página
    }

        /*Variaveis*/
        $id = (int) null
        $nome = (string) null;
        $celular = (string) null;
        $email = (string) null;
        $estado = (int) null;
        $dataNascimento = (string) null;
        $sexo = (string) null;
        $obs = (string) null;
        $foto = (string) "semFoto.png";
        $statusContato = (integer) 0;
        
        //Converte o formato JSON para um Array de dados
        //$dadosContato = convertArray($dados);

        /*Recebe todos os dados da API*/
        $nome = $dadosContato['nome'];
        $celular = $dadosContato['celular'];
        $email = $dadosContato['email'];
        $estado = $dadosContato['estado'];
        $dataNascimento = $dadosContato['dataNascimento'];
        $sexo = $dadosContato['sexo'];
        $obs = $dadosContato['obs'];

        $sql = "update tblcontatos set 
        nome = '".$nome."',
        celular = '".$celular."',
        email = '".$email."',
        idEstado = ".$estado.",
        dataNascimento = '".$dataNascimento."',
        sexo = '".$sexo."',
        obs = '".$obs."'
       
	    where idContato = " . $_SESSION['id'];

        //echo($sql);
        //die;

        //Executa no BD o Script SQL

        if (mysqli_query($conex, $sql))
        {
            $dados = convertJSON($dadosContato);
            return $dados;
        }
        else
            return false;
    }

    function atualizarFoto($file, $id)
    {
        //Import da função de Upload
        require_once('upload.php');
        
        //chama a função para fazer o upload do arquivo
        $foto = uploadFoto($file);

        
        if(is_numeric($foto))
        {

            if ($foto == 2)
                return "Extensão inválida!";
            elseif($foto == 3)
                return "Tamanho Inválido!";
        }else
        {
            //Import do arquivo de Variaveis e Constantes
            require_once('../modulo/config.php');

            //Import do arquivo de função para conectar no BD
            require_once('conexaoMysql.php');

            //chama a função que vai estabelecer a conexão com o BD
            if(!$conex = conexaoMysql())
            {
                echo("<script> alert('".ERRO_CONEX_BD_MYSQL."'); </script>");
                //die; //Finaliza a interpretação da página
            }
            
            $sql = "update tblcontatos set foto = '".$foto ."'
                    where idContato =".$id;
            
             if (mysqli_query($conex, $sql))
                 if(mysqli_affected_rows($conex) > 0)
                    return true;
                else
                    return false;
        
             else
               return false;
                
        }
    }

    //Converte um Array em JSON
    function convertJSON($objeto)
    {
        //forçamos o cabeçalho do arquivo a ser aplicação do tipo JSON
        header("content-type:application/json");

        //Converte a array de dados em JSON
        $listJSON = json_encode($objeto);
        
        return $listJSON;
    }

    //Converte um JSON em Array
    function convertArray($objeto)
    {
        var_dump($objeto);
        die;
        //Converte JSON em ARRAY
        $listArray = json_decode($objeto, true);
        
        return $listArray;
    }

?>