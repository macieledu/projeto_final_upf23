<?php

  require_once("globals.php");
  require_once("db.php");
  require_once("models/User.php");
  require_once("models/Message.php");
  require_once("dao/UserDAO.php");

  $message = new Message($BASE_URL);

  $userDao = new UserDAO($conn, $BASE_URL);

  // resgata o tipo do formulário
  $type = filter_input(INPUT_POST, "type");

  if($type === "update") {

    $userData = $userDao->verifyToken();

    // recebe dados
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $bio = filter_input(INPUT_POST, "bio");

    $user = new User();

    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->email = $email;
    $userData->bio = $bio;

  // upload da imagem
  if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
    
    $image = $_FILES["image"];
    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
    $jpgArray = ["image/jpeg", "image/jpg"];

    if(in_array($image["type"], $imageTypes)) {

    // checar se jpg ou png
    if(in_array($image["type"], $jpgArray)) {
      $imageFile = imagecreatefromjpeg($image["tmp_name"]);
    } else {
      $imageFile = imagecreatefrompng($image["tmp_name"]);

      // verifica se a criação da imagem PNG foi bem-sucedida
      if(!$imageFile) {
          $message->setMessage("Falha ao processar a imagem PNG. Certifique-se de que é uma imagem válida.", "error", "back");
          exit; // ou redireciona
      }
    }

      $imageName = $user->imageGenerateName();

      imagejpeg($imageFile, "./img/users/" . $imageName, 100);

      $userData->image = $imageName;

    } else {

      $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");

    }
  }

    $userDao->update($userData);

  //atualiza senha
  } else if($type === "changepassword") {

    //recebe dados do post
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    //resgata dados
    $userData = $userDao->verifyToken();
    $id = $userData->id;

    if($password == $confirmpassword) {

      //cria um novo obj
      $user = new User();

      $finalPassword = $user->generatePassword($password);

      $user->password = $finalPassword;
      $user->id = $id;

      $userDao->changePassword($user);

    } else {
      $message->setMessage("As senhas não são iguais!", "error", "back");
    }

  } else {

    $message->setMessage("Informações inválidas!", "error", "index.php");

  }