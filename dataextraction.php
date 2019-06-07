<?php

require_once 'includes/culturalCompetence.php';

$type = Input::get('type');
$studyCode = Input::get('StudyCode');

if(!empty($_GET['reviewerID'])){
    $reviewerID = Input::get('reviewerID');
}else{
    $reviewerID =Session::get('reviewerID');
}

$screen = new DataExtractionPage();
$screen->link(array('bootstrap.min.css', 'page.css', 'dataExtraction.css', 'dataExtraction.js'));

if(!empty($studyCode)){
    $screen->display($type, $studyCode, $reviewerID);
}else{
    $screen->display($type, $studyCode = null, $reviewerID);
}