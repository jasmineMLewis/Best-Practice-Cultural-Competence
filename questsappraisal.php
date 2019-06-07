<?php

require_once 'includes/culturalCompetence.php';

$type = Input::get('type');
$studyCode = Input::get('StudyCode');

if(!empty($_GET['reviewerID'])){
    $reviewerID = Input::get('reviewerID');
}else{
    $reviewerID =Session::get('reviewerID');
}

$quests = new QuestsAppraisalPage();
$quests->link(array('bootstrap.min.css', 'page.css', 'questsAppraisal.css', 'questsAppraisal.js'));

if(!empty($studyCode)){
    $quests->display($type, $studyCode, $reviewerID);
}else{
    $quests->display($type, $studyCode = null, $reviewerID);
}
