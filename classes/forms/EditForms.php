<?php
require_once 'classes/ChecklistScreen.php';
require_once 'classes/utility/Database.php';
require_once 'classes/DataExtraction.php';
require_once 'classes/forms/FormHelper.php';
require_once 'classes/KirkpatrickRating.php';
require_once 'classes/StudyInventory.php';
require_once 'classes/QuestsAppraisal.php';

class EditForms {

    const NON_APPLICABLE = "N/A";
    const DUMMY_FIELD = "DummyValue";
    const YEAR_INCLUSIVE_BEGIN = 2000;
    const YEAR_INCLUSIVE_END = 2015;

    public static function displayDataExtraction($studyCode) {
        $extraction = new DataExtraction();
        $list = $extraction->getEditInfo($studyCode);
        ?>
        <div id="wrapper_edit_form">
            <progress id="progressBar" value="0" max="100"></progress>
            <div id="edit_form">
                <h1 id="phaseName">Background/Objective</h1>
                <form id="multiphase" onsubmit="return false">
                    <!-- Background -->
                    <div id="background">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="success">
                                    <th class="text-center">Question</th>
                                    <th class="text-center">Response</th>
                                    <th class="text-center">Additional Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Was there is a literature review? *</td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <p><input type="radio" name="isLiteratureReview" value="Yes" <?php echo ($list['IsLiteratureReview'] === 'Yes') ? 'checked' : ''; ?>>Yes</p>
                                        <p><input type="radio" name="isLiteratureReview" value="No" <?php echo ($list['IsLiteratureReview'] === 'No') ? 'checked' : ''; ?> >No <?php echo str_repeat('&nbsp;', 2); ?></p>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Is there a theory or theoretical framework for the study? *</td>
                                    <td class="text-center">
                                        <input type="radio" name="isTheoryForStudy" value="Yes" <?php echo ($list['IsTheoryForStudy'] === 'Yes') ? 'checked' : ''; ?>>Yes
                                        <p><input type="radio" name="isTheoryForStudy" value="No" <?php echo ($list['IsTheoryForStudy'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 2); ?></p></td>

                                    <?php
                                    if ($list['TheoryStudyYesDescrpt'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle"><div class="col-sm-14"><input type="text" class="form-control" id="isTheoryForStudyDescribe"
                                                                                                        name="isTheoryForStudyDescribe" placeholder="YES, describe" maxlength="500"/></div>
                                                <?php
                                            } else {
                                                ?>
                                        <td style="vertical-align:middle"><div class="col-sm-14"><input type="text" class="form-control" id="isTheoryForStudyDescribe"  maxlength="500"
                                                                                                        name="isTheoryForStudyDescribe" value="<?php echo $list['TheoryStudyYesDescrpt']; ?>"/></div>
                                                <?php
                                            }
                                            ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Is there a learning outcome for the study? *</td>
                                    <td class="text-center">
                                        <p><input type="radio" name="isLearningOutcomeForStudy" value="Yes" <?php echo ($list['IsLearningOutcomeForStudy'] === 'Yes') ? 'checked' : ''; ?>>Yes</p>
                                        <p><input type="radio" name="isLearningOutcomeForStudy" value="No" <?php echo ($list['IsLearningOutcomeForStudy'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 2); ?> </p>
                                    </td>

                                    <?php
                                    if ($list['LearningOutcomeForStudyYesDescrpt'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle"><div class="col-sm-14"><input type="text" class="form-control"  maxlength="500"
                                                                                                        id="learningOutcomeForStudyDescribe" name="learningOutcomeForStudyYesDescribe"
                                                                                                        placeholder="YES, describe" ></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle"><div class="col-sm-14"><input type="text" class="form-control"  maxlength="500"
                                                                                                        id="learningOutcomeForStudyDescribe" name="learningOutcomeForStudyYesDescribe"
                                                                                                        value="<?php echo str_replace("'", "", $list['LearningOutcomeForStudyYesDescrpt']); ?>" /></div></td>
                                            <?php
                                        }
                                        ?>

                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Is there a clear, well-defined objective? *</td>
                                    <td class="text-center">
                                        <p><input type="radio" name="isClearWellDefined" value="Yes" <?php echo ($list['IsCorrespondenceRequired'] === 'Yes') ? 'checked' : ''; ?>>Yes</p>
                                        <p><input type="radio" name="isClearWellDefined" value="No" <?php echo ($list['IsCorrespondenceRequired'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 2); ?></p></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">What levels of learner outcomes are included? *</td>
                                    <td class="text-center">
                                        <?php
                                        $outcomeIDs = FormHelper::getDropDownListIDs($studyCode, $list['IsLearnerOutcomeExists'], 'data_extract_learner_outcome_t', 'learner_outcome_t', 'OutcomeID');
                                        FormHelper::displayComboBoxSelection('learner_outcome_t', 'OutcomeID', 'Outcome', 'outcome', $outcomeIDs);
                                        ?>
                                    </td>
                                    <?php
                                    if ($list['LearningOutcomeForStudyYesDescrpt'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle"><div class="col-sm-18"><input type="text" class="form-control" id="levelsLearnerOutcomeOther" maxlength="500"
                                                                                                        name="levelsLearnerOutcomeOther" placeholder="OTHER, specify" /></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle"><div class="col-sm-18"><input type="text" class="form-control" id="levelsLearnerOutcomeOther" maxlength="500"
                                                                                                        name="levelsLearnerOutcomeOther" value="<?php echo str_replace("'", "", $list['LearningOutcomeForStudyYesDescrpt']); ?>" /></div></td>
                                            <?php
                                        }
                                        ?>
                                </tr>
                            </tbody>
                        </table>
                        <button class="continueBtn" onclick="processBackground()">Continue</button>
                        <p class="gradient"></p>
                    </div>

                    <!--  Methods -->
                    <div id="methods">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="success">
                                    <th class="text-center">Question</th>
                                    <th class="text-center">Response</th>
                                    <th class="text-center">Additional Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Is the study design reported? *</td>
                                    <td class="text-center">

                                        <p><input type="radio" name="isStudyDesignReported" value="Yes" <?php echo ($list['IsStudyDesignReported'] === 'Yes') ? 'checked' : ''; ?>>Yes<?php echo str_repeat('&nbsp;', 6); ?></p>
                                        <p><input type="radio" name="isStudyDesignReported" value="No" <?php echo ($list['IsStudyDesignReported'] === 'No') ? 'checked' : ''; ?>>No<?php echo str_repeat('&nbsp;', 8); ?></p>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Study Design</td>
                                    <td class="text-center">
                                        <?php
                                        $studyDesignIDs = FormHelper::getDropDownListIDs($studyCode, $list['IsStudyDesignExists'], 'data_extract_study_design_t', 'study_design_t', 'StudyDesignID');
                                        FormHelper::displayComboBoxSelection('study_design_t', 'StudyDesignID', 'StudyDesign', 'studyDesign', $studyDesignIDs);
                                        ?>
                                    </td>
                                    <?php
                                    if ($list['StudyDesignOtherDescrpt'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle"><div class="col-sm-18"><input type="text" class="form-control" id="studyDesignOtherDescribe" maxlength="500"
                                                                                                        name="studyDesignOtherDescribe" placeholder="OTHER, describe" ></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle"><div class="col-sm-18"><input type="text" class="form-control" id="studyDesignOtherDescribe" maxlength="500"
                                                                                                        name="studyDesignOtherDescribe" value="<?php str_replace("'", "", $list['StudyDesignOtherDescrpt']); ?>" /></div></td>
                                            <?php
                                        }
                                        ?>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Is the design appropriate to answer the research question? *</td>
                                    <td class="text-center">
                                        <p><input type="radio" name="isDesignAppropriate" value="Yes" <?php echo ($list['IsDesignAppropriate'] === 'Yes') ? 'checked' : ''; ?>>Yes <?php echo str_repeat('&nbsp;', 7); ?></p>
                                        <p><input type="radio" name="isDesignAppropriate" value="No" <?php echo ($list['IsDesignAppropriate'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 9); ?></p>
                                        <p><input type="radio" name="isDesignAppropriate" value="Not Sure" <?php echo ($list['IsDesignAppropriate'] === 'Not Sure') ? 'checked' : ''; ?>>Not Sure</p>
                                    </td>
                                    <?php
                                    if ($list['IsDesignAppropriateUnsureDescrpt'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-18"><input type="text" class="form-control" id="isDesignAppropriateDescription"
                                                                                                                            name="isDesignAppropriateDescription" placeholder="NOT SURE, specify" maxlength="500"></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-18"><input type="text" class="form-control" id="isDesignAppropriateDescription" maxlength="500"
                                                                                                                            name="isDesignAppropriateDescription" value="<?php echo str_replace("'", "", $list['IsDesignAppropriateUnsureDescrpt']); ?>" /></div></td>
                                            <?php
                                        }
                                        ?>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">What instruments were used?</td>
                                    <?php
                                    if ($list['InstrumentsUsed'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td class="text-center"><div class="col-sm-20"><input type="text" class="form-control" id="instrumentsUsed"
                                                                                              name="instrumentsUsed"></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td class="text-center"><div class="col-sm-20"><input type="text" class="form-control" id="instrumentsUsed"
                                                                                                    name="instrumentsUsed" value ="<?php echo str_replace("'", "", $list['InstrumentsUsed']); ?>"/></div></td>
                                            <?php
                                        }
                                        ?>
                                    <td class="text-center"></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">What is the total study duration?</td>
                                    <?php
                                    if ($list['TotalStudyDuration'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-20"><input type="text" class="form-control" id="totalStudyDurationTime" maxlength="500"
                                                                                                                            name="totalStudyDurationTime" placeholder="TIME"></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-20"><input type="text" class="form-control" id="totalStudyDurationTime" maxlength="500"
                                                                                                                            name="totalStudyDurationTime" value="<?php echo str_replace("'", "", $list['TotalStudyDuration']); ?>"/></div></td>
                                            <?php
                                        }
                                        ?>
                                    <td class="text-center"><p>                       
                                            <input type="checkbox" name="totalStudyDurationOption" value="Not Specified" <?php ($list['TotalStudyDurationSpecification'] === 'Not Specified') ? 'checked' : ''; ?>> Not Specified
                                        </p></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Is there bias in the methods? *</td>
                                    <td class="text-center"><p>
                                            <input type="radio" name="isBiasMethods" value="Yes" <?php echo ($list['IsMethodsBias'] === 'Yes') ? 'checked' : ''; ?>>Yes <?php echo str_repeat('&nbsp;', 8); ?></p>
                                        <p><input type="radio" name="isBiasMethods" value="No" <?php echo ($list['IsMethodsBias'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 10); ?></p>
                                        <p><input type="radio" name="isBiasMethods" value="Not Sure" <?php echo ($list['IsMethodsBias'] === 'Not Sure') ? 'checked' : ''; ?>>Not Sure <?php echo str_repeat('&nbsp;', 1); ?></p>
                                    </td>
                                    <?php
                                    if ($list['MethodsBiasYesDescrpt'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><p><div class="col-sm-20"><input type="text" class="form-control" id="isBiasMethodsYesDescribe" maxlength="500"
                                                                                                                               name="isBiasMethodsYesDescribe" placeholder="YES, describe"/></div>
                                                <?php
                                            } else {
                                                ?>
                                        <td style="vertical-align:middle" class="text-center"><p><div class="col-sm-20"><input type="text" class="form-control" id="isBiasMethodsYesDescribe" maxlength="500"
                                                                                                                               name="isBiasMethodsYesDescribe" value="<?php echo str_replace("'", "", $list['MethodsBiasYesDescrpt']); ?>"/></p></div>
                                                <?php
                                            }

                                            if ($list['MethodsBiasUnsureDescrpt']) {
                                                ?>
                                            <p><div class="col-sm-20"><input type="text" class="form-control" maxlength="500"
                                                                             id="isBiasMethodsUnsure" name="isBiasMethodsUnsure" placeholder="NOT SURE, specify"/></div></p></td>
                                            <?php
                                        } else {
                                            ?>
                                <p><div class="col-sm-20"><input type="text" class="form-control" maxlength="500"
                                                                 id="isBiasMethodsUnsure" name="isBiasMethodsUnsure" value="<?php echo str_replace("'", "", $list['MethodsBiasUnsureDescrpt']); ?>"/></div></p></td>
                                    <?php
                                }
                                ?>
                            </tr>
                            </tbody>
                        </table>
                        <button class="backBtn" onclick="processBackToBackground()">Back to Background</button>
                        <button class="proceedBtn" onclick="processMethods()">Go to Participants</button>
                        <p class="gradient"></p>
                    </div>

                    <!-- Participants -->
                    <div id="participants">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="success">
                                    <th class="text-center">Question</th>
                                    <th class="text-center">Response</th>
                                    <th class="text-center">Additional Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Total Number of participants</td>
                                    <?php
                                    if ($list['TotalParticipants'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-20"><input type="text" class="form-control" id="totalParticipants" maxlength="500"
                                                                                                                            name="totalParticipants"></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-20"><input type="text" class="form-control" id="totalParticipants" maxlength="500"
                                                                                                                            name="totalParticipants" value="<?php echo str_replace("'", "", $list['TotalParticipants']); ?>"/></div></td>
                                            <?php
                                        }
                                        ?>

                                    <td class="text-center"><p>
                                            <input type="checkbox" name="particiantsTotalSpecificiation" value="Not Specified" <?php echo ($list['TotalParticipantsSpecification'] === 'Not Specified') ? 'checked' : ''; ?>> Not Specified
                                        </p></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Type of participants</td>
                                    <td class="text-center">
                                        <?php
                                        $participantIDs = FormHelper::getDropDownListIDs($studyCode, $list['IsParticipantExists'], 'data_extract_participant_t', 'participant_t', 'ParticipantID');
                                        FormHelper::displayComboBoxSelection('participant_t', 'ParticipantID', 'Participant', 'participantTypes', $participantIDs);
                                        ?>
                                    </td>
                                    <?php
                                    if ($list['ParticipantStudentAcademicLevel'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                               id="studentAcademicLevel" name="studentAcademicLevel" placeholder="STUDENT, academic level"></div></p>
                                                <?php
                                            } else {
                                                ?>
                                        <td style="vertical-align:middle" class="text-center"><p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                               id="studentAcademicLevel" name="studentAcademicLevel" value="<?php echo str_replace("'", "", $list['ParticipantStudentAcademicLevel']); ?>"/></div></p>
                                                <?php
                                            }

                                            if ($list['ParticipantOtherDescrpt'] == self::DUMMY_FIELD) {
                                                ?>
                                            <p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                             id="participantOther" name="participantOther" placeholder="OTHER, specify"></div></p></td>
                                            <?php
                                        } else {
                                            ?>
                                <p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                 id="participantOther" name="participantOther" value="<?php echo str_replace("'", "", $list['ParticipantOtherDescrpt']); ?>"/></div></p></td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Age</td>
                                <?php
                                if ($list['AgeDemographic'] == self::DUMMY_FIELD) {
                                    ?>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="age"
                                                                                                                    maxlength="500"    name="age"></div></td>
                                        <?php
                                    } else {
                                        ?>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="age"
                                                                                                                      maxlength="500"  name="age" value="<?php echo str_replace("'", "", $list['AgeDemographic']); ?>"/></div></td>
                                        <?php
                                    }
                                    ?>
                                <td class="text-center"><p>
                                        <input type="checkbox" name="ageSpecificiation" value="Not Specified" <?php echo ($list['AgeDemographicSpecification'] === 'Not Specified') ? 'checked' : ''; ?>> Not Specified</p>
                                </td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">Gender</td>
                                <td class="text-center">
                                    <?php
                                    $genderIDs = FormHelper::getDropDownListIDs($studyCode, $list['IsGenderExists'], 'data_extract_gender_t', 'gender_t', 'GenderID');
                                    FormHelper::displayComboBoxSelection('gender_t', 'GenderID', 'Gender', 'gender', $genderIDs);
                                    ?>
                                </td>
                                <?php
                                if ($list['GenderOtherDescrpt'] == self::DUMMY_FIELD) {
                                    ?>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="genderOther"
                                                                                                                     maxlength="500"   name="genderOther" placeholder="OTHER, specify"/></div></td>
                                        <?php
                                    } else {
                                        ?>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="genderOther"
                                                                                                                  maxlength="500"      name="genderOther" value="<?php echo str_replace("'", "", $list['GenderOtherDescrpt']); ?>"/></div></td>
                                        <?php
                                    }
                                    ?>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Professions</td>
                                <td class="text-center">
                                    <?php
                                    $professionIDs = FormHelper::getDropDownListIDs($studyCode, $list['IsProfessionExists'], 'data_extract_profession_t', 'profession_t', 'ProfessionID');
                                    FormHelper::displayComboBoxSelection('profession_t', 'ProfessionID', 'Profession', 'profession', $professionIDs);
                                    ?>
                                </td>

                                <?php
                                if ($list['ProfessionOtherDescrpt'] == self::DUMMY_FIELD) {
                                    ?>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="professionOther"
                                                                                                                   maxlength="500"     name="professionOther" placeholder="OTHER, specify"></div></td>
                                        <?php
                                    } else {
                                        ?>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="professionOther"
                                                                                                                    maxlength="500"    name="professionOther" value="<?php echo str_replace("'", "", $list['ProfessionOtherDescrpt']); ?>"/></div></td>
                                        <?php
                                    }
                                    ?>

                            </tr>
                            </tbody>
                        </table>
                        <button class="backBtn" onclick="processBackToMethods()">Back to Methods</button>
                        <button class="proceedBtn" onclick="processParticipants()">Go to Interventions</button>
                        <p class="gradient"></p>
                    </div>

                    <!-- Interventions -->
                    <div id="interventions">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="success">
                                    <th class="text-center">Question</th>
                                    <th class="text-center">Response</th>
                                    <th class="text-center">Additional Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Is the educational intervention clearly described? *</td>
                                    <td class="text-center"><p>
                                            <input type="radio" name="isEducationDescribed" value="Yes" <?php echo ($list['IsEduIntervDescriptionClear'] === 'Yes') ? 'checked' : ''; ?>>Yes <?php echo str_repeat('&nbsp;', 6); ?></p>
                                        <p><input type="radio" name="isEducationDescribed" value="No" <?php echo ($list['IsEduIntervDescriptionClear'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 8); ?></p>
                                        <p><input type="radio" name="isEducationDescribed" value="Not Sure" <?php echo ($list['IsEduIntervDescriptionClear'] === 'Not Sure') ? 'checked' : ''; ?>>Not Sure</p>
                                    </td>
                                    <?php
                                    if ($list['EduIntervDescriptionUnsureDescrpt'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="educationNotSureDescribe"
                                                                                                                         maxlength="500"   name="educationNotSureDescribe" placeholder="UNSURE, specify"></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="educationNotSureDescribe" maxlength="500"
                                                                                                                            name="educationNotSureDescribe" value="<?php echo str_replace("'", "", $list['EduIntervDescriptionUnsureDescrpt']); ?>"/></div></td>
                                            <?php
                                        }
                                        ?>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Number of intervention groups</td>
                                    <?php
                                    if ($list['NumberIntervGroups'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="numberInterventionGrp" name="numberInterventionGrp"></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="numberInterventionGrp" name="numberInterventionGrp" value="<?php echo str_replace("'", "", $list['NumberIntervGroups']); ?>"/></div></td>
                                            <?php
                                        }
                                        ?>
                                    <td class="text-center"><p>
                                            <input type="checkbox" name="numberInterSpecificiation" value="Not Specified" <?php echo ($list['NumberIntervGroupsSpecification'] === 'Not Specified') ? 'checked' : ''; ?>> Not Specified
                                        </p></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Number of participants allocated to each intervention group</td>
                                    <?php
                                    if ($list['NumberParticipantsInIntervGroups'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="participantNumberInterventionGrp" name="participantNumberInterventionGrp"/></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="participantNumberInterventionGrp" name="participantNumberInterventionGrp" value="<?php echo str_replace("'", "", $list['NumberParticipantsInIntervGroups']); ?>"></div></td>
                                            <?php
                                        }
                                        ?>
                                    <td class="text-center"><p>
                                            <input type="checkbox" name="numberParticipantGrpInterSpecificiation" value="Not Specified"  <?php echo ($list['NumberParticipantsInIntervGroupsSpecification'] === 'Not Specified') ? 'checked' : ''; ?>> Not Specified
                                        </p></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Topics covered</td>
                                    <?php
                                    if ($list['TopicsCover'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-12">
                                                <input type="text" class="form-control" id="topicCover" name="topicCover" maxlength="500"/></div></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-12">
                                                <input type="text" class="form-control" id="topicCover" maxlength="500"
                                                       name="topicCover" value="<?php echo str_replace("'", "", $list['TopicsCover']); ?>"></div></td>
                                            <?php
                                        }
                                        ?>
                                    <td style="vertical-align:middle" class="text-center"><p>
                                            <input type="checkbox" name="topicCoverSpecificiation" value="Not Specified" <?php echo ($list['TopicsCoverSpecification'] === 'Not Specified') ? 'checked' : ''; ?>> Not Specified
                                        </p></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Type of intervention/Teaching methods</td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php
                                        $teachingMethodIDs = FormHelper::getDropDownListIDs($studyCode, $list['IsTeachingMethodExists'], 'data_extract_teaching_method_t', 'teaching_method_t', 'TeachingMethodID');
                                        FormHelper::displayComboBoxSelection('teaching_method_t', 'TeachingMethodID', 'TeachingMethod', 'teachingmethod', $teachingMethodIDs);
                                        ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php
                                        if ($list['TeachingMethodCreditHrs'] == self::DUMMY_FIELD) {
                                            ?>
                                            <p><div class="col-sm-12"><input type="text" class="form-control" id="teachingMethodCreditHrs"
                                                                             name="teachingMethodCreditHrs" placeholder="# Credit Hours" maxlength="500"></div></p>
                                                <?php
                                            } else {
                                                ?>
                                            <p><div class="col-sm-12"><input type="text" class="form-control" id="teachingMethodCreditHrs" maxlength="500"
                                                                             name="teachingMethodCreditHrs" value="<?php echo str_replace("'", "", $list['TeachingMethodCreditHrs']); ?>"></div></p>
                                                <?php
                                            }

                                            if ($list['TeachingMethodOtherDescrpt']) {
                                                ?>
                                            <p><div class="col-sm-12"><input type="text" class="form-control" id="teachingMethodOther" maxlength="500"
                                                                             name="teachingMethodOther" placeholder="OTHER, specfiy"/></div></p>
                                                <?php
                                            } else {
                                                ?>
                                            <p><div class="col-sm-12"><input type="text" class="form-control" id="teachingMethodOther" maxlength="500"
                                                                             name="teachingMethodOther" value="<?php echo str_replace("'", "", $list['TeachingMethodOtherDescrpt']); ?>"/></div></p>
                                                <?php
                                            }
                                            ?>
                                    </td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Instructional resources used</td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php
                                        $instructionalRescourceIDs = FormHelper::getDropDownListIDs($studyCode, $list['IsInstructionalRescourceExists'], 'data_extract_instructional_resource_t', 'instructional_resource_t', 'InstructionalResourceID');
                                        FormHelper::displayComboBoxSelection('instructional_resource_t', 'InstructionalResourceID', 'InstructionalResource', 'instructionalResource', $instructionalRescourceIDs);
                                        ?>
                                    </td>
                                    <?php
                                    if ($list['InstructionalRescourceOtherDescrpt'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                               id="instructionalResourceOther" name="instructionalResourceOther" placeholder="OTHER, specfiy"/></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle" class="text-center"><p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                               id="instructionalResourceOther" name="instructionalResourceOther" value="<?php echo str_replace("'", "", $list['InstructionalRescourceOtherDescrpt']); ?>"></div></td>
                                            <?php
                                        }
                                        ?>
                                </tr>
                            </tbody>
                        </table>
                        <button class="backBtn" onclick="processBackToParticipants()">Back to Participants</button>
                        <button class="proceedBtn" onclick="processInterventions()">Go to Assessment</button>
                        <p class="gradient"></p>
                    </div>

                    <!-- Assessment -->
                    <div id="assessment">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="success">
                                    <th class="text-center">Question</th>
                                    <th class="text-center">Response</th>
                                    <th class="text-center">Additional Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Assessment of intervention</td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php
                                        $assessmentIntervIDs = FormHelper::getDropDownListIDs($studyCode, $list['IsAssessmentIntervExists'], 'data_extract_assessment_intervention_t', 'assessment_intervention_t', 'AssessmentInterventionID');
                                        FormHelper::displayComboBoxSelection('assessment_intervention_t', 'AssessmentInterventionID', 'AssessmentIntervention', 'assessmentIntervention', $assessmentIntervIDs);
                                        ?>
                                    </td>
                                    <?php
                                    if ($list['AssessmentIntervOther'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><p><div class="col-sm-10"><input type="text" class="form-control" maxlength="500"
                                                                                                                               id="assessmentInterventionOther" name="assessmentInterventionOther" placeholder="OTHER, specfiy"/></div></td>
                                            <?php
                                        } else {
                                            ?>                                  
                                        <td style="vertical-align:middle" class="text-center"><p><div class="col-sm-10"><input type="text" class="form-control" maxlength="500"
                                                                                                                               id="assessmentInterventionOther" name="assessmentInterventionOther" value="<?php echo str_replace("'", "", $list['AssessmentIntervOther']); ?>"/></div></td>
                                            <?php
                                        }
                                        ?>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Time points collected and reported</td>
                                    <?php
                                    if ($list['TimePointsCollected'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-16"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="timePointsCollected" name="timePointsCollected"/></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-16"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="timePointsCollected" name="timePointsCollected" value="<?php echo str_replace("'", "", $list['TimePointsCollected']); ?>"></div></td>
                                            <?php
                                        }
                                        ?>
                                    <td style="vertical-align:middle" class="text-center">
                                        <input type="checkbox" name="timePointCollectedSpecification" value="Not Specified" <?php echo ($list['TimePointsCollectedSpecification'] === 'Not Specified') ? 'checked' : ''; ?>> Not Specified
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Unit of measurement (if relevant)</td>
                                    <?php
                                    if ($list['UnitOfMeasurement'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-16"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="unitOfMeasurement" name="unitOfMeasurement"/></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-16"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="unitOfMeasurement" name="unitOfMeasurement" value="<?php echo str_replace("'", "", $list['UnitOfMeasurement']); ?>"></div></td>
                                            <?php
                                        }
                                        ?>
                                    <td style="vertical-align:middle" class="text-center">
                                        <input type="checkbox" name="unitOfMesaurementSpecification" value="Not Specified" <?php echo ($list['UnitOfMeasurementSpecification'] === 'Not Specified') ? 'checked' : ''; ?>> Not Specified
                                    </td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">For scales, upper and lower limits and interpretation (if relevant)</td>
                                    <?php
                                    if ($list['ScaleLimitInterpretation'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-16"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="limitsAndInterpretation" name="limitsAndInterpretation"/></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-16"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="limitsAndInterpretation" name="limitsAndInterpretation" value="<?php echo str_replace("'", "", $list['ScaleLimitInterpretation']); ?>"></div></td>
                                            <?php
                                        }
                                        ?>
                                    <td style="vertical-align:middle" class="text-center">
                                        <input type="checkbox" name="limitSpecification" value="Not Specified" <?php echo ($list['ScaleLimitInterpretationSpecification'] === 'Not Specified') ? 'checked' : ''; ?>> Not Specified
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Evaluation Criteria</td>
                                    <?php
                                    if ($list['EvaluationCriteria'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-16"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="evaluationCriteria" name="evaluationCriteria"></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-16"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="evaluationCriteria" name="evaluationCriteria" value="<?php echo str_replace("'", "", $list['EvaluationCriteria']); ?>"></div></td>
                                            <?php
                                        }
                                        ?>
                                    <td style="vertical-align:middle" class="text-center">
                                        <input type="checkbox" name="elevuationCriteriaSpecification" value="Not Specified" <?php echo ($list['EvaluationCriteriaSpecifciation'] === 'Not Specified') ? 'checked' : ''; ?>> Not Specified
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="backBtn" onclick="processBackToInterventions()">Back to Interventions</button>
                        <button class="proceedBtn" onclick="processAssessment()">Go to Results</button>
                        <p class="gradient"></p>
                    </div>

                    <!-- Result -->
                    <div id="result">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="success">
                                    <th class="text-center">Question</th>
                                    <th class="text-center">Response</th>
                                    <th class="text-center">Additional Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Sample Size</td>
                                    <?php
                                    if ($list['SampleSize'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="sampleSize" name="sampleSize"/></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="sampleSize" name="sampleSize" value="<?php echo str_replace("'", "", $list['SampleSize']); ?>"></div></td>
                                            <?php
                                        }
                                        ?>
                                    <td style="vertical-align:middle" class="text-center">
                                        <input type="checkbox" name="sampleSizeSpecification" value="Not Specified"  <?php echo ($list['SampleSizeSpecification'] === 'Not Specified') ? 'checked' : ''; ?>> Not Specified
                                    </td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Response Rate</td>
                                    <?php
                                    if ($list['ResponseRate'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="responseRate" name="responseRate"></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="responseRate" name="responseRate" value="<?php echo str_replace("'", "", $list['ResponseRate']); ?>"></div></td>
                                            <?php
                                        }
                                        ?>
                                    <td style="vertical-align:middle" class="text-center">
                                        <input type="checkbox" name="responseRateSpecification" value="Not Specified" <?php echo ($list['ResponseRateSpecifcation'] === 'Not Specified') ? 'checked' : ''; ?>> Not Specified
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Missing participants</td>
                                    <?php
                                    if ($list['MissingParticipants'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="missingParticipants" name="missingParticipants"></div></td>
                                            <?php
                                        } else {
                                            ?>
                                        <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                            id="missingParticipants" name="missingParticipants" value="<?php echo str_replace("'", "", $list['MissingParticipants']); ?>"/></div></td>
                                            <?php
                                        }
                                        ?>

                                    <td style="vertical-align:middle" class="text-center">
                                        <input type="checkbox" name="missingParticipantsSpecification" value="Not Specified" <?php echo ($list['MissingParticipantsSpecification'] === 'Not Specified') ? 'checked' : ''; ?>> Not Specified
                                    </td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Summary data for each intervention</td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php
                                        if ($list['SummaryDataMean'] == self::DUMMY_FIELD) {
                                            ?>
                                            <p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                             id="mean" name="mean" placeholder="MEAN"></div></p>
                                                <?php
                                            } else {
                                                ?>
                                            <p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                             id="mean" name="mean" placeholder="MEAN" value="<?php echo str_replace("'", "", $list['SummaryDataMean']); ?>"/></div></p>
                                                <?php
                                            }

                                            if ($list['SummaryDataCI'] == self::DUMMY_FIELD) {
                                                ?>
                                            <p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                             id="ci" name="ci" placeholder="CI"></div></p>
                                                <?php
                                            } else {
                                                ?>
                                            <p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                             id="ci" name="ci" value="<?php echo str_replace("'", "", $list['SummaryDataCI']); ?>"/></div></p>
                                                <?php
                                            }

                                            if ($list['SummaryDataSD'] == self::DUMMY_FIELD) {
                                                ?>
                                            <p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                             id="sd" name="sd" placeholder="SD"/></div></p>
                                                <?php
                                            } else {
                                                ?>
                                            <p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                             id="sd" name="sd" value="<?php str_replace("'", "", $list['SummaryDataSD']); ?>"/></div></p>
                                                <?php
                                            }

                                            if ($list['SummaryDataPValue'] == self::DUMMY_FIELD) {
                                                ?>
                                            <p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                             id="pValue" name="pValue" placeholder="P-Value"></div></p></td>
                                            <?php
                                        } else {
                                            ?>
                                <p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                 id="pValue" name="pValue" value="<?php echo str_replace("'", "", $list['SummaryDataPValue']); ?>"></div></p></td>
                                    <?php
                                }

                                if ($list['SummaryDataOther'] == self::DUMMY_FIELD) {
                                    ?>
                                <td style="vertical-align:middle" class="text-center"><p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                       id="otherSummaryData" name="otherSummaryData" placeholder="OTHER, specify"></div></p></td>
                                    <?php
                                } else {
                                    ?>

                                <td style="vertical-align:middle" class="text-center"><p><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                       id="otherSummaryData" name="otherSummaryData" value="<?php echo str_replace("'", "", $list['SummaryDataOther']); ?>"></div></p></td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Subgroup analyses, if applicable</td>
                                <?php
                                if ($list['SubgroupAnalyses'] == self::DUMMY_FIELD) {
                                    ?>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                        id="subgrpAnalyses" name="subgrpAnalyses" ></div></td>
                                        <?php
                                    } else {
                                        ?>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" maxlength="500"
                                                                                                                        id="subgrpAnalyses" name="subgrpAnalyses" value="<?php str_replace("'", "", $list['SubgroupAnalyses']); ?>"></div></td>
                                        <?php
                                    }
                                    ?>
                                <td style="vertical-align:middle" class="text-center"></td>
                            </tr>
                            </tbody>
                        </table>
                        <button class="backBtn" onclick="processBackToAssessment()">Back to Assessment</button>
                        <button class="proceedBtn" onclick="processResult()">Go to Miscellaneous</button>
                        <p class="gradient"></p>
                    </div>

                    <!-- Miscellaneous -->
                    <div id="miscellaneous">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="success">
                                    <th class="text-center">Question</th>
                                    <th class="text-center">Response</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Key conclusions of authors</td>
                                    <?php
                                    if ($list['AuthorConclusion'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="keyAuthorConclusion" name="keyAuthorConclusion" maxlength="2500"></textarea></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="keyAuthorConclusion" name="keyAuthorConclusion" maxlength="2500"><?php echo $list['AuthorConclusion']; ?></textarea></td>
                                        <?php
                                    }
                                    ?>

                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Limitations of Study</td>
                                    <?php
                                    if ($list['StudyLimitation'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="studyLimiation" name="studyLimiation" maxlength="2500"></textarea></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="studyLimiation" name="studyLimiation" maxlength="2500" ><?php echo $list['StudyLimitation'] ?></textarea></td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Comments from Authors</td>
                                    <?php
                                    if ($list['AuthorComments'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="authorComments" name="authorComments" maxlength="2500"></textarea></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="authorComments" name="authorComments" maxlength="2500"><?php echo $list['AuthorComments']; ?></textarea></td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">References to other relevant studies</td>
                                    <?php
                                    if ($list['ReferenceToStudies'] == self::DUMMY_FIELD) {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="referenceToOthStudies" name="referenceToOthStudies" maxlength="2500"></textarea></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="referenceToOthStudies" name="referenceToOthStudies" maxlength="2500"><?php echo $list['ReferenceToStudies'] ?></textarea></td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Correspondence required *</td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <p><input type="radio" name="correspondenceRequired" value="Yes" <?php echo ($list['IsCorrespondenceRequired'] === 'Yes') ? 'checked' : ''; ?>>Yes</p>
                                        <p><input type="radio" name="correspondenceRequired" value="No" <?php echo ($list['IsCorrespondenceRequired'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 2); ?></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                         <button class="backBtn" onclick="processBackToResults()">Back to Results</button>
                          <button class="submitBtn" onclick="submitForm()">Submit Data</button>
                        <p class="gradient"></p>
                    </div>
                    </tbody>
                    </table>
                </form>
            </div>
        </div>
        <?php
    }

    public static function displayKirkpatrickRating($studyCode, $reviewerID) {
        $rating = new KirkpatrickRating();
        $list = $rating->getInfo($studyCode, $reviewerID);
        ?>
        <div id="wrapper_edit_form">
            <div id="edit_form">
                <h1>Kirkpatrick Rating (<?php echo $studyCode; ?>)</h1>
                <form method="post" action="">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Question</th>
                                <th class="text-center">Response</th>
                                <th class="text-center">Reviewer Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class = "danger">
                                <td style="vertical-align:middle" class="text-center"><b>Level 1</b><p> Learner's reaction to the Educational Intervention</p></td>
                                <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="isLevelOne" value="Yes" <?php echo ($list['IsLevelOne'] === 'Yes') ? 'checked' : ''; ?> required="required">Yes</p>
                                    <p><input type="radio" name="isLevelOne" value="No" <?php echo ($list['IsLevelOne'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="levelOneAComment" name="levelOneAComment" required="required"><?php echo $list['LevelOneAComments']; ?></textarea></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center"><b>Level 2a</b><p> Change of Attitudes/Perceptions</p></td>
                                <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="isLevelTwoA" value="Yes" <?php echo ($list['IsLevelTwoA'] === 'Yes') ? 'checked' : ''; ?> required="required">Yes</p>
                                    <p><input type="radio" name="isLevelTwoA" value="No"  <?php echo ($list['IsLevelTwoA'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="levelTwoAComment" name="levelTwoAComment" required="required"><?php echo $list['LevelTwoAComments']; ?></textarea></td>
                            </tr>
                            <tr class = "danger">
                                <td style="vertical-align:middle" class="text-center"><b>Level 2b</b><p> Change of Knowledge and/or Skills</p></td>
                                <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="isLevelTwoB" value="Yes" <?php echo ($list['IsLevelTwoB'] === 'Yes') ? 'checked' : ''; ?> required="required">Yes</p>
                                    <p><input type="radio" name="isLevelTwoB" value="No" <?php echo ($list['IsLevelTwoB'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="levelTwoBComment" name="levelTwoBComment" required="required"><?php echo $list['LevelTwoBComments']; ?></textarea></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center"><b>Level 3a</b><p> Self-reported Behavioral Change</p></td>
                                <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="isLevelThreeA" value="Yes" <?php echo ($list['IsLevelThreeA'] === 'Yes') ? 'checked' : ''; ?> required="required">Yes</p>
                                    <p><input type="radio" name="isLevelThreeA" value="No" <?php echo ($list['IsLevelThreeA'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="levelThreeAComment" name="levelThreeAComment" required="required"><?php echo $list['LevelThreeAComments']; ?></textarea></td>
                            </tr>
                            <tr class = "danger">
                                <td style="vertical-align:middle" class="text-center"><b>Level 3b</b><p> Observed Behavioral Change</p></td>
                                <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="isLevelThreeB" value="Yes" <?php echo ($list['IsLevelThreeB'] === 'Yes') ? 'checked' : ''; ?> required="required">Yes</p>
                                    <p><input type="radio" name="isLevelThreeB" value="No" <?php echo ($list['IsLevelThreeB'] === 'No') ? 'checked' : ''; ?> >No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="levelThreeBComment" name="levelThreeBComment" required="required"><?php echo $list['LevelThreeBComments']; ?></textarea></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center"><b>Level 4a</b><p> Changes in Professional Practice</p></td>
                                <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="isLevelFourA" value="Yes" <?php echo ($list['IsLevelFourA'] === 'Yes') ? 'checked' : ''; ?> required="required">Yes</p>
                                    <p><input type="radio" name="isLevelFourA" value="No" <?php echo ($list['IsLevelFourA'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="levelFourAComment" name="levelFourAComment" required="required"><?php echo $list['LevelFourAComments']; ?></textarea></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center"><b>Level 4b</b><p> Benefits to Patients</p></td>
                                <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="isLevelFourB" value="Yes" <?php echo ($list['IsLevelFourB'] === 'Yes') ? 'checked' : ''; ?> required="required">Yes</p>
                                    <p><input type="radio" name="isLevelFourB" value="No" <?php echo ($list['IsLevelFourB'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="levelFourBComment" name="levelFourBComment" required="required"><?php echo $list['LevelFourBComments']; ?></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                    <p><input name="kirkpatrick" type="submit" value="Submit" class="editBtn"/><p>
                    <p class="gradient"></p>
                </form>
            </div>
        </div>
        <?php
    }

    public static function displayScreenChecklist($studyCode, $reviewerID) {
        $checklist = new ChecklistScreen();
        $list = $checklist->getEditInfo($studyCode, $reviewerID);
        ?>
        <div id="wrapper_edit">
            <div id="edit_form">
                <h1>Screen CheckList (<?php echo $studyCode; ?>)</h1>
                <form method="post" action="">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th>Question</th>
                                <th>Response</th>
                                <th>Exclusion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="danger">Is the article published between 2000-2015? *</td>
                                <td class= "danger">
                                    <p><input type="radio" name="IsPublishedWithinTimeFrame" value="Yes" <?php echo ($list['IsPublishedWithinTimeFrame'] === 'Yes') ? 'checked' : ''; ?> required>Yes</p>
                                    <p><input type="radio" name="IsPublishedWithinTimeFrame" value="No"  <?php echo ($list['IsPublishedWithinTimeFrame'] === 'No') ? 'checked' : ''; ?> />No</p>
                                    <p><input type="radio" name="IsPublishedWithinTimeFrame" value="Unsure" <?php echo ($list['IsPublishedWithinTimeFrame'] === 'Unsure') ? 'checked' : ''; ?> />Unsure</p>
                                </td>
                                <td class = "success"><p>Include</p><p>Exclude</p><p>Flag</p></td>
                            </tr>
                            <tr>
                                <td>Is the target population students, residents, and providers in
                                    healthcare-related professions providing direct patient contact such as medicine, nursing, pharmacy, psychology, and allied health? *</td>
                                <td>
                                    <p><input type="radio" name="IsPopProvideDirectPatientContact" value="Yes" <?php echo ($list['IsPopProvideDirectPatientContact'] === 'Yes') ? 'checked' : ''; ?> required>Yes</p>
                                    <p><input type="radio" name="IsPopProvideDirectPatientContact" value="No"  <?php echo ($list['IsPopProvideDirectPatientContact'] === 'No') ? 'checked' : ''; ?> />No</p>
                                    <p><input type="radio" name="IsPopProvideDirectPatientContact" value="Unsure"  <?php echo ($list['IsPopProvideDirectPatientContact'] === 'Unsure') ? 'checked' : ''; ?> />Unsure</p>
                                </td>
                                <td class = "success"><p>Include</p><p>Exclude</p><p>Flag</p></td>
                            </tr>
                            <tr>
                                <td class = "danger">Is the publication peer-reviewed? *</td>
                                <td class = "danger">
                                    <p><input type="radio" name="IsPeerReviewed" value="Yes" <?php echo ($list['IsPeerReviewed'] === 'Yes') ? 'checked' : ''; ?> required>Yes</p>
                                    <p><input type="radio" name="IsPeerReviewed" value="No"  <?php echo ($list['IsPeerReviewed'] === 'No') ? 'checked' : ''; ?> />No</p>
                                    <p><input type="radio" name="IsPeerReviewed" value="Unsure"  <?php echo ($list['IsPeerReviewed'] === 'Unsure') ? 'checked' : ''; ?> />Unsure</p>
                                </td>
                                <td class = "success"><p>Include</p><p>Exclude</p><p>Flag</p></td>
                            </tr>
                            <tr>
                                <td>Does the study describe a planned education intervention that 
                                    is designed to lead to a positive change in learners cultural
                                    competence (i.e., cultural competence is an outcome)?</td>
                                <td>
                                    <p><input type="radio" name="IsDescribedPlanEducationIntervention" value="Yes" <?php echo ($list['IsDescribedPlanEducationIntervention'] === 'Yes') ? 'checked' : ''; ?> required>Yes</p>
                                    <p><input type="radio" name="IsDescribedPlanEducationIntervention" value="No"  <?php echo ($list['IsDescribedPlanEducationIntervention'] === 'No') ? 'checked' : ''; ?> />No</p>
                                    <p><input type="radio" name="IsDescribedPlanEducationIntervention" value="Unsure"  <?php echo ($list['IsDescribedPlanEducationIntervention'] === 'Unsure') ? 'checked' : ''; ?> />Unsure</p>
                                </td>
                                <td class = "success"><p>Include</p><p>Exclude</p><p>Flag</p></td>
                            </tr>
                            <tr>
                                <td class="danger">Is the cultural competence topic related to race/ethnicity, origin/ancestry, and/or culture? *</td>
                                <td class="danger">
                                    <p><input type="radio" name="IsCulturalCompetenceTopicOriginRelated" value="Yes" <?php echo ($list['IsCulturalCompetenceTopicOriginRelated'] === 'Yes') ? 'checked' : ''; ?> required>Yes</p>
                                    <p><input type="radio" name="IsCulturalCompetenceTopicOriginRelated" value="No"  <?php echo ($list['IsCulturalCompetenceTopicOriginRelated'] === 'No') ? 'checked' : ''; ?> />No</p>
                                    <p><input type="radio" name="IsCulturalCompetenceTopicOriginRelated" value="Unsure" <?php echo ($list['IsCulturalCompetenceTopicOriginRelated'] === 'Unsure') ? 'checked' : ''; ?> />Unsure</p>
                                </td>
                                <td class = "success"><p>Include</p><p>Exclude</p><p>Flag</p></td>
                            </tr>
                            <tr>
                                <td>Decision *</td>
                                <td>
                                    <p><input type="radio" name="Decision" value="3" <?php echo ($list['DecisionID'] === '3') ? 'checked' : ''; ?> required>Flag</p>
                                    <p><input type="radio" name="Decision" value="1"  <?php echo ($list['DecisionID'] === '1') ? 'checked' : ''; ?> />Include</p>
                                    <p><input type="radio" name="Decision" value="2"  <?php echo ($list['DecisionID'] === '2') ? 'checked' : ''; ?> />Exclude</p>
                                </td>
                                <td><p>ALL YES, Include</p><p>ONE NO, exclude</p><p>ONE UNSURE, Flag</p></td>
                            </tr>
                        </tbody>
                    </table>
                    <p>
                        <textarea name="commentDecision"><?php echo $list['DecisionComments']; ?></textarea>
                    </p>
                    <div class="submit_button">
                        <p><input name="submit" type="submit" value="Submit" /><p>
                    </div>
                </form>
                <p class="gradient"></p>
            </div>
        </div>
        <?php
    }

    public static function displayStudyInventory($studyCode) {
        $studyInventory = new StudyInventory();
        $list = $studyInventory->getEditInfo($studyCode);
        ?>
        <div id="wrapper_edit_form">
            <div id="edit_form">
                <h1>Edit Study Inventory (<?php echo $list['StudyCode']; ?>)</h1>
                <form method="post" action="" enctype="multipart/form-data">

                    <!-- Article Code-->
                    <div class="article_code">
                        <h3>Article Code</h3>
                        <p class="code"><?php echo $list['StudyCode']; ?></p>
                    </div>
                    <br/>

                    <!-- Article Retrieval -->
                    <div class="article_obtention">
                        <h3>Article Retrieval *</h3>
                        <div class="source_retrieved"><b>Source Retrieved By</b> <?php echo $list['SourceRetirevedBy']; ?></div>
                        <p class="search_method"><b>Search Method *</b> 
                            <input type="radio" name="searchMethod" value="1" <?php echo ($list['SearchMethodID'] === '1') ? 'checked' : ''; ?> required="required">Electronic
                            <input type="radio" name="searchMethod" value="2" <?php echo ($list['SearchMethodID'] === '2') ? 'checked' : ''; ?>>Hand
                        </p>
                        <div class="database"><b>Database *</b> 
                            <?php FormHelper::displayDropdownSelection('database_t', 'DatabaseID', 'ResDatabase', 'database', $list['DatabaseID']); ?>
                            </br>
                        </div>
                    </div>

                    <!--  Article Info -->
                    <div class="article_info">
                        <h3>Article Info</h3>
                        <div class="target_professions">
                            <p>Target Professions</p>
                            <?php FormHelper::displayComboBoxSelection('profession_t', 'ProfessionID', 'Profession', 'profession', $list['ProfessionIDs']); ?>
                        </div>

                        <div class="country">
                            <p>Countries</p>
                            <?php FormHelper::displayComboBoxSelection('country_t', 'CountryID', 'Country', 'country', $list['CountryIDs']); ?>
                        </div>

                        <div class="institutions">
                            <p>Institutions</p>
                            <?php FormHelper::displayComboBoxSelection('institution_t', 'InstitutionID', 'Institution', 'institution', $list['InstitutionIDs']); ?>
                        </div>

                        <!--Title, Authors, Other Insititutions, DOI, PUBMED, & URL-->
                        <p class="title"><textarea name="title" placeholder="TITLE" required="required"> <?php echo $list['Title']; ?></textarea></p>
                        <p class="authors"><textarea name="authors" placeholder="FIRST AUTHOR" required="required"> <?php echo $list['AuthorsNames']; ?></textarea></p>
                        <p class="otherInstitutions">
                            <?php
                            if ($list['OtherInstitution'] != self::NON_APPLICABLE) {
                                ?>
                                <textarea name="otherInstitutions"><?php echo $list['OtherInstitution']; ?></textarea>
                                <?php
                            } else {
                                ?>
                                <textarea name="otherInstitutions" placeholder="OTHER INSTITUTIONS"></textarea>
                                <?php
                            }
                            ?>
                        </p>

                        <p class="doi">
                            <?php
                            if ($list['Doi'] != self::NON_APPLICABLE) {
                                ?>
                                <textarea name="doi"><?php echo $list['Doi']; ?></textarea>
                                <?php
                            } else {
                                ?>
                                <textarea name="doi" placeholder="DOI"></textarea>
                                <?php
                            }
                            ?>
                        </p>

                        <p class="pubmed">
                            <?php
                            if ($list['Pubmed'] != self::NON_APPLICABLE) {
                                ?>
                                <textarea name="pubmed"><?php echo $list['Pubmed']; ?></textarea>
                                <?php
                            } else {
                                ?>
                                <textarea name="pubmed" placeholder="PUBMED"></textarea>
                                <?php
                            }
                            ?>
                        </p>

                        <p class="url">  
                            <?php
                            if ($list['Url'] != self::NON_APPLICABLE) {
                                ?>
                                <textarea name="url" placeholder="URL"><?php echo $list['Url']; ?></textarea>
                                <?php
                            } else {
                                ?>
                                <textarea name="url" placeholder="URL"></textarea>
                                <?php
                            }
                            ?>
                        </p>
                    </div>

                    <div class="document_type">Type *
                        <?php FormHelper::displayDropdownSelection('document_type_t', 'DocumentTypeID', 'DocumentType', 'documentType', $list['DocumentType']); ?>
                    </div>

                    <div class="published_month_year">Published *
                        <?php
                        $months = array("January", "Feburary", "March", "April", "May", "June", "July", "August", "September",
                            "October", "November", "December");
                        
                        if (in_array($list['MonthPublished'][0], $months)) {
                            FormHelper::displayDropdownSelection('calendar_months_t', 'CalendarMonth', 'CalendarMonth', 'publishedMonth', $list['MonthPublished']);
                        } else {
                            FormHelper::displayDropdownBoxNotRequired('calendar_months_t', 'CalendarMonthID', 'CalendarMonth', 'publishedMonth', 'Month');
                        }

                        FormHelper::displayDropdownSelection('calendar_years_t', 'CalendarYear', 'CalendarYear', 'publishedYear', $list['YearPublished']);
                        ?>
                    </div>

                    <div class="study_years">Years of Study 
                        <?php
                        if (($list['YearStudyBegan'][0] >= self::YEAR_INCLUSIVE_BEGIN) && ($list['YearStudyBegan'][0] <= self::YEAR_INCLUSIVE_END)) {
                            FormHelper::displayDropdownSelection('calendar_years_t', 'CalendarYear', 'CalendarYear', 'studyYearBegan', $list['YearStudyBegan']);
                        } else {
                            FormHelper::displayDropdownBoxNotRequired('calendar_years_t', 'CalendarYear', 'CalendarYear', 'studyYearBegan', 'Year');
                        }

                        echo ' - ';
                        if (($list['YearStudyEnd'][0] >= self::YEAR_INCLUSIVE_BEGIN) && ($list['YearStudyEnd'][0] <= self::YEAR_INCLUSIVE_END)) {
                            FormHelper::displayDropdownSelection('calendar_years_t', 'CalendarYear', 'CalendarYear', 'studyYearEnd', $list['YearStudyEnd']);
                        } else {
                            FormHelper::displayDropdownBoxNotRequired('calendar_years_t', 'CalendarYear', 'CalendarYear', 'studyYearBegan', 'Year');
                        }
                        ?>
                    </div>

                    <div class="article_abstract">
                        <h3>Original Abstract *</h3>
                        <textarea name="originalAbstract" required="required"> <?php echo $list['OriginalAbstract']; ?></textarea>
                    </div>

                    <div class="article_citation">
                        <h3>Citation *</h3>
                        <textarea name="citation" required="required"> <?php echo $list['Citation']; ?></textarea>
                    </div>

                    <div class="article_relevant_points">
                        <h3>Relevant Points</h3>
                        <?php
                        if ($list['RelevantPointOne'] != self::NON_APPLICABLE) {
                            ?>
                            <textarea name="relPoint1"><?php echo $list['RelevantPointOne']; ?></textarea>
                            <?php
                        } else {
                            ?>
                            <textarea name="relPoint1" placeholder="POINT ONE"></textarea>
                            <?php
                        }

                        if ($list['RelevantPointTwo'] != self::NON_APPLICABLE) {
                            ?>
                            <textarea name="relPoint2"><?php echo $list['RelevantPointTwo']; ?></textarea>
                            <?php
                        } else {
                            ?>
                            <textarea name="relPoint2" placeholder="POINT TWO"></textarea>
                            <?php
                        }

                        if ($list['RelevantPointThree'] != self::NON_APPLICABLE) {
                            ?>
                            <textarea name="relPoint3"><?php echo $list['RelevantPointThree']; ?></textarea>
                            <?php
                        } else {
                            ?>
                            <textarea name="relPoint3" placeholder="POINT THREE"></textarea>
                            <?php
                        }
                        ?>
                    </div>

                    <input type="hidden" name="reviewerID" value="<?php echo $list['ReviewerID']; ?>">
                    <input type="hidden" name="ArticleUrl" value="<?php echo $list['ArticleUrl']; ?>">
                    <div class="submit_button">
                        <p><input name="studyInventory" type="submit" value="Submit" /><p>
                    </div>
                </form>
                <p class="gradient"></p>
            </div>
        </div>
        <?php
    }

    public static function displayQuestsAppraisal($studyCode, $reviewerID) {
        $quests = new QuestsAppraisal();
        $list = $quests->getInfo($studyCode, $reviewerID);
        ?>
        <div id="wrapper_edit">
            <progress id="progressBar" value="50" max="100"></progress>
            <div id="edit_form">
                <h1 id="phaseName">Purpose of Research</h1>
                <form id="multiphase" onsubmit="return false">
                    <!--  Purpose of Research -->
                    <div id="purposeOfResearch">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="success">
                                    <th class="text-center">Question</th>
                                    <th class="text-center">Response</th>
                                    <th class="text-center">Reviewer Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Description</td>
                                    <td style="vertical-align:middle" class="text-center">        
                                        <p><input type="radio" name="isDescription" value="Yes" <?php echo ($list['IsPRDescription'] === 'Yes') ? 'checked' : ''; ?> required="required">Yes</p>
                                        <p><input type="radio" name="isDescription" value="No"  <?php echo ($list['IsPRDescription'] === 'No') ? 'checked' : ''; ?> />No <?php echo str_repeat('&nbsp;', 1); ?></p>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="descriptionComment" name="descriptionComment" required="required"><?php echo $list['PRDescriptionComments']; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Justification</td>
                                    <td class="text-center"><p><input type="radio" name="isJustification" value="Yes" <?php echo ($list['IsPRJustification'] === 'Yes') ? 'checked' : ''; ?> required="required">Yes</p>
                                        <p><input type="radio" name="isJustification" value="No" <?php echo ($list['IsPRJustification'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="justificationComment" name="justificationComment" required="required"><?php echo $list['PRJustificationComments']; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Clarification</td>
                                    <td class="text-center"><p><input type="radio" name="isClarification" value="Yes" <?php echo ($list['IsPRClarification'] === 'Yes') ? 'checked' : ''; ?> required="required">Yes</p>
                                        <p><input type="radio" name="isClarification" value="No" <?php echo ($list['IsPRClarification'] === 'No') ? 'checked' : ''; ?>>No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="clarificationComment" name="clarificationComment" required="required"><?php echo $list['PRClarificationComments']; ?></textarea></td>
                                </tr>

                            </tbody>
                        </table>
                          <button class="continueBtn" onclick="processPurposeOfResearch()">Continue</button>
                        <p class="gradient"></p>
                    </div>

                    <!-- Quests Dimension -->
                    <div id="questsDimension">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="success">
                                    <th class="text-center">Question</th>
                                    <th class="text-center">Score</th>
                                    <th class="text-center">Reviewer Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><b>Quality</b>
                                        <p>How reliable if the evidence?</p>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php
                                        $qdQualityScore = $list['QDQualityScore'];
                                        $qdQualityScore = array($qdQualityScore);
                                        FormHelper::displayDropdownSelection('score_t', 'Score', 'Rating', 'qualityScore', $qdQualityScore);
                                        ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="qualityComment" name="qualityComment" required="required"><?php echo $list['QDQualityComments']; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><b>Utility</b>
                                        <p>To what extent (time, cost, resources, flexibility, effort) can the method be transferred and adopted without modification?</p>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php
                                        $qdUtilityScore = $list['QDUtilityScore'];
                                        $qdUtilityScore = array($qdUtilityScore);
                                        FormHelper::displayDropdownSelection('score_t', 'Score', 'Rating', 'utilityScore', $qdUtilityScore);
                                        ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="utilityComment" name="utilityComment" required="required"><?php echo $list['QDUtilityComments']; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><b>Extent</b>
                                        <p>What is the magnitude of the evidence?</p>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php
                                        $qdExtentScore = $list['QDExtentScore'];
                                        $qdExtentScore = array($qdExtentScore);
                                        FormHelper::displayDropdownSelection('score_t', 'Score', 'Rating', 'extentScore', $qdExtentScore);
                                        ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="extentComment" name="extentComment" required="required"><?php echo $list['QDExtentComments']; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><b>Strength</b>
                                        <p>How strong is the evidence?</p>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php
                                        $qdStrengthScore = $list['QDStrengthScore'];
                                        $qdStrengthScore = array($qdStrengthScore);
                                        FormHelper::displayDropdownSelection('score_t', 'Score', 'Rating', 'strengthScore', $qdStrengthScore);
                                        ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="strengthComment" name="strengthComment" required="required"><?php echo $list['QDStrengthComments']; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><b>Target or Outcomes Measured</b>
                                        <p>What educational outcomes are measured?</p>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php
                                        $qdTargetScore = $list['QDTargetScore'];
                                        $qdTargetScore = array($qdTargetScore);
                                        FormHelper::displayDropdownSelection('score_t', 'Score', 'Rating', 'targetScore', $qdTargetScore);
                                        ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="targetComment" name="targetComment" required="required"><?php echo $list['QDTargetComments']; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><b>Setting or Context</b>
                                        <p>How relevant or applicable is the evidence to healthcare-related practice?</p>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php
                                        $qdSettingScore = $list['QDSettingScore'];
                                        $qdSettingScore = array($qdSettingScore);
                                        FormHelper::displayDropdownSelection('score_t', 'Score', 'Rating', 'settingScore', $qdSettingScore);
                                        ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="settingComment" name="settingComment" required="required"><?php echo $list['QDSettingComments']; ?></textarea></td>
                                </tr>
                            </tbody>
                        </table>
                         <button class="backBtn" onclick="processBackToPurposeOfResearch()">Back to Purpose</button>
                        <button class="submitBtn" onclick="submitForm()">Submit Data</button>
                        <p class="gradient"></p>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

}
