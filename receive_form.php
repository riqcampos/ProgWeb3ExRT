<?php

require 'conexao.php';
require 'jwtclass.php';
$myjwt = new myJWT();
$myrefresh = new myRefreshToken();

$nomeUsuario = $_POST["usuario"];
$senhaUsuario = $_POST["senha"];
$sql = "select * from usuario where nomeUser = '". $nomeUsuario ."' and senhaUser = '". $senhaUsuario ."'"; 
$resultadoQuery = mysqli_query($conn, $sql);

if ($resultadoQuery->num_rows == 0 ){
    die("usuário ou senha inválidos");
}

$arrayQuery = $resultadoQuery->fetch_assoc();

echo "<BR>";
echo "usuário digitado: " . $arrayQuery["idUser"];
echo "<BR>";
echo "<BR>";
echo "senha digitada: " . $arrayQuery["senhaUser"];

$idU = $arrayQuery["idUser"];

$dt = date('d-m-Y h:i:s a', time());
$payload = [
    'iss' => 'localhost',
    'nome' => $arrayQuery["nomeUser"],
    'datahora' => $dt
    'tm' => time() + 150, 
    'refresh' => $refresh_token
    ];
    
    print_r($payload);

    echo "<BR>";
    echo "<BR>";
    $token = $myjwt->criaToken($conn, $payload, $idU);
    echo $token;

    echo "<BR>";
    echo "<BR>";
    echo "Validação do token: <br>";

        if ($myjwt->validaToken($conn, $token, $idU)){
            echo "Sim<Br>";

            if ($myrefresh->validate_token($token,$refresh_token))
            {
                $payload = $myjwt->get_payload($token);
                $payload = (array)json_decode($payload);
                if($payload['tm'] <= time())
                {
                    $payload['tm'] = time() + 150;
                }
                $token = $myjwt->generate_token($payload);
                $remaining_time = $payload["tm"] - time();
                echo "Token vai durar por <b>{$remaining_time}</b> segundos<br><br>";
            }else{
                die("Refresh token inválido!<br><br>");
            }

        }
        else{
            echo "Não<br>";
        }

?>