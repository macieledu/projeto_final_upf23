<?php

  require_once("models/Review.php");
  require_once("models/Message.php");

  require_once("dao/UserDAO.php");

  class ReviewDao implements ReviewDAOInterface {

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url) {
      $this->conn = $conn;
      $this->url = $url;
      $this->message = new Message($url);
    }

    public function buildReview($data) {

      $reviewObject = new Review();

      $reviewObject->id = $data["id"];
      $reviewObject->rating = $data["rating"];
      $reviewObject->review = $data["review"];
      $reviewObject->users_id = $data["users_id"];
      $reviewObject->movies_id = $data["movies_id"];

      return $reviewObject;

    }

    public function findById($id) {
      if ($id !== "") {
          $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE id = :id");
          $stmt->bindParam(":id", $id);
          $stmt->execute();

          if ($stmt->rowCount() > 0) {
              $data = $stmt->fetch();
              return $this->buildReview($data);
          } else {
              return null; 
          }
      } else {
          return null; 
      }
  }

    public function destroy($id, $movies_id) {

      $stmt = $this->conn->prepare("DELETE FROM reviews WHERE id = :id");

      $stmt->bindParam(":id", $id);

      $stmt->execute();

      // Mensagem de sucesso por remover filme
      $redirectUrl = "movie.php?id=" . $movies_id;
      $this->message->setMessage("Review removido com sucesso!", "success", $redirectUrl);

    }

    public function update(Review $review, $movies_id) {
      $stmt = $this->conn->prepare("UPDATE reviews SET
          rating = :rating,
          review = :review
          WHERE id = :id
      ");
  
      $stmt->bindParam(":rating", $review->rating);
      $stmt->bindParam(":review", $review->review);
      $stmt->bindParam(":id", $review->id);
  
      $stmt->execute();
  
      // Mensagem de sucesso por editar filme
      $redirectUrl = "movie.php?id=" . $movies_id;
      $this->message->setMessage("Filme atualizado com sucesso!", "success", $redirectUrl);
  }

    public function create(Review $review, $movies_id) {

      $stmt = $this->conn->prepare("INSERT INTO reviews (
        rating, review, movies_id, users_id
      ) VALUES (
        :rating, :review, :movies_id, :users_id
      )");

      $stmt->bindParam(":rating", $review->rating);
      $stmt->bindParam(":review", $review->review);
      $stmt->bindParam(":movies_id", $review->movies_id);
      $stmt->bindParam(":users_id", $review->users_id);

      $stmt->execute();

      // Mensagem de sucesso por adicionar filme
      $redirectUrl = "movie.php?id=" . $movies_id;
      $this->message->setMessage("Crítica adicionada com sucesso!", "success", $redirectUrl);

    }

    public function getMoviesReview($id) {

      $reviews = [];

      $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movies_id = :movies_id");

      $stmt->bindParam(":movies_id", $id);

      $stmt->execute();

      if($stmt->rowCount() > 0) {

        $reviewsData = $stmt->fetchAll();

        $userDao = new UserDao($this->conn, $this->url);

        foreach($reviewsData as $review) {

          $reviewObject = $this->buildReview($review);

          // Chamar dados do usuário
          $user = $userDao->findById($reviewObject->users_id);

          $reviewObject->user = $user;

          $reviews[] = $reviewObject;
        }

      }

      return $reviews;

    }

    public function hasAlreadyReviewed($id, $userId) {

      $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movies_id = :movies_id AND users_id = :users_id");

      $stmt->bindParam(":movies_id", $id);
      $stmt->bindParam(":users_id", $userId);

      $stmt->execute();

      if($stmt->rowCount() > 0) {
        return true;
      } else {
        return false;
      }

    }

    public function getRatings($id) {

      $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movies_id = :movies_id");

      $stmt->bindParam(":movies_id", $id);

      $stmt->execute();

      if($stmt->rowCount() > 0) {

        $rating = 0;

        $reviews = $stmt->fetchAll();

        foreach($reviews as $review) {
          $rating += $review["rating"];
        }

        $rating = $rating / count($reviews);

      } else {

        $rating = "Não avaliado";

      }

      return $rating;

    }

  }