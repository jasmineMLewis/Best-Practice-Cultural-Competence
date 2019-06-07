<?php
require_once 'includes/culturalCompetence.php';

$adminMenu = new AdminMenuPage();
$adminMenu->link(array('bootstrap.min.css', 'page.css', 'adminMenu.css', 'bootstrap.min.js'));
$adminMenu->display($type = null, $studyCode = null, $id = null);