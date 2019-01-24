<?php

    class conexao
    {
      private $server;
      private $database;
      private $username;
      private $password;
    
      private $conn;
      
      public function  __construct(){
        $this->server   = "SUELEN\SQLEXPRESS";
        $this->database = "bdnet";
        $this->username = "sa";
        $this->password = "P@ct02ol7";
      }
      
      //----------------------------------------------------------------------------------------------------------------------------
      // conectar acessorestrito
      //----------------------------------------------------------------------------------------------------------------------------
      
      public function conectar()
      { 
        try {
           if(!isset($connection))
           {
                $connectionInfo = array( "Database"=>$this->database, "UID"=>$this->username, "PWD"=>$this->password);
                $conn = sqlsrv_connect($this->server, $connectionInfo);
           }

           return $conn;
       } catch (PDOException $e) 
       {
           $mensagem = "\nErro: " . $e->getMessage();
           throw new Exception($mensagem);
       }
      }
      
      //----------------------------------------------------------------------------------------------------------------------------
      // conectar acessorestrito
      //----------------------------------------------------------------------------------------------------------------------------
      
      public function conectarAcesso()
      { 
        try {
           if(!isset($connection))
           {
                $connectionInfo = array( "Database"=>"acessorestrito", "UID"=>$this->username, "PWD"=>$this->password);
                $conn = sqlsrv_connect($this->server, $connectionInfo);
           }
           return $conn;
       } catch (PDOException $e) 
       {
           $mensagem = "\nErro: " . $e->getMessage();
           throw new Exception($mensagem);
       }
      }
      
      //----------------------------------------------------------------------------------------------------------------------------
      // fechar conexão
      //----------------------------------------------------------------------------------------------------------------------------
      
      public function close()
      { 
         sqlsrv_close($this->conn);
      }
    }
?>


