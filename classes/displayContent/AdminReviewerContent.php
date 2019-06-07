<?php
require_once 'classes/Admin.php';
require_once 'classes/utility/Database.php';
require_once 'classes/forms/FormHelper.php';

class AdminReviewerContent {

    public static function displayAssignReviewerForm($studyCode) {
        ?>
        <div id="wrapper_assign_reviewer">
            <div id="assign_reviewer">
                <h1>Assign Reviewer</h1>
                <form method="post" action="">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Code</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Reviewer To Assign</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center"><?php echo $studyCode; ?></td>

                                <?php
                                $db = Database::getInstance();
                                $db->select('study_inventory_t', array('StudyCode', '=', $studyCode));
                                ?>
                                <td class="text-center"><?php echo $db->getFirstResult()->Title; ?></td>

                                <td class="text-center">
                                    <?php
                                    $admin = new Admin();
                                    $ids = $admin->getReviewersIDsNotAssignedToStudyCode($studyCode);
                                    $names = $admin->getReviewersNamesNotAssignedToStudyCode($studyCode);
                                    ?>

                                    <select name="reviewerToAssign" id="reviewerToAssign" required="required">
                                        <option value="">Name</option>
                                        <?php
                                        for ($i = 0; $i < count($ids); $i++) {
                                            echo '<option value="' . $ids[$i] . '">' .
                                            $names[$i] . '</option>';
                                        }
                                        ?>
                                </td>
                        <input name="StudyCode" type="hidden" value="<?php echo $studyCode; ?>" />
                </form>
                </tr>
                </tbody>
                </table>

                <div class="submit_button">
                    <p><input name="assignReviewer" type="submit" value="Submit" /><p>
                </div>

                <p class="gradient"></p>
            </div>
        </div>
        <?php
    }

    public static function displayRegisterReviewerForm() {
        ?>
        <div id="wrapper_register_reviewer">
            <div id="register_reviewer">
                <h1>Register Reviewer</h1>

                <form class="form-horizontal" method="post" action="">
                    <div class="form-group">
                        <div class="col-sm-12"><input type="text" class="form-control" id="FirstName" name="FirstName" placeholder="FIRST NAME" required="required"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12"><input type="text" class="form-control" id="LastName" name="LastName" placeholder="LAST NAME" required="required"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12"><input type="email" class="form-control" id="Email" name="Email" placeholder="EMAIL" required="required"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12"><input type="text" class="form-control" id="Password" email="Password" placeholder="PASSWORD: Xavier01" disabled></div>
                    </div>
                    <div id="teamLeft">  
                        <div class="form-group">
                            <div class="col-sm-2">
                                <label for="team" class="col-sm-2 control-label">Team</label>
                                <?php FormHelper::displayComboBoxSelection('code_t', 'Code', 'Code', 'Team'); ?>
                            </div>
                        </div>
                    </div>
                    <div id="teamAdminRight"> 
                        <div class="form-group">
                            <div class="col-sm-2">
                                <label for="teamAdmin" class="col-sm-4 control-label">Admin</label>
                                <?php FormHelper::displayComboBoxSelection('code_t', 'Code', 'Code', 'TeamAdmin'); ?>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="submit_button">
                        <p><input name="registerReviewer" type="submit" value="Register" /></p>
                    </div>
                </form>
                <p class="gradient"></p>
            </div>
        </div>
        <?php
    }

    public static function displayReviewerArticleList($reviewerID) {
        $db = Database::getInstance();
        ?>
        <div id="wrapper_reviewer_article_list">
            <div id="reviewer_article_list">
                <h1>Articles</h1>

                <?php
                $db->select('article_process_stage_t', array('ReviewerID', '=', $reviewerID));
                if ($db->getCount() <= 0) {
                    echo '<h1>NO Articles Assigned<h1>';
                } else {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center"></th>
                                <th class="text-center">Study Code</th>
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
                     $count = 0;
            foreach ($db->getResults() as $value) {
                $code = $value->StudyCode;
                $phase = $value->ArticleProcessID;
                $count++;

                if ($count % 2 != 0) {
                    ?>
                    <tr class="danger">
                      <?php
                } else {
                    ?>
                    <tr>
                        <?php
                }
                ?>
                <td class="text-center"><?php echo $count; ?></td>
                <td class="text-center"><a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $code; ?> "><?php echo $code; ?></a></td>
<?php
                //Checklist         
                $db->query("SELECT DecisionID FROM article_screen_checklist_t WHERE StudyCode = '$code' AND ReviewerID = '$reviewerID'");
                if ($db->getCount() >= 1) {
                    ?>
                    <td class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></td>
                    <?php
                } else {
                    ?>
                    <td class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></td>
                    <?php
                }

                //Include
                $db->query("SELECT DecisionID FROM article_screen_checklist_t WHERE StudyCode = '$code' AND ReviewerID = '$reviewerID'");
                if ($db->getCount() >= 1) {

                    foreach ($db->getResults() as $value) {
                        $decision = $value->DecisionID;
                    }

                    if ($decision == 1) {
                        ?>
                       <td class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></td>
                       <?php
                    } else {
                        ?>
                        <td class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></td>
                        <?php
                    }

                    //Exclude
                    if ($decision == 2) {
                        ?>
                        <td class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></td>
                        <?php
                    } else {
                        ?>
                        <td class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></td>
                        <?php
                    }

                    //flag
                    if ($decision == 3) {
                        ?>
                        <td class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></td>
                        <?php
                    } else {
                        ?>
                        <td class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></td>
                        <?php
                    }
                } else {
                    ?>
                    <td class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></td>
                    <td class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></td>
                    <td class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></td>
                    <?php
                }

                //Data Extraction
                if ($phase == 3) {
                    ?>
                    <td class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></td>
                    <?php
                } else {
                    ?>
                    <td class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></td>
                    <?php
                }

                //QUESTS
                if ($phase == 4) {
                    ?>
                    <td class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></td>
                    <?php
                } else {
                    ?>
                    <td class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></td>
                    <?php
                }

                //KirkPatrick
                if ($phase == 5) {
                    ?>
                    <td class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></td>
                    <?php
                } else {
                    ?>
                    <td class="text-center"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></td>
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

    public static function displayReviewerList() {
        $db = Database::getInstance();
        ?>
        <div id="wrapper_user_list">
            <div id="user_list">
                <h1>Reviewers</h1>
                <p>
                <h3><a href="admin.php?type=registerReviewer"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Register NEW Reviewer</a>
                    <?php echo str_repeat('&nbsp;', 50); ?>
                    <a href="admin.php?type=reviewerTeamTable"><span class="glyphicon glyphicon-superscript" aria-hidden="true"></span> Full TEAM Table</a></h3>
                <p>
                <table class="table table-bordered">
                    <thead>
                        <tr class="success">
                            <th class="text-center">Name</th>
                            <th class="text-center">Update</th>
                            <th class="text-center">Disable</th>
                            <th class="text-center">Articles Assigned</th>
                            <th class="text-center">Assigned Teams</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $db->query("SELECT ReviewerID, FirstName, LastName, IsDisabled FROM reviewer_t");
                        foreach ($db->getResults() as $user) {
                            ?>
                            <tr>
                                <td style="vertical-align:middle" class="text-center"><?php echo $user->FirstName . ' ' . $user->LastName; ?></td>
                                <td style="vertical-align:middle" class="text-center">
                                    <a href="admin.php?type=updateReviewer&reviewerID=<?php echo $user->ReviewerID; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                </td>
                                <td style="vertical-align:middle" class="text-center">

                                    <?php
                                    if ($user->IsDisabled == 0) {
                                        echo '<a href="admin.php?type=disableReviewer&reviewerID=' . $user->ReviewerID . '">'
                                        . '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> Disable</a>';
                                    } else {
                                        echo '<a href="admin.php?type=undisableReviewer&reviewerID=' . $user->ReviewerID . '">'
                                        . '<span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> UnDisable</a>';
                                    }
                                    ?>
                                </td>
                                <td style="vertical-align:middle" class="text-center">
                                    <a href="admin.php?type=reviewerArticleList&reviewerID=<?php echo $user->ReviewerID; ?>"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span></a>
                                </td>
                                <td style="vertical-align:middle" class="text-center">
                                    <a href="admin.php?type=reviewerTeamList&reviewerID=<?php echo $user->ReviewerID; ?>"><span class="glyphicon glyphicon-superscript" aria-hidden="true"></span></a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

    public static function displayReviewerTeamList($reviewerID) {
        $admin = new Admin();
        ?>
        <div id="wrapper_reviewer_team_list">
            <div id="reviewer_team_list">
                <h1><?php echo $admin->getData('FirstName', $reviewerID) . ' ' . htmlspecialchars("'s", ENT_QUOTES) . ' Teams'; ?></h1>

                <?php
                $reviewerCodes = $admin->getAssignedCodesForReviewer($reviewerID, 'team_t');
                ?>
                <table class="table table-bordered">
                    <thead>
                        <tr class="success">
                            <th class="text-center" colspan="<?php echo count($reviewerCodes); ?>">Team</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            for ($i = 0; $i < count($reviewerCodes); $i++) {
                                echo '<td class="text-center">' . $reviewerCodes[$i] . '</td>';
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
                <br/>

                <?php
                $adminCodes = $admin->getAssignedCodesForAdmin($reviewerID, 'team_admin_t');
                ?>
                <table class="table table-bordered">
                    <thead>
                        <tr class="success">
                            <th class="text-center" colspan="<?php echo count($adminCodes); ?>"> Admin Team</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            if (count($adminCodes) == 0) {
                                echo '<td class="text-center">Not An Admin</td>';
                            } else {
                                for ($i = 0; $i < count($adminCodes); $i++) {
                                    echo '<td class="text-center">' . $adminCodes[$i] . '</td>';
                                }
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
                <p class="gradient"></p>
            </div>
        </div>
        <?php
    }

    public static function displayTeamTable() {
        $db = Database::getInstance();
        ?>
        <div id="wrapper_reviewer_team_table">
            <div id="reviewer_team_table">
                <h1>Team Table</h1>

                <table class="table table-bordered">
                    <thead>
                        <tr class="success">
                            <th class="text-center">Name</th>
                            <?php
                            $db->query("SELECT Code FROM code_t");
                            foreach ($db->getResults() as $code) {
                                echo '<th class="text-center">' . $code->Code . '</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $db->query("SELECT ReviewerID, FirstName, LastName FROM reviewer_t");

                        foreach ($db->getResults() as $user) {
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $user->FirstName . ' ' . $user->LastName; ?> </td>

                                <?php
                                $admin = new Admin();
                                $adminCodes = $admin->getAssignedCodesForAdmin($user->ReviewerID, 'team_admin_t');
                                $reviewerCodes = $admin->getAssignedCodesForReviewer($user->ReviewerID, 'team_t');

                                $db->query("SELECT Code FROM code_t");
                                foreach ($db->getResults() as $code) {
                                    if (in_array($code->Code, $adminCodes) && in_array($code->Code, $reviewerCodes)) {
                                        echo '<td class="text-center"><span class="glyphicon glyphicon-user" aria-hidden="true" style="color:blue"></span></td>';
                                    } else if (in_array($code->Code, $reviewerCodes) && !(in_array($code->Code, $adminCodes))) {
                                        echo '<td class="text-center"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></td>';
                                    } else {
                                        echo '<td class="text-center"></td>';
                                    }
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

    public static function displayUpdateReviewerForm($reviewerID) {
        $admin = new Admin();
        ?>
        <div id="wrapper_update_reviewer">
            <div id="update_reviewer">
                <h1>Update Reviewer</h1>


                <form class="form-horizontal" method="post" action="">
                    <div class="form-group">
                        <div class="col-sm-12"><input type="text" class="form-control" id="FirstName" name="FirstName" required="required" value="<?php echo $admin->getData('FirstName', $reviewerID); ?>"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12"><input type="text" class="form-control" id="LastName" name="LastName" required="required" value="<?php echo $admin->getData('LastName', $reviewerID); ?>"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12"><input type="email" class="form-control" id="Email" name="Email" required="required" value="<?php echo $admin->getData('Email', $reviewerID); ?>"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12"><input type="text" class="form-control" id="Password" email="Password" placeholder="PASSWORD" disabled></div>
                    </div>

                    <label>Disable</label>
                    <div class="radio-inline"><label><input type="radio" name="Disable" value="1" <?php echo ($admin->getData('IsDisabled', $reviewerID) === '1') ? 'checked' : ''; ?> required="required">Yes</label></div>
                    <div class="radio-inline"><label><input type="radio" name="Disable" value="0" <?php echo ($admin->getData('IsDisabled', $reviewerID) === '0') ? 'checked' : ''; ?>>No</label></div>

                    <p></p>
                    <div id="reviewerUpdateTeam">
                        <label for="team" class="col-sm-6 control-label">Team</label>
                        <?php
                         $listTeam = $admin->getAssignedCodesForReviewer($reviewerID, 'team_t');
                        FormHelper::displayComboBoxSelection('code_t', 'Code', 'Code', 'Team', $listTeam);
                        ?>
                           
                    </div>
                     
                         <label for="teamAdmin" class="col-sm-6 control-label">Team Admin</label>
                        <?php
                           $listAdminTeam = $admin->getAssignedCodesForReviewer($reviewerID, 'team_admin_t');
                        FormHelper::displayComboBoxSelection('code_t', 'Code', 'Code', 'Admin', $listAdminTeam);
                        ?>
                    <br/>
                    <div class="submit_button">
                        <p><input name="updateReviewer" type="submit" value="Update" /><p>
                    </div>

                </form>
                <p class="gradient"></p>
            </div>
        </div>
        <?php
    }

}
