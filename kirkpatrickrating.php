<?php
require_once 'includes/culturalCompetence.php';

$type = Input::get('type');
$studyCode = Input::get('StudyCode');

if(!empty($_GET['reviewerID'])){
    $reviewerID = Input::get('reviewerID');
}else{
    $reviewerID =Session::get('reviewerID');
}

$kirk = new KirkpatrickRatingPage();
$kirk->link(array('bootstrap.min.css', 'page.css', 'kirkpatrickRating.css'));

if(!empty($studyCode)){
    $kirk->display($type, $studyCode, $reviewerID);
}else{
    $kirk->display($type, $studyCode = null, $reviewerID);
}
