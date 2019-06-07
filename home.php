<?php
require_once 'includes/culturalCompetence.php';

$home = new HomePage();
$home->link(array('page.css','home.css'));
$home->display($type = null, $code = null, $id = null);