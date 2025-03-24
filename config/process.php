<?php

    session_start();

    include_once("connection.php");
    include_once("url.php");

    $data = $_POST;

    // MODIFICAÇÔES
    if(!empty($data)){

        // Criar contato
        if($data["type"] === "create"){
          $name = $data["name"];
          $phone = $data["phone"];
          $observations = $data["observation"];

          $query = "INSERT INTO contacts (name, phone, observation) VALUES (:name, :phone, :observation)";

          $stmt = $conn->prepare($query);

          $stmt->bindParam(":name", $name);
          $stmt->bindParam(":phone", $phone);
          $stmt->bindParam(":observation", $observations);

        try {

            $stmt->execute();
            $_SESSION["msg"] = "Contato criado com sucesso!";
        
        } catch(PDOException $e){
            // Erro na conexão
            $erro = $e->getMessage();
            echo "Erro: $erro";
        }
        }

        //Redirect HOME
        header("location:" . $BASE_URL . "../index.php");

    // SELEÇÃO DE DADOS
    } else {
        $id;

        if(!empty($_GET)){
            $id = $_GET["id"];
        }
        // Retorna os dados de um contato
        if(!empty($id)){

            $query = "SELECT * FROM contacts WHERE id = :id";

            $stmt = $conn->prepare($query);

            $stmt->bindParam(":id", $id);

            $stmt->execute();

            $contact = $stmt->fetch();

        } else {
            // Retorna todos os contatos
            $contacts = [];

            $query = "SELECT * FROM contacts";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            $contacts = $stmt->fetchAll();
        }
    }
    
    // FECHAR CONEXÂO
    $conn = null;
    
?>