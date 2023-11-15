<?php

  class Review {

    public $id;
    public $rating;
    public $review;
    public $users_id;
    public $movies_id;

  }

  interface ReviewDAOInterface {

    public function buildReview($data);
    public function create(Review $review, $movies_id);
    public function getMoviesReview($id);
    public function hasAlreadyReviewed($id, $userId);
    public function getRatings($id);
    public function findById($id);
    public function update(Review $review, $movies_id);
    public function destroy($id, $movies_id);

  }