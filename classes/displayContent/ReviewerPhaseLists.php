<?php
require_once 'classes/ChecklistScreen.php';
require_once 'classes/utility/Database.php';
require_once 'classes/DataExtraction.php';
require_once 'classes/DataExtraction.php';
require_once 'classes/forms/FormHelper.php';

class ReviewerPhaseLists {

    public static function displayArticleScreenChecklist($reviewerID) {
        ?>
        <div id="wrapper">
            <div id="article_list">
                <h1>Articles to Screen</h1>
                <?php
                $screen = new ChecklistScreen();
                $studyCodes = $screen->getReviewerAssignedStudyCodes($reviewerID);
                if (!empty($studyCodes)) {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Study Code</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Screen</th>
                                <th class="text-center">View Article</th>
                                <th class="text-center">Download Article</th>
                                <th class="text-center">View Inventory</th>
                                <th class="text-center">View Checklist</th>
                                <th class="text-center">Edit Checklist</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $db = Database::getInstance();
                            for ($i = 0; $i < count($studyCodes); $i++) {
                                $db->query("SELECT Title FROM study_inventory_t WHERE StudyCode = '$studyCodes[$i]'");

                                foreach ($db->getResults() as $article) {

                                    $db->query("SELECT IsDisabled FROM article_process_stage_t WHERE StudyCode = '$studyCodes[$i]' AND ReviewerID = '$reviewerID'");
                                    $isDisabled = $db->getFirstResult()->IsDisabled;
                                    ?>
                                    <tr>
                                        <td style="vertical-align:middle" class="text-center"><?php echo $studyCodes[$i]; ?></td>
                                        <?php
                                        if ($isDisabled == 0) {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center"><?php echo $article->Title; ?></td>
                                            <?php
                                        } else {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center duplicate"><?php echo $article->Title; ?></td>
                                            <?php
                                        }
                              
                                        $db->query("SELECT StudyCode FROM article_screen_checklist_t WHERE StudyCode = '$studyCodes[$i]' AND ReviewerID = '$reviewerID'");
                                        if ($db->getCount() > 0) {
                                            $exists = true;
                                        } else {
                                            $exists = false;
                                        }

                                        //screen
                                        if (!$exists) {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center">
                                                <a href="articlescreencreate.php?StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center">
                                                <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                                            </td>
                                            <?php
                                        }
                                        ?>

                                        <td style="vertical-align:middle" class="text-center">
                                            <a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                <span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span></a>
                                        </td>

                                        <td style="vertical-align:middle" class="text-center">
                                            <a href="studyinventory.php?type=download&StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>
                                        </td>

                                        <td style="vertical-align:middle" class="text-center">
                                            <a href="studyinventory.php?type=view&StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                <span class="glyphicon glyphicon-file" aria-hidden="true"></span></a>
                                        </td>

                                        <?php
                                        //view checklist
                                        if ($exists) {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center">
                                                <a href="articlescreen.php?type=view&StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center">
                                                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                                            </td>
                                            <?php
                                        }

                                        //edit checklist
                                        if ($exists) {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center">
                                                <a href="articlescreenedit.php?StudyCode=<?php echo $studyCodes[$i]; ?>&reviewerID=<?php echo $reviewerID; ?>">
                                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center">
                                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                            </td>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo '<h1>No articles have been assigned to you<h1>';
                }
                ?>
            </div>
        </div>
        <?php
    }

    public static function displayDataExtractionList($reviewerID) {
        ?>
        <div id="wrapper_extract_list">
            <div id="extract_list">
                <h1>Articles to Data Extract</h1>
                <?php
                $extraction = new DataExtraction();
                $studyCodes = $extraction->getAssignedStudyCodesHigherThanPhase($reviewerID, 2);

                if (empty($studyCodes)) {
                    ?>
                    <h1>No Articles to Data Extract</h1>

                    <?php
                } else {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Study Code</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Extract</th>
                                <th class="text-center">View Article</th>
                                <th class="text-center">Download Article</th>
                                <th class="text-center">View Extraction</th>
                                <th class="text-center">Edit Extraction</th>
                                <th class="text-center">Other Reviewer(s) to Extract</th>
                            </tr>
                        </thead>
                        <tbody>
                        <br/>
                        <?php
                        for ($i = 0; $i < count($studyCodes); $i++) {
                            $db = Database::getInstance();
                            $db->select('study_inventory_t', array('StudyCode', '=', $studyCodes[$i]));

                            foreach ($db->getResults() as $extract) {
                                $db->query("SELECT IsDisabled FROM article_process_stage_t WHERE StudyCode = '$studyCodes[$i]' AND ReviewerID = '$reviewerID'");
                                $isDisabled = $db->getFirstResult()->IsDisabled;
                                ?>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><?php echo $studyCodes[$i]; ?></td>
                                    <?php
                                    if ($isDisabled == 0) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><?php echo $extract->Title; ?></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center duplicate"><?php echo $extract->Title; ?></td>
                                        <?php
                                    }
                                  
                                    $db->query("SELECT StudyCode FROM data_extraction_t WHERE StudyCode = '$studyCodes[$i]'");
                                    if ($db->getCount() > 0) {
                                        $exists = true;
                                    } else {
                                        $exists = false;
                                    }

                                    //extract
                                    if (!$exists) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center">
                                            <a href="dataextractioncreate.php?StudyCode=<?php echo $studyCodes[$i]; ?> "><span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span></a>
                                        </td>
                                        <?php
                                    } else {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center">
                                            <span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span>
                                        </td>
                                        <?php
                                    }
                                    ?>

                                    <td style="vertical-align:middle" class="text-center">
                                        <a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $studyCodes[$i]; ?>">
                                            <span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span></a>
                                    </td>

                                    <td style="vertical-align:middle" class="text-center">
                                        <a href="studyinventory.php?type=download&StudyCode=<?php echo $studyCodes[$i]; ?>">
                                            <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>
                                    </td>

                                    <?php
                                    if ($exists) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center">
                                            <a href="dataextraction.php?type=view&StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                                        </td>
                                        <?php
                                    } else {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center">
                                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                        </td>
                                        <?php
                                    }

                                    //edit extract
                                    if ($exists) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center">
                                            <a href="dataextractionedit.php?StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                        </td>
                                        <?php
                                    } else {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </td>
                                        <?php
                                    }

                                    $checkList = new ChecklistScreen();
                                    $reviewerIDsAssigned = $checkList->getReviewerIDsAssignedToStudyCode($studyCodes[$i]);
                                    $reviewersNames = $extraction->getReviewerNamesExceptSpecficReviewer($reviewerID, $reviewerIDsAssigned);
                                    ?>

                                    <td style="vertical-align:middle" class="text-center">
                                        <?php
                                        for ($j = 0; $j < count($reviewersNames); $j++) {
                                            echo '<p>' . $reviewersNames[$j] . '</p>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }

    public static function displayKirkpatrickList($reviewerID) {
        ?>
        <div id="wrapper_kirkpatrick_list">
            <div id="kirkpatrick_list">
                <h1>Articles for Kirkpatrick Rating</h1>

                <?php
                $extraction = new DataExtraction();
                $studyCodes = $extraction->getAssignedStudyCodesHigherThanPhase($reviewerID, 4);

                if (empty($studyCodes)) {
                    echo '<h1>No Articles to Kirkpatrick Rate</h1>';
                } else {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Study Code</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Download Rating Rubric</th>
                                <th class="text-center">View Article</th>
                                <th class="text-center">Download Article</th>
                                <th class="text-center">View Rating</th>
                                <th class="text-center">Edit Rating</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $db = Database::getInstance();
                            for ($i = 0; $i < count($studyCodes); $i++) {
                                $db->query("SELECT Title FROM study_inventory_t WHERE "
                                        . "StudyCode = '$studyCodes[$i]'");

                                foreach ($db->getResults() as $kirkpatrick) {
                                  $db->query("SELECT IsDisabled FROM article_process_stage_t WHERE StudyCode = '$studyCodes[$i]' AND ReviewerID = '$reviewerID'");
                                    $isDisabled = $db->getFirstResult()->IsDisabled;
                                    ?>
                                    <tr>
                                        <td style="vertical-align:middle" class="text-center"><?php echo $studyCodes[$i]; ?></td>
                                        <?php
                                        if ($isDisabled == 0) {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center"><?php echo $kirkpatrick->Title; ?></td>
                                            <?php
                                        } else {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center duplicate"><?php echo $kirkpatrick->Title; ?></td>
                                            <?php
                                        }
                                        
                                        $db->query("SELECT StudyCode FROM "
                                                . "kirkpatrick_rating_t WHERE StudyCode = "
                                                . "'$studyCodes[$i]' AND ReviewerID = '$reviewerID'");
                                        if ($db->getCount() > 0) {
                                            $exists = true;
                                        } else {
                                            $exists = false;
                                        }

                                        //create quests
                                        if (!$exists) {
                                            ?>
                                            <td  style="vertical-align:middle"class="text-center">
                                                <a href="kirkpatrickratingcreate.php?StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                    <span class="glyphicon glyphicon-stats" aria-hidden="true"></span></a>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td  style="vertical-align:middle"class="text-center">
                                                <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                                            </td>
                                            <?php
                                        }
                                        ?>

                                        <td style="vertical-align:middle" class="text-center">
                                            <a href="kirkpatrickrating.php?type=downloadRubric">
                                                <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a></td>


                                        <td style="vertical-align:middle" class="text-center">
                                            <a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                <span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span></a>
                                        </td>

                                        <td style="vertical-align:middle" class="text-center">
                                            <a href="studyinventory.php?type=download&StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>
                                        </td>

                                        <?php
                                        if ($exists) {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center">
                                                <a href="kirkpatrickrating.php?type=view&StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center">
                                                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                            </td>
                                            <?php
                                        }

                                        //edit rating
                                        if ($exists) {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center">
                                                <a href="kirkpatrickratingedit.php?StudyCode=<?php echo $studyCodes[$i]; ?>&reviewerID=<?php echo $reviewerID; ?>">
                                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center">
                                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                            </td>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }

    public static function displayQuestsList($reviewerID) {
        ?>
        <div id="wrapper_quests_list">
            <div id="quests_list">
                <h1>Articles to QUESTS Appraisal</h1>
                <?php
                $extraction = new DataExtraction();
                $studyCodes = $extraction->getAssignedStudyCodesHigherThanPhase($reviewerID, 3);

                if (empty($studyCodes)) {
                    echo '<h1>No Articles to Quests Appraisal</h1>';
                } else {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Study Code</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Quests</th>
                                <th class="text-center">Download Rubric</th>
                                <th class="text-center">View Article</th>
                                <th class="text-center">Download Article</th>
                                <th class="text-center">View Quests</th>
                                <th class="text-center">Edit Quests</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $db = Database::getInstance();
                            for ($i = 0; $i < count($studyCodes); $i++) {
                                $db->query("SELECT Title FROM study_inventory_t WHERE "
                                        . "StudyCode = '$studyCodes[$i]'");

                                foreach ($db->getResults() as $quests) {
                                    $db->query("SELECT IsDisabled FROM article_process_stage_t WHERE StudyCode = '$studyCodes[$i]' AND ReviewerID = '$reviewerID'");
                                    $isDisabled = $db->getFirstResult()->IsDisabled;
                                    ?>
                                    <tr>
                                        <td style="vertical-align:middle" class="text-center"><?php echo $studyCodes[$i]; ?></td>
                                        <?php
                                        if ($isDisabled == 0) {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center"><?php echo $quests->Title; ?></td>
                                            <?php
                                        } else {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center duplicate"><?php echo $quests->Title; ?></td>
                                            <?php
                                        }
                              
                                        $db->query("SELECT StudyCode FROM "
                                                . "quest_appraise_t WHERE StudyCode = "
                                                . "'$studyCodes[$i]' AND ReviewerID = '$reviewerID'");
                                        if ($db->getCount() > 0) {
                                            $exists = true;
                                        } else {
                                            $exists = false;
                                        }

                                        //create quests
                                        if (!$exists) {
                                            ?>
                                            <td  style="vertical-align:middle"class="text-center">
                                                <a href="questsappraisalcreate.php?StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                    <span class="glyphicon glyphicon-stats" aria-hidden="true"></span></a>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td  style="vertical-align:middle"class="text-center">
                                                <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                                            </td>
                                            <?php
                                        }
                                        ?>

                                        <td style="vertical-align:middle" class="text-center">
                                            <a href="questsappraisal.php?type=downloadRubric">
                                                <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a></td>


                                        <td style="vertical-align:middle" class="text-center">
                                            <a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                <span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span></a>
                                        </td>


                                        <td style="vertical-align:middle" class="text-center">
                                            <a href="studyinventory.php?type=download&StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>
                                        </td>

                                        <?php
                                        if ($exists) {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center">
                                                <a href="questsappraisal.php?type=view&StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center">
                                                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                            </td>
                                            <?php
                                        }

                                        //edit quests
                                        if ($exists) {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center">
                                                <a href="questsappraisaledit.php?StudyCode=<?php echo $studyCodes[$i]; ?>">
                                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td style="vertical-align:middle" class="text-center">
                                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                            </td>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }

}
