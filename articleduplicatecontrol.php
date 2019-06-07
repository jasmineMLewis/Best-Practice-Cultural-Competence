<?php
include 'includes/culturalCompetence.php';

$type = Input::get('type');
$reviewerID = Input::get('reviewerID');
$studyCode = Input::get('StudyCode');

$admin = new AdminPage();
$admin->link(array('bootstrap.min.css', 'page.css', 'admin.css'));

if (!empty($reviewerID) && empty($studyCode)) {
    $admin->display($type, $reviewerID, $studyCode = null);
} else if (empty($reviewerID) && !empty($studyCode)) {
    $admin->display($type, $reviewerID = null, $studyCode);
} else if (!empty($studyCode) && !empty($reviewerID)) {
    $admin->display($type, $reviewerID, $studyCode);
} else {
    $admin->display($type, $reviewerID = null, $studyCode = null);
}