<?php

require_once 'includes/culturalCompetence.php';

$reviewerMenu = new ReviewerMenuPage();
$reviewerMenu->link(array('bootstrap.min.css', 'page.css', 'reviewerMenu.css', 'bootstrap.min.js'));
$reviewerMenu->display($type = null, $studyCode = null, $id = null);

