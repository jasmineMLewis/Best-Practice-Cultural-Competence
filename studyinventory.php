<?php
require_once 'includes/culturalCompetence.php';

$type = Input::get('type');
$studyCode = Input::get('StudyCode');

$studyInventory = new StudyInventoryPage();
$studyInventory->link(array('bootstrap.min.css', 'page.css',
    'studyInventory.css'));


if(!empty($studyCode)){
    $studyInventory->display($type, $studyCode, $reviewerID = null);
}else{
    $studyInventory->display($type, $studyCode = null, $reviewerID = null);
}



    