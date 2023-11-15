<?php
var_dump($_POST);

  require_once("globals.php");
  require_once("db.php");
  require_once("models/Movie.php");
  require_once("models/Review.php");
  require_once("models/Message.php");
  require_once("dao/UserDAO.php");
  require_once("dao/MovieDAO.php");
  require_once("dao/ReviewDAO.php");

  $message = new Message($BASE_URL);
  $userDao = new UserDAO($conn, $BASE_URL);
  $movieDao = new MovieDAO($conn, $BASE_URL);

  $type = filter_input(INPUT_POST, "type");

  $reviewDao = new ReviewDao($conn, $BASE_URL);

  $userData = $userDao->verifyToken();

  if($type === "create") {

    $rating = filter_input(INPUT_POST, "rating");
    $review = filter_input(INPUT_POST, "review");
    $movies_id = filter_input(INPUT_POST, "movies_id");
    $users_id = $userData->id;

    $reviewObject = new Review();

    $movieData = $movieDao->findById($movies_id);

    // valida existencia do movie
    if($movieData) {

      // verifica dados mínimos
      if(!empty($rating) && !empty($review) && !empty($movies_id)) {

        $reviewObject->rating = $rating;
        $reviewObject->review = $review;
        $reviewObject->movies_id = $movies_id;
        $reviewObject->users_id = $users_id;

        $reviewDao->create($reviewObject, $movies_id);

      } else {

        $message->setMessage("Você precisa inserir a nota e o comentário!", "error", "back");

      }

    } else {

      $message->setMessage("Informações inválidas!", "error", "index.php");

    }

  }else if($type === "delete") {

    $id = filter_input(INPUT_POST, "id");

    $reviewDao = new ReviewDao($conn, $BASE_URL);
    $review = $reviewDao->findById($id);

    if($review) {

      // verifica se o filme pertence ao usuário
      if($review->users_id === $userData->id) {

        $reviewDao->destroy($review->id, $review->movies_id);

      } else {

        $message->setMessage("O review so pode ser deletado por quem o criou!", "error", "index.php");

      }

    } else {

      $message->setMessage("Informações inválidas!", "error", "index.php");

    }

  }else if($type === "update") { 

    //recebe dados
    $rating = filter_input(INPUT_POST, "rating");
    $review = filter_input(INPUT_POST, "review");
    $id = filter_input(INPUT_POST, "reviews_id");
    $movies_id = filter_input(INPUT_POST, "movies_id");

    $reviewDao = new ReviewDao($conn, $BASE_URL);

    $reviewData = $reviewDao->findById($id);

    if(!empty($rating) && !empty($review)) {

      $reviewData->review = $review;
      $reviewData->rating = $rating;

      $reviewDao->update($reviewData, $movies_id);

    } else {

      $message->setMessage("Você não pode deixar os campos em branco!", "error", "back");

    }

  
  }else {

    $message->setMessage("Informações inválidas!", "error", "index.php");

  }