<?php

include 'includes/culturalCompetence.php';

$type = Input::get('type');

$excel = new Excel();
switch ($type) {
    case 'studyInventory':
        $excel->exportStudyInventory();
        break;
    case 'articleScreenChecklist':
        $excel->exportArticleScreenChecklist();
        break;
    case 'dataExtraction':
        $excel->exportDataExtraction();
        break;
     case 'questsAppraisal':
        $excel->exportQuestsAppraisal();
        break;
     case 'kirkpatrickRating':
        $excel->exportKirkpatrickRating();
        break;
    default:
        break;
}