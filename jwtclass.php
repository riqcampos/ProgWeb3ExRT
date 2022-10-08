<?php

class myJWT {
   private $senha = "SenhaSecreta";
   public function criaToken($conn, $payload, $idU){
      $header = [
         'alg' => 'SHA256',
         'typ' => 'JWT'
      ];
      
      $header = json_encode($header);
      $header = base64_encode($header);
      
      $payload = json_encode($payload);
      $payload = base64_encode($payload);
      
      $signature = hash_hmac('sha256', "$header.$payload", $this->senha, true);
      $signature = base64_encode($signature);

      $token = "$header.$payload.$signature";

      $sql = "insert into token (hashToken, idUser) values ('". $token ."',". $idU .")"; 
      $resultadoQuery = mysqli_query($conn, $sql);
      
      return $token;
   }
    
   public function validaToken($conn, $tk, $idU){
      $arrayPartesToken = explode(".",$tk);
      $vheader = $arrayPartesToken[0];
      $vpayload = $arrayPartesToken[1];
      $vsignature = $arrayPartesToken[2];
      
      $signatureCheck = hash_hmac('sha256', "$vheader.$vpayload", $this->senha, true);
      
      $signatureCheck = base64_encode($signatureCheck);
      
      if ($vsignature == $signatureCheck){
         $retorno = true;
      }else {
         $retorno = false;
         $sql = "delete from token where idUser = ". $idU; 
         $resultadoQuery = mysqli_query($conn, $sql);
         $this->criaToken($conn, $vpayload, $idU);
      }
      
      return $retorno;
   }


}
?>