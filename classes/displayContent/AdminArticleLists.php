<?php

require_once 'classes/Admin.php';
require_once 'classes/utility/Database.php';
require_once 'classes/ChecklistScreen.php';

class AdminArticleLists {

    public static function displayArticleList() {
        $db = Database::getInstance();
        ?>
        <div id="wrapper_article_list">
       <div id="article_list">
        <h1>Articles</h1>

        <?php
        $articleInfo = $db->query("SELECT StudyCode, Title, ReviewerID FROM study_inventory_t");
        if ($articleInfo->getCount() < 1) {
            echo '<h1>No Articles submitted</h1>';
        } else {
        ?>
            <h3><a href="excelmenu.php"><span class="glyphicon glyphicon-export" aria-hidden="true"></span>Export to Excel</a> 
            <?php echo str_repeat('&nbsp;', 50); ?>
             <a href="admin.php?type=articledupcontrol"><span class="glyphicon glyphicon-export" aria-hidden="true"></span> Article Duplication Controller</a></h3>
           
            <table class="table table-bordered">
            <thead>
                <tr class="success">
                    <th class="text-center">StudyCode</th>
                    <th class="text-center">Title</th>
                    <th class="text-center">Reviewers Assigned</th>
                    <th class="text-center">View/Edit Study Form</th>
                    <th class="text-center">Checklist Completion</th>
                    <th class="text-center">Checklist Include</th>
                    <th class="text-center">Checklist Exclude</th>
                    <th class="text-center">Checklist Flag</th>
                    <th class="text-center">Data Extraction Completion</th>
                    <th class="text-center">QUESTS Completion</th>
                    <th class="text-center">Kirkpatrick Rating Completion</th>
                </tr>
            </thead>
            <tbody>
                
            <?php
            foreach ($articleInfo->getResults() as $article) {
                ?>
                <tr>
                    <td style="vertical-align:middle" class="text-center">
                        <a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $article->StudyCode; ?>"><?php echo $article->StudyCode; ?></a></td>
                <td style="vertical-align:middle" class="text-center"><?php echo $article->Title; ?></td>

                <?php
                //reviewers assigned IDS and names
                $checklist = new ChecklistScreen();
                $reviewerIDs = $checklist->getReviewerIDsAssignedToStudyCode($article->StudyCode);
                
                $admin = new Admin();
                $reviewersNames = $admin->getReviewersNamesAssignedToStudyCode($article->StudyCode);
                ?>
                
                <td style="vertical-align:middle" class="text-center">
                <?php
                    for ($i = 0; $i < count($reviewersNames); $i++) {
                        $db->query("SELECT IsDisabled "
                            . "FROM article_process_stage_t WHERE StudyCode = '$article->StudyCode' AND ReviewerID = '$reviewerIDs[$i]' ");
                    if ($db->getFirstResult()->IsDisabled == 1) {
                        echo '<p class="duplicate">' . $reviewersNames[$i] . '</p>';
                        echo '<p><a href="admin.php?type=reassignReviewerToArticle&StudyCode=' . $article->StudyCode . '&reviewerID=' . $reviewerIDs[$i] . '"><span class="glyphicon glyphicon-random"></span> REASSIGN</a></p> ';
                    } else {
                        echo '<p>' . $reviewersNames[$i] . '</p>';
                        echo '<p><a href="admin.php?type=reassignReviewerToArticle&StudyCode=' . $article->StudyCode . '&reviewerID=' . $reviewerIDs[$i] . '"><span class="glyphicon glyphicon-random"></span> REASSIGN</a></p> ';
                    }
                  }
                  ?>
                </td>
                <td style="vertical-align:middle" class="text-center"><a href="studyinventory.php?type=edit&StudyCode=<?php echo $article->StudyCode; ?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                
                <td style="vertical-align:middle" class="text-center">               
                <?php
                //Checklist Compeletion
                for ($n = 0; $n < count($reviewerIDs); $n++) {
                    $db->query("SELECT article_screen_checklist_t.DecisionID, aps.IsDisabled, aps.StudyCode "
                            . "FROM article_screen_checklist_t "
                            . "INNER JOIN article_process_stage_t aps ON article_screen_checklist_t.StudyCode = aps.StudyCode "
                            . "WHERE article_screen_checklist_t.StudyCode = '$article->StudyCode' AND article_screen_checklist_t.ReviewerID = '$reviewerIDs[$n]'");
                    if ($db->getCount() >= 1) {
                        if ($db->getFirstResult()->IsDisabled == 1) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:black"></span></p>';
                        } else {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></p>';
                        }
                    } else {
                        $db->query("SELECT IsDisabled FROM article_process_stage_t WHERE StudyCode = '$article->StudyCode' AND ReviewerID = '$reviewerIDs[$n]' ");
                        if ($db->getFirstResult()->IsDisabled == 1) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:black"></span></p>';
                        } else {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></p>';
                        }
                    }
                }
                ?>
                </td>
                
                <td style="vertical-align:middle" class="text-center">
                <?php
                //Checklist Incldue
                for ($n = 0; $n < count($reviewerIDs); $n++) {
                    $db->query("SELECT article_screen_checklist_t.DecisionID, aps.IsDisabled, aps.StudyCode "
                            . "FROM article_screen_checklist_t "
                            . "INNER JOIN article_process_stage_t aps ON article_screen_checklist_t.StudyCode = aps.StudyCode "
                            . "WHERE article_screen_checklist_t.StudyCode = '$article->StudyCode' AND article_screen_checklist_t.ReviewerID = '$reviewerIDs[$n]'");
                    if ($db->getCount() >= 1) {
                        if ($db->getFirstResult()->DecisionID == 1 && $db->getFirstResult()->IsDisabled == 1) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:black"></span></p>';
                        } else if ($db->getFirstResult()->DecisionID == 1 && $db->getFirstResult()->IsDisabled == 0) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></p>';
                        } else {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></p>';
                        }
                    } else {
                        $db->query("SELECT IsDisabled FROM article_process_stage_t WHERE StudyCode = '$article->StudyCode' AND ReviewerID = '$reviewerIDs[$n]' ");
                        if ($db->getFirstResult()->IsDisabled == 1) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:black"></span></p>';
                        } else {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></p>';
                        }
                    }
                }
                ?>
                </td>
                
                <td style="vertical-align:middle" class="text-center">
                <?php
                //Checklist Exclude
                for ($n = 0; $n < count($reviewerIDs); $n++) {
                    $db->query("SELECT article_screen_checklist_t.DecisionID, aps.IsDisabled, aps.StudyCode "
                            . "FROM article_screen_checklist_t "
                            . "INNER JOIN article_process_stage_t aps ON article_screen_checklist_t.StudyCode = aps.StudyCode "
                            . "WHERE article_screen_checklist_t.StudyCode = '$article->StudyCode' AND article_screen_checklist_t.ReviewerID = '$reviewerIDs[$n]'");
                    if ($db->getCount() >= 1) {
                        if ($db->getFirstResult()->DecisionID == 2 && $db->getFirstResult()->IsDisabled == 1) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:black"></span></p>';
                        } else if ($db->getFirstResult()->DecisionID == 2 && $db->getFirstResult()->IsDisabled == 0) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></p>';
                        } else {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></p>';
                        }
                    } else {
                        $db->query("SELECT IsDisabled FROM article_process_stage_t WHERE StudyCode = '$article->StudyCode' AND ReviewerID = '$reviewerIDs[$n]' ");
                        if ($db->getFirstResult()->IsDisabled == 1) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:black"></span></p>';
                        } else {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></p>';
                        }
                    }
                }
                ?>
                </td>

                <td style="vertical-align:middle" class="text-center">
                <?php
                //Checklist Flag
                for ($n = 0; $n < count($reviewerIDs); $n++) {
                    $db->query("SELECT article_screen_checklist_t.DecisionID, aps.IsDisabled, aps.StudyCode "
                            . "FROM article_screen_checklist_t "
                            . "INNER JOIN article_process_stage_t aps ON article_screen_checklist_t.StudyCode = aps.StudyCode "
                            . "WHERE article_screen_checklist_t.StudyCode = '$article->StudyCode' AND article_screen_checklist_t.ReviewerID = '$reviewerIDs[$n]'");
                    if ($db->getCount() >= 1) {
                        if ($db->getFirstResult()->DecisionID == 3 && $db->getFirstResult()->IsDisabled == 1) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:black"></span></p>';
                        } else if ($db->getFirstResult()->DecisionID == 3 && $db->getFirstResult()->IsDisabled == 0) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></p>';
                        } else {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></p>';
                        }
                    } else {
                        $db->query("SELECT IsDisabled FROM article_process_stage_t WHERE StudyCode = '$article->StudyCode' AND ReviewerID = '$reviewerIDs[$n]' ");
                        if ($db->getFirstResult()->IsDisabled == 1) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:black"></span></p>';
                        } else {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></p>';
                        }
                    }
                }
                ?>
                </td>

                <td style="vertical-align:middle" class="text-center">
                <?php
                //Data Extraction
                for ($n = 0; $n < count($reviewerIDs); $n++) {
                    $db->query("SELECT dt.StudyCode, aps.IsDisabled, aps.StudyCode "
                            . "FROM data_extraction_t dt "
                            . "INNER JOIN article_process_stage_t aps ON dt.StudyCode = aps.StudyCode "
                            . "WHERE dt.StudyCode = '$article->StudyCode'");
                    if ($db->getCount() >= 1) {
                        if ($db->getFirstResult()->IsDisabled == 1) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:black"></span></p>';
                        } else {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></p>';
                        }
                    } else {
                        $db->query("SELECT IsDisabled FROM article_process_stage_t WHERE StudyCode = '$article->StudyCode' AND ReviewerID = '$reviewerIDs[$n]' ");
                        if ($db->getFirstResult()->IsDisabled == 1) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:black"></span></p>';
                        } else {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></p>';
                        }
                    }
                }
                ?>
                </td>

                <td style="vertical-align:middle" class="text-center">
                <?php
                //QUESTS
                for ($n = 0; $n < count($reviewerIDs); $n++) {
                    $db->query("SELECT qt.StudyCode, aps.IsDisabled, aps.StudyCode "
                            . "FROM quest_appraise_t qt "
                            . "INNER JOIN article_process_stage_t aps ON qt.StudyCode = aps.StudyCode "
                            . "WHERE qt.StudyCode = '$article->StudyCode' AND qt.ReviewerID = '$reviewerIDs[$n]'");
                    if ($db->getCount() >= 1) {
                        if ($db->getFirstResult()->IsDisabled == 1) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:black"></span></p>';
                        } else {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></p>';
                        }
                    } else {
                        $db->query("SELECT IsDisabled FROM article_process_stage_t WHERE StudyCode = '$article->StudyCode' AND ReviewerID = '$reviewerIDs[$n]' ");
                        if ($db->getFirstResult()->IsDisabled == 1) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:black"></span></p>';
                        } else {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></p>';
                        }
                    }
                }
                ?>
                </td>

                <td style="vertical-align:middle" class="text-center">
                <?php
                //Kirkpatrick
                for ($n = 0; $n < count($reviewerIDs); $n++) {
                    $db->query("SELECT kp.StudyCode, aps.IsDisabled, aps.StudyCode "
                            . "FROM kirkpatrick_rating_t kp "
                            . "INNER JOIN article_process_stage_t aps ON kp.StudyCode = aps.StudyCode "
                            . "WHERE kp.StudyCode = '$article->StudyCode' AND kp.ReviewerID = '$reviewerIDs[$n]'");
                    if ($db->getCount() >= 1) {
                        if ($db->getFirstResult()->IsDisabled == 1) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:black"></span></p>';
                        } else {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></p>';
                        }
                    } else {
                        $db->query("SELECT IsDisabled FROM article_process_stage_t WHERE StudyCode = '$article->StudyCode' AND ReviewerID = '$reviewerIDs[$n]' ");
                        if ($db->getFirstResult()->IsDisabled == 1) {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:black"></span></p>';
                        } else {
                            echo '<p class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></p>';
                        }
                    }
                }
                ?>
                </td>
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

    public static function displayDuplicateArticles() {
        $db = Database::getInstance();
        ?>
        <div id="wrapper_duplicate_article_list">
            <div id="duplicate_article_list">
                <h1>Duplicate Articles</h1>
                <?php
                $db->query("SELECT s1.StudyCode, s1.Title, s1.AuthorsNames, s1.Hash, s1.IsAnArticleDisabled, aps.ArticleProcessID, aps.IsDisabled, aps.ReviewerID,
rt1.FirstName, rt1.LastName,
ap.ArticleProcessID, ap.Process
FROM study_inventory_t s1
INNER JOIN article_process_stage_t aps ON s1.StudyCode = aps.StudyCode
INNER JOIN reviewer_t rt1 ON aps.ReviewerID = rt1.ReviewerID
INNER JOIN article_process_t ap ON aps.ArticleProcessID = ap.ArticleProcessID
WHERE exists (SELECT s2.StudyCode, s2.Title, s2.AuthorsNames, s2.Hash, s2.IsAnArticleDisabled, aps.ArticleProcessID, aps.IsDisabled, aps.ReviewerID, rt.FirstName, 
rt.LastName,ap.ArticleProcessID, ap.Process
FROM study_inventory_t s2
INNER JOIN article_process_stage_t aps ON s2.StudyCode = aps.StudyCode 
INNER JOIN reviewer_t rt ON aps.ReviewerID = rt.ReviewerID
INNER JOIN article_process_t ap ON aps.ArticleProcessID = ap.ArticleProcessID
WHERE s2.Hash = s1.Hash GROUP BY s2.Hash HAVING COUNT(*) > 2)");

                if ($db->getCount() == 0) {
                    echo '<h1>No Duplicate Articles</h1>';
                } else {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Study Code</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Author Names</th>
                                <th class="text-center">Hash</th>
                                <th class="text-center">Current Phase</th>
                                <th class="text-center">Phase Name</th>
                                <th class="text-center">Assigned Reviewer</th>
                                <th class="text-center">Disable</th>
                                <th class="text-center">Article</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($db->getResults() as $article) {
                                if ($article->IsDisabled == 1) {
                                    ?>
                                    <tr>
                                        <td class="text-center duplicate"><?php echo $article->StudyCode; ?></td>
                                        <td class="text-center duplicate"><?php echo $article->Title; ?></td>
                                        <td class="text-center duplicate"><?php echo $article->AuthorsNames; ?></td>
                                        <td class="text-center duplicate"><?php echo $article->Hash; ?></td>
                                        <td class="text-center duplicate"><?php echo $article->ArticleProcessID; ?></td>
                                        <td class="text-center duplicate"><?php echo $article->Process; ?></td>
                                        <td class="text-center duplicate"><?php echo $article->FirstName . ' ' . $article->LastName; ?></td>
                                        <td class="text-center"><a href="admin.php?type=undisableArticle&StudyCode=<?php echo $article->StudyCode; ?>&ReviewerID=<?php echo $article->ReviewerID; ?>">UnDisable</a></td>
                                        <td class="text-center"><a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $article->StudyCode; ?>"><span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span></a></td>
                                    </tr>
                                    <?php
                                } else {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $article->StudyCode; ?></td>
                                        <td class="text-center"><?php echo $article->Title; ?></td>
                                        <td class="text-center"><?php echo $article->AuthorsNames; ?></td>
                                        <td class="text-center"><?php echo $article->Hash; ?></td>
                                        <td class="text-center"><?php echo $article->ArticleProcessID; ?></td>
                                        <td class="text-center"><?php echo $article->Process; ?></td>
                                        <td class="text-center"><?php echo $article->FirstName . ' ' . $article->LastName; ?></td>
                                        <td class="text-center"><a href="admin.php?type=disableArticle&StudyCode=<?php echo $article->StudyCode; ?>&ReviewerID=<?php echo $article->ReviewerID; ?>">Disable</a></td>
                                        <td class="text-center"><a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $article->StudyCode; ?>"><span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span></a></td>
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
