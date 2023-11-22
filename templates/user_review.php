<?php

    require_once("models/User.php");

    $userModel = new User();

    $fullName = $userModel->getFullName($review->user);

    // verifica se o filme tem img
    if($review->user->image == "") {
      $review->user->image = "user.png";
    }

?>
<div class="col-md-12 review">
  <div class="row">
    <div class="col-md-1">
      <div class="profile-image-container review-image" style="background-image: url('<?= $BASE_URL ?>img/users/<?= $review->user->image ?>')"></div>
    </div>
    <div class="col-md-9 author-details-container">
      <h4 class="author-name">
        <a href="<?= $BASE_URL ?>profile.php?id=<?= $review->user->id ?>"><?= $fullName ?></a>
      </h4>
      <p><i class="fas fa-star"></i> <?= $review->rating ?></p>
    </div>
    <div class="col-md-12">
      <p class="comment-title">Comentário:</p>
      <p><?= $review->review ?></p>
    </div>
    <?php if($review->users_id === ($userData->id ?? null)): ?>
      <div class="col-md-12 buttons-container">
        <div class="buttons-right">
          <a href="<?= $BASE_URL ?>editreview.php?id=<?= $review->id ?>" class="review-edit-btn">
            <i class="far fa-edit"></i> 
          </a>
          <form action="<?= $BASE_URL ?>review_process.php" method="POST">
            <input type="hidden" name="type" value="delete">
            <input type="hidden" name="id" value="<?= $review->id ?>">
            <input type="hidden" name="movies_id" value="<?= $review->movies_id ?>">
            <button type="submit" class="review-delete-btn">
              <i class="fas fa-times"></i> 
            </button>
          </form>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>