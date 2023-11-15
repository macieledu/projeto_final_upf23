<?php
  require_once("templates/header.php");

  require_once("models/User.php");
  require_once("dao/UserDAO.php");
  require_once("dao/MovieDAO.php");
  require_once("dao/ReviewDAO.php");

  $user = new User();
  $review = new Review();
  $userDao = new UserDao($conn, $BASE_URL);
  $reviewDao = new ReviewDao($conn, $BASE_URL);

  $userData = $userDao->verifyToken(true);

  $movieDao = new MovieDAO($conn, $BASE_URL);

  $id = filter_input(INPUT_GET, "id");

  if(empty($id)) {

    $message->setMessage("O review não foi encontrado!", "error", "index.php");

  } else {

    $review = $reviewDao->findById($id);

    
    if(!$review) {

      $message->setMessage("O review não foi encontrado!", "error", "index.php");

    }

  }

?>
  <div id="main-container" class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12" id="review-form-container">
                <h4>Atualize sua avaliação:</h4>
                <p class="page-description">Edite aqui o seu review</p>
                <form action="<?= $BASE_URL ?>review_process.php" id="review-form" method="POST">
                    <input type="hidden" name="type" value="update">
                    <input type="hidden" name="reviews_id" value="<?= $review->id ?>">
                    <input type="hidden" name="movies_id" value="<?= $review->movies_id ?>">
                    <div class="form-group">
                        <label for="rating">Nota do filme:</label>
                        <select name="rating" id="rating" class="form-control">
                            <option value="">Selecione</option>
                            <?php for ($i = 10; $i >= 1; $i--): ?>
                                <option value="<?= $i ?>" <?= ($i == $review->rating) ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="review">Seu comentário:</label>
                        <textarea name="review" id="review" rows="3" class="form-control" placeholder="O que você achou do filme?"><?= $review->review ?></textarea>
                    </div>
                    <input type="submit" class="btn card-btn" value="Enviar comentário">
                </form>
            </div>
        </div>
    </div>
</div>
<?php
  require_once("templates/footer.php");
?>