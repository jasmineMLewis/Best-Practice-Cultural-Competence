<?php
require_once 'includes/culturalCompetence.php';

$menu = new AdminArticleMenuPage();
$menu->link(array('bootstrap.min.css', 'page.css', 'adminMenu.css'));
$menu->display($type = null, $studyCode = null, $id = null);