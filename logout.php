<?php
require_once 'includes/culturalCompetence.php';

$logout = new Reviewer();
$logout->logout();
//header('Location: index.php');
header('Location: home.php');
exit();