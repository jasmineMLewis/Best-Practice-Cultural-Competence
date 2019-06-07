<?php
require_once 'classes/Admin.php';
require_once 'classes/ChecklistScreen.php';
require_once 'classes/utility/Database.php';
require_once 'classes/KirkpatrickRating.php';
require_once 'classes/QuestsAppraisal.php';

class AdminArticlePhaseLists {

    public static function displayAllArticleScreenChecklists() {
        $db = Database::getInstance();
        ?>
        <div id="wrapper_all_article_list">
            <div id="all_article_list">
                <h1>All Article Screen Checklists</h1>

                <?php
                $db->query("SELECT * FROM article_screen_checklist_t GROUP BY StudyCode");
                if ($db->getCount() < 1) {
                    echo '<h1>No Article Screens Submitted</h1>';
                } else {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Study Code</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Reviewer 1 Checklist</th>
                                <th class="text-center">Reviewer 2 Checklist</th>
                                <th class="text-center">Reviewer 3 Checklist</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($db->getResults() as $code) {
                                ?>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">
                                        <a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $code->StudyCode; ?>"><?php echo $code->StudyCode; ?></a>
                                    </td>

                                    <?php
                                    $db->select('study_inventory_t', array('StudyCode', '=', $code->StudyCode));
                                    $inventory = $db->getFirstResult();
                                    ?>
                                    <td style="vertical-align:middle" class="text-center"><?php echo $inventory->Title; ?></td>
                                    <?php
                                    $checklist = new ChecklistScreen();
                                    $reviewersIDs = $checklist->getReviewerIDsAssignedToStudyCode($code->StudyCode);

                                    for ($i = 0; $i < count($reviewersIDs); $i++) {
                                        echo '<td style="vertical-align:middle" class="text-center"> ';
                                        $db->query("SELECT reviewer_t.ReviewerID, reviewer_t.FirstName, reviewer_t.LastName"
                                                . " FROM article_screen_checklist_t "
                                                . "INNER JOIN reviewer_t ON article_screen_checklist_t.ReviewerID = reviewer_t.ReviewerID "
                                                . "WHERE article_screen_checklist_t.StudyCode = '$code->StudyCode' "
                                                . "AND article_screen_checklist_t.ReviewerID = '$reviewersIDs[$i]'");


                                        if ($db->getCount() >= 1) {
                                            ?>
                                    <p><?php echo $db->getFirstResult()->FirstName . ' ' . $db->getFirstResult()->LastName; ?></p>
                                    <a href="articlescreen.php?type=view&StudyCode=<?php echo $code->StudyCode; ?>&reviewerID=<?php echo $reviewersIDs[$i]; ?>">
                                        <span class="glyphicon glyphicon-eye-open"></span> View</a> | 
                                    <a href="articlescreenedit.php?StudyCode=<?php echo $code->StudyCode; ?>&reviewerID=<?php echo $reviewersIDs[$i]; ?>">
                                        <span class="glyphicon glyphicon-pencil"></span> Edit</td>
                                        <?php
                                    } else {
                                        $db->query("SELECT reviewer_t.ReviewerID, reviewer_t.FirstName, reviewer_t.LastName"
                                                . " FROM article_process_stage_t "
                                                . "INNER JOIN reviewer_t ON article_process_stage_t.ReviewerID = reviewer_t.ReviewerID "
                                                . "WHERE article_process_stage_t.StudyCode = '$code->StudyCode' "
                                                . "AND article_process_stage_t.ReviewerID = '$reviewersIDs[$i]' AND ArticleProcessID = '1'");
                                        echo '<p>' . $db->getFirstResult()->FirstName . ' ' . $db->getFirstResult()->LastName . '</p>';
                                        echo 'Not Submitted Yet';
                                    }
                                    ?>
                                    </td>
                                    <?php
                                }
                                ?>
                                </tr>
                                <?php
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

    public static function displayDataExtractionList() {
        $db = Database::getInstance();
        ?>
        <div id="wrapper_data_extraction_list">
            <div id="data_extraction_list">
                <h1>Article Data Extractions</h1>
                <?php
                $db->query(
                        "SELECT study_inventory_t.StudyCode, study_inventory_t.Title, "
                        . "reviewer_t.ReviewerID, reviewer_t.FirstName, reviewer_t.LastName "
                        . "FROM data_extraction_t "
                        . "INNER JOIN study_inventory_t ON data_extraction_t.StudyCode = study_inventory_t.StudyCode "
                        . "INNER JOIN reviewer_t ON data_extraction_t.ReviewerID = reviewer_t.ReviewerID ");

                if ($db->getCount() < 1) {
                    echo '<h1>No Data Extractions Completed</h1>';
                } else {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Study Code</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">View Data Extraction</th>
                                <th class="text-center">Edit Data Extraction</th>
                                <th class="text-center">Reviewers Names</th>
                                <th class="text-center">Reviewer Name Submission</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            foreach ($db->getResults() as $dataInfo) {
                                ?>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">
                                        <a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $dataInfo->StudyCode; ?>"><?php echo $dataInfo->StudyCode; ?></a>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><?php echo $dataInfo->Title; ?></td>
                                    <td style="vertical-align:middle" class="text-center"><a href="dataextraction.php?type=view&StudyCode=<?php echo $dataInfo->StudyCode; ?>">
                                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><a href="dataextractionedit.php?StudyCode=<?php echo $dataInfo->StudyCode; ?>">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    </td>

                                    <?php
                                    //get Reviewer Names Assigned to StudyCode
                                    $admin = new Admin();
                                    $reviewersAssignedToCode = $admin->getReviewersNamesAssignedToStudyCode($dataInfo->StudyCode);
                                    ?>

                                    <td style="vertical-align:middle" class="text-center">
                                        <?php
                                        for ($i = 0; $i < count($reviewersAssignedToCode); $i++) {
                                            echo '<p>' . $reviewersAssignedToCode[$i] . '</p>';
                                        }
                                        ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><?php echo $dataInfo->FirstName . ' ' . $dataInfo->LastName; ?></td>
                                </tr>
                                <?php
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

    public static function displayExcludeArticleScreenChecklists() {
        $db = Database::getInstance();
        ?>
        <div id="wrapper_exclude_list">
            <div id="exclude_list">
                <h1>Excluded Articles</h1>
                <?php
                $db->select('article_screen_checklist_final_decision_t', array('DecisionID', '=', '2'));
                if ($db->getCount() < 1) {
                    echo '<h1>No Excluded Articles</h1>';
                } else {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Study Code</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Reviewer 1 Checklist</th>
                                <th class="text-center">Reviewer 2 Checklist</th>
                                <th class="text-center">Reviewer 3 Checklist</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($db->getResults() as $code) {
                                ?>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">
                                        <a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $code->StudyCode; ?>"><?php echo $code->StudyCode; ?></a>
                                    </td>

                                    <?php
                                    $db->select('study_inventory_t', array('StudyCode', '=', $code->StudyCode));
                                    $inventory = $db->getFirstResult();
                                    ?>
                                    <td style="vertical-align:middle" class="text-center"><?php echo $inventory->Title; ?></td>
                                    <?php
                                    $checklist = new ChecklistScreen();
                                    $reviewersIDs = $checklist->getReviewerIDsAssignedToStudyCode($code->StudyCode);

                                    for ($i = 0; $i < count($reviewersIDs); $i++) {
                                        echo '<td style="vertical-align:middle" class="text-center"> ';
                                        $db->query("SELECT reviewer_t.ReviewerID, reviewer_t.FirstName, reviewer_t.LastName"
                                                . " FROM article_screen_checklist_t "
                                                . "INNER JOIN reviewer_t ON article_screen_checklist_t.ReviewerID = reviewer_t.ReviewerID "
                                                . "WHERE article_screen_checklist_t.StudyCode = '$code->StudyCode' "
                                                . "AND article_screen_checklist_t.ReviewerID = '$reviewersIDs[$i]'");
                                        ?>
                                <p><?php echo $db->getFirstResult()->FirstName . ' ' . $db->getFirstResult()->LastName; ?></p>
                                <a href="articlescreen.php?type=view&StudyCode=<?php echo $code->StudyCode; ?>&reviewerID=<?php echo $reviewersIDs[$i]; ?>">
                                    <span class="glyphicon glyphicon-eye-open"></span> View</a> | 
                                <a href="articlescreenedit.php?StudyCode=<?php echo $code->StudyCode; ?>&reviewerID=<?php echo $reviewersIDs[$i]; ?>">
                                    <span class="glyphicon glyphicon-pencil"></span> Edit</td>
                                    <?php
                                }
                                ?>
                                </tr>
                                <?php
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

    public static function displayFlagArticleScreenChecklists() {
        $db = Database::getInstance();
        ?>
        <div id="wrapper_flag_list">
            <div id="flag_list">
                <h1>Flag Articles</h1>

                <?php
                $db->select('article_screen_checklist_final_decision_t', array('DecisionID', '=', '3'));
                if ($db->getCount() < 1) {
                    echo '<h1>No Flaged Articles</h1>';
                } else {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Study Code</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Assign Reviewer</th>
                                <th class="text-center">Reviewer 1 Checklist</th>
                                <th class="text-center">Reviewer 2 Checklist</th>
                                <th class="text-center">Reviewer 3 Checklist</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            foreach ($db->getResults() as $code) {
                                ?>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $code->StudyCode; ?>"><?php echo $code->StudyCode; ?></a></td>

                                    <?php
                                    $db->select('study_inventory_t', array('StudyCode', '=', $code->StudyCode));
                                    ?>

                                    <td style="vertical-align:middle" class="text-center"><?php echo $db->getFirstResult()->Title; ?></td>
                                    <?php
                                    $checklist = new ChecklistScreen();
                                    $reviewersIDs = $checklist->getReviewerIDsAssignedToStudyCode($code->StudyCode);

                                    if (count($reviewersIDs) >= 3) {
                                        echo '<td style="vertical-align:middle" class="text-center"><span class="glyphicon glyphicon-user"></span></td>';
                                    } else {
                                        echo '<td style="vertical-align:middle" class="text-center"><a href="admin.php?type=assignReviewerToArticle&StudyCode=' . $code->StudyCode . '"><span class="glyphicon glyphicon-user"></span></a></td>';
                                    }

                                    for ($i = 0; $i < count($reviewersIDs); $i++) {
                                        $name = $db->select('reviewer_t', array('ReviewerID', '=', $reviewersIDs[$i]));
                                        ?>
                                        <td style="vertical-align:middle" class="text-center">
                                            <p><?php echo $name->getFirstResult()->FirstName . ' ' . $name->getFirstResult()->LastName; ?> </p>

                                            <?php
                                            $db->query("SELECT * FROM article_screen_checklist_t WHERE StudyCode = '$code->StudyCode' AND ReviewerID = $reviewersIDs[$i]");
                                            if ($db->getCount() < 1) {
                                                echo '<p>Not Submitted Yet</p>';
                                            } else {
                                                ?>
                                                <a href="articlescreen.php?type=view&StudyCode=<?php echo $code->StudyCode; ?>&reviewerID=<?php echo $reviewersIDs[$i]; ?>">
                                                    <span class="glyphicon glyphicon-eye-open"></span> View</a> | 
                                                <a href="articlescreenedit.php?StudyCode=<?php echo $code->StudyCode; ?>&reviewerID=<?php echo $reviewersIDs[$i]; ?>">
                                                    <span class="glyphicon glyphicon-pencil"></span> Edit
                                            </td>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php
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

    public static function displayIncludeArticleScreenChecklists() {
        $db = Database::getInstance();
        ?>
        <div id="wrapper_include_list">
            <div id="include_list">
                <h1>Include Articles</h1>
                <?php
                $db->select('article_screen_checklist_final_decision_t', array('DecisionID', '=', '1'));

                if ($db->getCount() < 1) {
                    echo '<h1>No Included Articles</h1>';
                } else {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Study Code</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Reviewer 1 Checklist</th>
                                <th class="text-center">Reviewer 2 Checklist</th>
                                <th class="text-center">Reviewer 3 Checklist</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($db->getResults() as $code) {
                                ?>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $code->StudyCode; ?>"><?php echo $code->StudyCode; ?></a></td>
                                    <?php
                                    $title = $db->select('study_inventory_t', array('StudyCode', '=', $code->StudyCode));
                                    ?>
                                    <td style="vertical-align:middle" class="text-center"><?php echo $title->getFirstResult()->Title; ?></td>

                                    <?php
                                    $checklist = new ChecklistScreen();
                                    $reviewersIDs = $checklist->getReviewerIDsAssignedToStudyCode($code->StudyCode);

                                    for ($i = 0; $i < count($reviewersIDs); $i++) {
                                        $name = $db->select('reviewer_t', array('ReviewerID', '=', $reviewersIDs[$i]));
                                        ?>
                                        <td style="vertical-align:middle" class="text-center">
                                            <p><?php echo $name->getFirstResult()->FirstName . ' ' . $name->getFirstResult()->LastName; ?></p>
                                            <a href="articlescreen.php?type=view&StudyCode=<?php echo $code->StudyCode; ?>&reviewerID=<?php echo $reviewersIDs[$i]; ?>">
                                                <span class="glyphicon glyphicon-eye-open"></span> View</a> | 
                                            <a href="articlescreenedit.php?StudyCode=<?php echo $code->StudyCode; ?>&reviewerID=<?php echo $reviewersIDs[$i]; ?>">
                                                <span class="glyphicon glyphicon-pencil"></span> Edit
                                        </td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <?php
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

    public static function displayKirkpatrickRatingList() {
        $db = Database::getInstance();
        ?>
        <div id="wrapper_kirkpatrick_rating_list">
            <div id="kirkpatrick_rating_list">
                <h1>Articles Kirkpatrick Rating</h1>
                <?php
                $db->query(
                        "SELECT study_inventory_t.StudyCode, study_inventory_t.Title "
                        . "FROM kirkpatrick_rating_t "
                        . "INNER JOIN study_inventory_t ON kirkpatrick_rating_t.StudyCode = study_inventory_t.StudyCode GROUP BY StudyCode ");

                if ($db->getCount() < 1) {
                    echo '<h1>No Kirkpatrick Rating</h1>';
                } else {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Study Code</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Inventory</th>
                                <th class="text-center">Download Rating Rubric</th>
                                <th class="text-center">Reviewer 1 Rating</th>
                                <th class="text-center">Reviewer 2 Rating</th>
                                <th class="text-center">Reviewer 3 Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($db->getResults() as $code) {
                                ?>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $code->StudyCode; ?>"><?php echo $code->StudyCode; ?></a></td>
                                    <td style="vertical-align:middle" class="text-center"><?php echo $code->Title; ?></td>
                                    <td style="vertical-align:middle" class="text-center"><a href="studyinventory.php?type=view&StudyCode=<?php echo $code->StudyCode; ?>"><span class="glyphicon glyphicon-eye-open"></span></a></td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <a href="kirkpatrickrating.php?type=downloadRubric">
                                            <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a></td>

                                    <?php
                                    $checklist = new ChecklistScreen();
                                    $reviewersIDs = $checklist->getReviewerIDsAssignedToStudyCode($code->StudyCode);
                                    for ($i = 0; $i < count($reviewersIDs); $i++) {
                                        $name = $db->select('reviewer_t', array('ReviewerID', '=', $reviewersIDs[$i]));
                                        ?>
                                        <td style="vertical-align:middle" class="text-center">
                                            <p><?php echo $name->getFirstResult()->FirstName . ' ' . $name->getFirstResult()->LastName; ?></p>

                                            <?php
                                            $kirkpatrick = new KirkpatrickRating();
                                            $db->query("SELECT * FROM kirkpatrick_rating_t WHERE StudyCode = '$code->StudyCode' AND ReviewerID = $reviewersIDs[$i]");

                                            if ($db->getCount() < 1) {
                                                echo '<p>Not Submitted Yet</p>';
                                            } else {
                                                ?>
                                                <p><a href="kirkpatrickrating.php?type=view&StudyCode=<?php echo $code->StudyCode; ?>&reviewerID=<?php echo $reviewersIDs[$i]; ?>">
                                                        <span class="glyphicon glyphicon-eye-open"></span> View</a> | 
                                                    <a href="kirkpatrickratingedit.php?StudyCode=<?php echo $code->StudyCode; ?>&reviewerID=<?php echo $reviewersIDs[$i]; ?>">
                                                        <span class="glyphicon glyphicon-pencil"></span> Edit</a>
                                                </p>

                                                <p><b>Grade</b> - <?php echo $kirkpatrick->getHighestKirkpatrickGrade($code->StudyCode, $reviewersIDs[$i]); ?></p>
                                            </td>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php
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

    public static function displayQuestsAppraisalList() {
        $db = Database::getInstance();
        ?>
        <div id="wrapper_quests_appraisal_list">
            <div id="quests_appraisal_list">
                <h1>Article QUESTS Appraisals</h1>
                <?php
                $db->query(
                        "SELECT study_inventory_t.StudyCode, study_inventory_t.Title "
                        . "FROM quest_appraise_t "
                        . "INNER JOIN study_inventory_t ON quest_appraise_t.StudyCode = study_inventory_t.StudyCode GROUP BY StudyCode ");

                if ($db->getCount() < 1) {
                    echo '<h1>No QUESTS Appraisals Completed</h1>';
                } else {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Study Code</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">View Data Extraction</th>
                                <th class="text-center">Download Rubric</th>
                                <th class="text-center">Reviewer 1</th>
                                <th class="text-center">Reviewer 2</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            foreach ($db->getResults() as $inventory) {
                                ?>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">
                                        <a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $inventory->StudyCode; ?>"><?php echo $inventory->StudyCode; ?></a>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><?php echo $inventory->Title; ?></td>
                                    <td style="vertical-align:middle" class="text-center"><a href="dataextraction.php?type=view&StudyCode=<?php echo $inventory->StudyCode; ?>">
                                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <a href="questsappraisal.php?type=downloadRubric"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>
                                    </td>

                                    <?php
                                    $checklist = new ChecklistScreen();
                                    $reviewersIDs = $checklist->getReviewerIDsAssignedToStudyCode($inventory->StudyCode);
                                    $quests = new QuestsAppraisal();

                                    for ($i = 0; $i < count($reviewersIDs); $i++) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"> 
                                            <?php
                                            $db->query("SELECT reviewer_t.ReviewerID, reviewer_t.FirstName, reviewer_t.LastName"
                                                    . " FROM quest_appraise_t "
                                                    . "INNER JOIN reviewer_t ON quest_appraise_t.ReviewerID = reviewer_t.ReviewerID "
                                                    . "WHERE quest_appraise_t.StudyCode = '$inventory->StudyCode' AND quest_appraise_t.ReviewerID = '$reviewersIDs[$i]'");
                                            if ($db->getCount() > 0) {
                                                ?>
                                                <p><?php echo $db->getFirstResult()->FirstName . ' ' . $db->getFirstResult()->LastName; ?></p>
                                                <p><a href="questsappraisal.php?type=view&StudyCode=<?php echo $inventory->StudyCode; ?>&reviewerID=<?php echo $reviewersIDs[$i]; ?>">
                                                        <span class="glyphicon glyphicon-eye-open"></span> View</a> | 
                                                    <a href="questsappraisaledit.php?StudyCode=<?php echo $inventory->StudyCode; ?>&reviewerID=<?php echo $reviewersIDs[$i]; ?>">
                                                        <span class="glyphicon glyphicon-pencil"></span> Edit</a>
                                                </p>
                                                <p>Score -- <b><?php echo $quests->calculateFinalScore($inventory->StudyCode, $reviewersIDs[$i]); ?></b></p>
                                                <?php
                                            } else {
                                                $name = $db->select('reviewer_t', array('ReviewerID', '=', $reviewersIDs[$i]));
                                                ?>
                                                <p><?php echo $name->getFirstResult()->FirstName . ' ' . $name->getFirstResult()->LastName; ?></p>
                                                <p>Not submitted Yet</p>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <?php
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
