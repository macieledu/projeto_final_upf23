<?php

    require_once("globals.php");
    require_once("db.php");
    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");

    $message = new Message($BASE_URL);

    $userDAO = new UserDAO($conn, $BASE_URL);

    // tipo do form
    $type = filter_input(INPUT_POST, "type");
    
    if($type === "register"){

        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

        if($name && $lastname && $email && $password) {

            //verfica senhas iguais
            if($password === $confirmpassword){

                //verifica tamanho da senha
                if(strlen($password) >= 6) {

                    //verifica se o email já está cadastrado
                    if($userDAO->findByEmail($email) === false){

                        $user = new User();

                        //token e senha
                        $userToken = $user->generateToken();
                        $finalPassword = $user->generatePassword($password);

                        $user->name = $name;
                        $user->lastname = $lastname;
                        $user->email = $email;
                        $user->password = $finalPassword;
                        $user->token = $userToken;

                        $auth = true;

                        $userDAO->create($user, $auth);

                    } else {

                        $message->setMessage("E-mail já cadastrado" , "error" , "back");

                    }

                }else {
                    $message->setMessage("A senha precisa ter no mínimo 12 caracteres" , "error" , "back");
                }    
            }else{
                $message->setMessage("As senhas não são iguais" , "error" , "back");
            }

        } else {

            $message->setMessage("Por favor, preencha todos os campos." , "error" , "back");

        }

    } else if($type === "login"){

        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");

        // tenta autenticar
        if($userDAO->authenticateUser($email, $password)) {

            $message->setMessage("Seja bem-vindo!" , "success" , "editprofile.php");

        }else {

            $message->setMessage("Usuário e/ou senha incorretos." , "error" , "back");

        }

    } else {

        $message->setMessage("Informações inválidas!" , "error" , "index.php");

    }