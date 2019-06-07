<?php
require_once 'includes/culturalCompetence.php';

$menu = new ExcelMenuPage();
$menu->link(array('bootstrap.min.css', 'page.css', 'excelMenu.css', 'bootstrap.min.js'));
$menu->display($type = null, $studyCode = null, $id = null);