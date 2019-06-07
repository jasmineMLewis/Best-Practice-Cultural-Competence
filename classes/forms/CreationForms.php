<?php
require_once 'classes/utility/Database.php';
require_once 'classes/StudyInventory.php';

class CreationForms {

    public static function displayArticleChecklistCreationForm() {
        ?>
        <div id="wrapper_create">
            <div id="creation_form">
                <h1>Screen CheckList</h1>
                <form method="post" action="">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Response</th>
                                <th>Exclusion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class = "danger" style="vertical-align:middle">Is the article published between 2000-2015? * </td>
                                <td class = "danger"><p><input type="radio" name="isArticlePublished" value="Yes" required>Yes</p>
                                    <p><input type="radio" name="isArticlePublished" value="No">No</p>
                                    <p><input type="radio" name="isArticlePublished" value="Unsure">Unsure</p></td>
                                <td class = "success"><p>Include</p><p>Exclude</p><p>Flag</p></td>
                            </tr>

                            <tr>
                                <td style="vertical-align:middle">Is the target population students, residents, and providers in
                                    healthcare-related professions providing direct patient contact such
                                    as medicine, nursing, pharmacy, psychology, and allied health? *</td>
                                <td><p><input type="radio" name="isPopProvideDirectPatientContact" value="Yes" required>Yes</p>
                                    <p><input type="radio" name="isPopProvideDirectPatientContact" value="No">No</p>
                                    <p><input type="radio" name="isPopProvideDirectPatientContact" value="Unsure">Unsure</p></td>
                                <td class = "success"><p>Include</p><p>Exclude</p><p>Flag</p></td>
                            </tr>

                            <tr>
                                <td class="danger" style="vertical-align:middle">Is the publication peer-reviewed? *</td>
                                <td class="danger"><p><input type="radio" name="isPeerReviewed" value="Yes" required>Yes</p>
                                    <p><input type="radio" name="isPeerReviewed" value="No">No</p>
                                    <p><input type="radio" name="isPeerReviewed" value="Unsure">Unsure</p></td>
                                <td class = "success"><p>Include</p><p>Exclude</p><p>Flag</p></td>
                            </tr>

                            <tr>
                                <td style="vertical-align:middle">Does the study describe a planned education intervention that is designed to lead to a positive change in learners cultural
                                    competence(i.e., cultural competence is an outcome)? *</td>
                                <td><p><input type="radio" name="isPlannedEduInterv" value="Yes" required>Yes</p>
                                    <p><input type="radio" name="isPlannedEduInterv" value="No">No</p>
                                    <p><input type="radio" name="isPlannedEduInterv" value="Unsure">Unsure</p></td>
                                <td class = "success"><p>Include</p><p>Exclude</p><p>Flag</p></td>
                            </tr>

                            <tr>
                                <td class="danger" style="vertical-align:middle">Is the cultural competence topic related to race/ethnicity, origin/ancestry, and/or culture? *</td>
                                <td class="danger"><p><input type="radio" name="isRelatedToRace" value="Yes" required>Yes </p>
                                    <p><input type="radio" name="isRelatedToRace" value="No">No</p>
                                    <p><input type="radio" name="isRelatedToRace" value="Unsure">Unsure</p></td>
                                <td class = "success"><p>Include</p><p>Exclude</p><p>Flag</p></td>
                            </tr>

                            <tr>
                                <td style="vertical-align:middle">Decision *</td>
                                <td><p><input type="radio" name="decision" value="3" required>Flag</p>
                                    <p><input type="radio" name="decision" value="1">Include</p>
                                    <p><input type="radio" name="decision" value="2">Exclude</p></td>
                                <td><p>ALL YES, Include</p><p>ONE NO, exclude</p><p>ONE UNSURE, Flag</p></td>
                            </tr>
                        </tbody>
                    </table>

                    <p><textarea name="commentDecision" placeholder="COMMENTS ABOUT DECISION *" maxlength="5000"></textarea></p>
                    <br/>
                    <div class="submit_button">
                        <p><input name="submit" type="submit" value="Submit" /><p>
                    </div>
                </form>
                <p class="gradient"></p>
            </div>
        </div>
        <?php
    }

    public static function displayDataExtractionCreationForm() {
        ?>
        <div id="wrapper_creation_form">
            <progress id="progressBar" value="0" max="100"></progress>
            <div id="creation_form">
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
                                    <td class="text-center"><p><input type="radio" name="isLiteratureReview" value="Yes">Yes</p>
                                        <p><input type="radio" name="isLiteratureReview" value="No"><?php echo str_repeat('&nbsp;', 1); ?>No</p></td>
                                    <td></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Is there a theory or theoretical framework for the study? *</td>
                                    <td class="text-center"><input type="radio" name="isTheoryForStudy" value="Yes">Yes
                                        <p><input type="radio" name="isTheoryForStudy" value="No"><?php echo str_repeat('&nbsp;', 1); ?>No</p></td>
                                    <td style="vertical-align:middle">
                                        <div class="col-sm-14">
                                            <input type="text" class="form-control" id="isTheoryForStudyDescribe" name="isTheoryForStudyDescribe" placeholder="YES, describe" maxlength="500">
                                        </div></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Is there a learning outcome for the study? *</td>
                                    <td class="text-center"><p><input type="radio" name="isLearningOutcomeForStudy" value="Yes">Yes</p>
                                        <p><input type="radio" name="isLearningOutcomeForStudy" value="No"><?php echo str_repeat('&nbsp;', 1); ?>No</p></td>
                                    <td style="vertical-align:middle"><div class="col-sm-14">
                                            <input type="text" class="form-control" id="learningOutcomeForStudyDescribe" name="learningOutcomeForStudyYesDescribe" placeholder="YES, describe" maxlength="500"></div>
                                    </td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Is there a clear, well-defined objective? *</td>
                                    <td class="text-center"><p><input type="radio" name="isClearWellDefined" value="Yes">Yes</p>
                                        <p><input type="radio" name="isClearWellDefined" value="No"><?php echo str_repeat('&nbsp;', 1); ?>No</p></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">What levels of learner outcomes are included?</td>
                                    <td class="text-center">
                                        <?php FormHelper::displayComboBox('learner_outcome_t', 'OutcomeID', 'Outcome', 'outcome'); ?>
                                    </td>
                                    <td style="vertical-align:middle"><div class="col-sm-18"><input type="text" class="form-control" id="levelsLearnerOutcomeOther" 
                                                                                                    name="levelsLearnerOutcomeOther" placeholder="OTHER, specify" maxlength="500"></div></td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="continueBtn" onclick="processBackground()">Continue</button>
                        <p class="gradient"></p>
                    </div>

                    <!-- Methods -->
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
                                    <td class="text-center"><p><input type="radio" name="isStudyDesignReported" value="Yes">Yes</p>
                                        <p><input type="radio" name="isStudyDesignReported" value="No"><?php echo str_repeat('&nbsp;', 1); ?>No</p></td>
                                    <td></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Study Design</td>
                                    <td class="text-center">
                                        <?php FormHelper::displayComboBox('study_design_t', 'StudyDesignID', 'StudyDesign', 'studyDesign'); ?>
                                    </td>
                                    <td style="vertical-align:middle"><div class="col-sm-18"><input type="text" class="form-control" id="studyDesignOtherDescribe" 
                                                                                                    name="studyDesignOtherDescribe" placeholder="OTHER, describe" maxlength="500"></div></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Is the design appropriate to answer the research question? *</td>
                                    <td class="text-center"> <p><input type="radio" name="isDesignAppropriate" value="Yes">Yes<?php echo str_repeat('&nbsp;', 8); ?></p>
                                        <p><input type="radio" name="isDesignAppropriate" value="No">No<?php echo str_repeat('&nbsp;', 10); ?></p
                                        <p><input type="radio" name="isDesignAppropriate" value="Not Sure">Not Sure</p></td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-18"><input type="text" class="form-control" id="isDesignAppropriateDescription"
                                                                                                                        name="isDesignAppropriateDescription" placeholder="NOT SURE, specify" maxlength="500"></div></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">What instruments were used?</td>
                                    <td class="text-center"><div class="col-sm-20"><input type="text" class="form-control" id="instrumentsUsed" name="instrumentsUsed" maxlength="500"></div></td>
                                    <td class="text-center"></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">What is the total study duration?</td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-20"><input type="text" class="form-control" id="totalStudyDurationTime"
                                                                                                                        name="totalStudyDurationTime" placeholder="TIME" maxlength="500"></div></td>
                                    <td class="text-center"><p><input type="checkbox" name="totalStudyDurationOption" value="Not Specified"> Not Specified</p></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Is there bias in the methods? *</td>
                                    <td class="text-center"><p><input type="radio" name="isBiasMethods" value="Yes">Yes<?php echo str_repeat('&nbsp;',8); ?></p>
                                        <p><input type="radio" name="isBiasMethods" value="No">No<?php echo str_repeat('&nbsp;', 10); ?></p>
                                        <p><input type="radio" name="isBiasMethods" value="Not Sure">Not Sure</p></td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <p><div class="col-sm-20"><input type="text" class="form-control" id="isBiasMethodsYesDescribe" name="isBiasMethodsYesDescribe" placeholder="YES, describe" maxlength="500"></div></p>
                                        <p><div class="col-sm-20"><input type="text" class="form-control" id="isBiasMethodsUnsure" name="isBiasMethodsUnsure" placeholder="NOT SURE, specify" maxlength="500"></div></p>
                                    </td>
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
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-20"><input type="text" class="form-control" id="totalParticipants" name="totalParticipants" maxlength="500"></div>
                                    </td>
                                    <td class="text-center"><p><input type="checkbox" name="particiantsTotalSpecificiation" value="Not Specified"> Not Specified</p></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Type of participants</td>
                                    <td class="text-center">
                                        <?php FormHelper::displayComboBox('participant_t', 'ParticipantID', 'Participant', 'participantTypes'); ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><p><div class="col-sm-12"><input type="text" class="form-control" id="studentAcademicLevel" name="studentAcademicLevel" placeholder="STUDENT, academic level" maxlength="500"></div></p>
                                        <p><div class="col-sm-12"><input type="text" class="form-control"  id="participantOther" name="participantOther" placeholder="OTHER, specify" maxlength="500"></div></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Age</td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="age" name="age" maxlength="500"></div>
                                    </td>
                                    <td class="text-center"><p><input type="checkbox" name="ageSpecificiation" value="Not Specified"> Not Specified</p></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Gender</td>
                                    <td class="text-center">
                                        <?php FormHelper::displayComboBox('gender_t', 'GenderID', 'Gender', 'gender'); ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="genderOther" name="genderOther" placeholder="OTHER, specify" maxlength="500"></div></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Professions</td>
                                    <td class="text-center">
                                        <?php FormHelper::displayComboBox('profession_t', 'ProfessionID', 'Profession', 'profession'); ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="professionOther" name="professionOther" placeholder="OTHER, specify" maxlength="500"></div></td>
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
                                    <td class="text-center"><p><input type="radio" name="isEducationDescribed" value="Yes">Yes<?php echo str_repeat('&nbsp;', 6); ?></p>
                                        <p><input type="radio" name="isEducationDescribed" value="No">No<?php echo str_repeat('&nbsp;', 8); ?></p>
                                        <p><input type="radio" name="isEducationDescribed" value="Not Sure">Not Sure</p></td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="educationNotSureDescribe" name="educationNotSureDescribe" placeholder="UNSURE, specify" maxlength="500"></div></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Number of intervention groups</td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="numberInterventionGrp" name="numberInterventionGrp" maxlength="500"></div></td>
                                    <td class="text-center"><p><input type="checkbox" name="numberInterSpecificiation" value="Not Specified"> Not Specified</p></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Number of participants allocated to each intervention group</td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="participantNumberInterventionGrp" name="participantNumberInterventionGrp" maxlength="500"></div></td>
                                    <td class="text-center"><p><input type="checkbox" name="numberParticipantGrpInterSpecificiation" value="Not Specified"> Not Specified</p></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Topics covered</td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12">
                                            <input type="text" class="form-control" id="topicCover" name="topicCover"></div></td>
                                    <td style="vertical-align:middle" class="text-center"><p><input type="checkbox" name="topicCoverSpecificiation" value="Not Specified"> Not Specified</p></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Type of intervention/Teaching methods</td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php FormHelper::displayComboBox('teaching_method_t', 'TeachingMethodID', 'TeachingMethod', 'teachingmethod'); ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <p><div class="col-sm-12"><input type="text" class="form-control" id="teachingMethodCreditHrs" name="teachingMethodCreditHrs" placeholder="# Credit Hours"></div></p>
                                        <p><div class="col-sm-12"><input type="text" class="form-control" id="teachingMethodOther" name="teachingMethodOther" placeholder="OTHER, specfiy"></div></p>
                                    </td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Instructional resources used</td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php FormHelper::displayComboBox('instructional_resource_t', 'InstructionalResourceID', 'InstructionalResource', 'instructionalResource'); ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><p><div class="col-sm-12"><input type="text" class="form-control" id="instructionalResourceOther" name="instructionalResourceOther" placeholder="OTHER, specfiy"></div></td>
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
                                        <?php FormHelper::displayComboBox('assessment_intervention_t', 'AssessmentInterventionID', 'AssessmentIntervention', 'assessmentIntervention'); ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><p><div class="col-sm-10"><input type="text" class="form-control"  id="assessmentInterventionOther" name="assessmentInterventionOther" placeholder="OTHER, specfiy" maxlength="500"></div></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Time points collected and reported</td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-16"><input type="text" class="form-control" id="timePointsCollected" name="timePointsCollected" maxlength="500"></div></td>
                                    <td style="vertical-align:middle" class="text-center"><input type="checkbox" name="timePointCollectedSpecification" value="Not Specified"> Not Specified</td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Unit of measurement (if relevant)</td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-16"><input type="text" class="form-control" id="unitOfMeasurement" name="unitOfMeasurement" maxlength="500"></div></td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <input type="checkbox" name="unitOfMesaurementSpecification" value="Not Specified"> Not Specified</td> 
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">For scales, upper and lower limits and interpretation (if relevant)</td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-16"><input type="text" class="form-control" id="limitsAndInterpretation" name="limitsAndInterpretation" maxlength="500"></div></td>
                                    <td style="vertical-align:middle" class="text-center"><input type="checkbox" name="limitSpecification" value="Not Specified"> Not Specified</td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Evaluation Criteria</td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-16"><input type="text" class="form-control" id="evaluationCriteria" name="evaluationCriteria" maxlength="500"></div></td>
                                    <td style="vertical-align:middle" class="text-center"><input type="checkbox" name="elevuationCriteriaSpecification" value="Not Specified"> Not Specified</td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="backBtn" onclick="processBackToInterventions()">Back to Interventions</button>
                        <button class="proceedBtn" onclick="processAssessment()">Go to Results</button>
                        <p class="gradient"></p>
                    </div>

                    <!-- Results -->
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
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="sampleSize" name="sampleSize" maxlength="500"></div></td>
                                    <td style="vertical-align:middle" class="text-center"><input type="checkbox" name="sampleSizeSpecification" value="Not Specified"> Not Specified</td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Response Rate</td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="responseRate" name="responseRate" maxlength="500"></div></td>
                                    <td style="vertical-align:middle" class="text-center"><input type="checkbox" name="responseRateSpecification" value="Not Specified"> Not Specified</td>
                                </tr
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Missing participants</td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="missingParticipants" name="missingParticipants" maxlength="500"></div></td>
                                    <td style="vertical-align:middle" class="text-center"><input type="checkbox" name="missingParticipantsSpecification" value="Not Specified"> Not Specified</td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Summary data for each intervention</td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <p><div class="col-sm-12"><input type="text" class="form-control" id="mean" name="mean" placeholder="MEAN" maxlength="500"></div></p>
                                        <p><div class="col-sm-12"><input type="text" class="form-control" id="ci" name="ci" placeholder="CI" maxlength="500"></div></p>
                                        <p><div class="col-sm-12"><input type="text" class="form-control" id="sd" name="sd" placeholder="SD" maxlength="500"></div></p>
                                        <p><div class="col-sm-12"><input type="text" class="form-control" id="pValue" name="pValue" placeholder="P-Value" maxlength="500"></div></p>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><p><div class="col-sm-12"><input type="text" class="form-control" id="otherSummaryData" name="otherSummaryData" placeholder="OTHER, specify" maxlength="500"></div></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Subgroup analyses, if applicable</td>
                                    <td style="vertical-align:middle" class="text-center"><div class="col-sm-12"><input type="text" class="form-control" id="subgrpAnalyses" name="subgrpAnalyses" maxlength="500"></div></td>
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
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="keyAuthorConclusion" name="keyAuthorConclusion" maxlength="2500"></textarea></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">Limitations of Study</td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="studyLimiation" name="studyLimiation" maxlength="2500"></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Comments from Authors</td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="authorComments" name="authorComments" maxlength="2500"></textarea></td>
                                </tr>
                                <tr class="danger">
                                    <td style="vertical-align:middle" class="text-center">References to other relevant studies</td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="referenceToOthStudies" name="referenceToOthStudies" maxlength="2500"></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center">Correspondence required *</td>
                                    <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="correspondenceRequired" value="Yes">Yes</p>
                                        <p><input type="radio" name="correspondenceRequired" value="No">No<?php echo str_repeat('&nbsp;', 2); ?></p></td>
                                </tr>
                            </tbody>
                        </table>
                          <button class="backBtn" onclick="processBackToResults()">Back to Results</button>
                          <button class="submitBtn" onclick="submitForm()">Submit Data</button>
                        <p class="gradient"></p>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    public static function displayKirkPatrickCreationForm() {
        ?>
        <div id="wrapper_creation_form">
            <div id="creation_form">
                <h1>Kirkpatrick Rating</h1>
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
                                <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="isLevelOne" value="Yes" required="required">Yes</p>
                                    <p><input type="radio" name="isLevelOne" value="No">No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="levelOneAComment" name="levelOneAComment" required="required" maxlength="2500"></textarea></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center"><b>Level 2a</b><p> Change of Attitudes/Perceptions</p></td>
                                <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="isLevelTwoA" value="Yes" required="required">Yes</p>
                                    <p><input type="radio" name="isLevelTwoA" value="No">No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="levelTwoAComment" name="levelTwoAComment" required="required" maxlength="2500"></textarea></td>
                            </tr>
                            <tr class = "danger">
                                <td style="vertical-align:middle" class="text-center"><b>Level 2b</b><p> Change of Knowledge and/or Skills</p></td>
                                <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="isLevelTwoB" value="Yes" required="required">Yes</p>
                                    <p><input type="radio" name="isLevelTwoB" value="No">No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="levelTwoBComment" name="levelTwoBComment" required="required" maxlength="2500"></textarea></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center"><b>Level 3a</b><p> Self-reported Behavioral Change</p></td>
                                <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="isLevelThreeA" value="Yes" required="required">Yes</p>
                                    <p><input type="radio" name="isLevelThreeA" value="No">No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="levelThreeAComment" name="levelThreeAComment" required="required" maxlength="2500"></textarea></td>
                            </tr>
                            <tr class = "danger">
                                <td style="vertical-align:middle" class="text-center"><b>Level 3b</b><p> Observed Behavioral Change</p></td>
                                <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="isLevelThreeB" value="Yes" required="required">Yes</p>
                                    <p><input type="radio" name="isLevelThreeB" value="No">No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="levelThreeBComment" name="levelThreeBComment" required="required" maxlength="2500"></textarea></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center"><b>Level 4a</b><p> Changes in Professional Practice</p></td>
                                <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="isLevelFourA" value="Yes" required="required">Yes</p>
                                    <p><input type="radio" name="isLevelFourA" value="No">No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="levelFourAComment" name="levelFourAComment" required="required" maxlength="2500"></textarea></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center"><b>Level 4b</b><p> Benefits to Patients</p></td>
                                <td style="vertical-align:middle" class="text-center"><p><input type="radio" name="isLevelFourB" value="Yes" required="required">Yes</p>
                                    <p><input type="radio" name="isLevelFourB" value="No">No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="levelFourBComment" name="levelFourBComment" required="required" maxlength="2500"></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                    <p><input name="kirkpatrick" type="submit" value="Submit" class="creationBtn"/><p>
                    <p class="gradient"></p>
                </form>
            </div>
        </div>
        <?php
    }

    public static function displayQuestsAppraisalCreationForm() {
        ?>
        <div id="wrapper_creation_form">
            <progress id="progressBar" value="50" max="100"></progress>
            <div id="creation_form">
                <h1 id="phaseName">Purpose of Research</h1>
                <form id="multiphase" onsubmit="return false">
                    <!-- Purpose of Research-->
                    <div id="purposeOfResearch">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="success">
                                    <th class="text-center">Question</th>
                                    <th class="text-center">Is that Clear?</th>
                                    <th class="text-center">Reviewer Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><b>Description</b> <p>What was done? *</p></td>
                                    <td class="text-center"><p><input type="radio" name="isDescription" value="Yes">Yes</p>
                                        <p><input type="radio" name="isDescription" value="No">No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="descriptionComment" name="descriptionComment" maxlength="2500"></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><b>Justification</b> <p>Did it work? *</p></td>
                                    <td class="text-center"><p><input type="radio" name="isJustification" value="Yes">Yes</p>
                                        <p><input type="radio" name="isJustification" value="No">No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="justificationComment" name="justificationComment" maxlength="2500"></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><b>Clarification</b>  <p>Why or how did it work? *</p></td>
                                    <td class="text-center"><p><input type="radio" name="isClarification" value="Yes">Yes</p>
                                        <p><input type="radio" name="isClarification" value="No">No <?php echo str_repeat('&nbsp;', 1); ?> </p></td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="clarificationComment" name="clarificationComment" maxlength="2500"></textarea></td>
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
                                    <td style="vertical-align:middle" class="text-center"><b>Quality *</b>
                                        <p>How reliable if the evidence?</p>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php FormHelper::displayDropdownBoxRequired('score_t', 'Score', 'Rating', 'qualityScore', 'Score'); ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="qualityComment" name="qualityComment" required="required" maxlength="2500"></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><b>Utility *</b>
                                        <p>To what extent (time, cost, resources, flexibility, effort) can the method be transferred and adopted without modification?</p>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php FormHelper::displayDropdownBoxRequired('score_t', 'Score', 'Rating', 'utilityScore', 'Score'); ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="utilityComment" name="utilityComment" required="required" maxlength="2500"></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><b>Extent *</b>
                                        <p>What is the magnitude of the evidence?</p>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php FormHelper::displayDropdownBoxRequired('score_t', 'Score', 'Rating', 'extentScore', 'Score'); ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="extentComment" name="extentComment" required="required" maxlength="2500"></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><b>Strength *</b>
                                        <p>How strong is the evidence?</p>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php FormHelper::displayDropdownBoxRequired('score_t', 'Score', 'Rating', 'strengthScore', 'Score'); ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="strengthComment" name="strengthComment" required="required" maxlength="2500"></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><b>Target or Outcomes Measured *</b>
                                        <p>What educational outcomes are measured?</p>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php FormHelper::displayDropdownBoxRequired('score_t', 'Score', 'Rating', 'targetScore', 'Score'); ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="targetComment" name="targetComment" required="required" maxlength="2500"></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><b>Setting or Context *</b>
                                        <p>How relevant or applicable is the evidence to healthcare-related practice?</p>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center">
                                        <?php FormHelper::displayDropdownBoxRequired('score_t', 'Score', 'Rating', 'settingScore', 'Score'); ?>
                                    </td>
                                    <td style="vertical-align:middle" class="text-center"><textarea class="form-control" id="settingComment" name="settingComment" required="required" maxlength="2500"></textarea></td>
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

    public static function displayStudyInventoryCreationForm() {
        ?>
        <div id="wrapper">
            <div id="creation_form">
                <h1>Study Inventory</h1>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="article_code">
                        <h3>Article Code</h3>
                        <p class="code">
                            <?php FormHelper::displayDoubleContentDropdownBoxRequired('code_t', 'Code', 'Label', 'code', 'Code'); ?>
                        </p>
                    </div>
                    </br>

                    <!-- Article Retrieval -->
                    <div class="article_retrieval">
                        <h3>Article Retrieval</h3>
                        <p class="search_method">Search Method *
                            <input type="radio" name="searchMethod" value="1" required>Electronic
                            <input type="radio" name="searchMethod" value="2">Hand 
                        </p>
                        <p>Database *
                            <?php FormHelper::displayDropdownBoxRequired('database_t', 'DatabaseID', 'ResDatabase', 'database', 'Database'); ?>
                        </p>
                        </br>
                    </div>

                    <!-- Article Info -->
                    <div class="article_info">
                        <h3>Article Info</h3>
                        <div class="target_professions">
                            <p>Target Professions</p>
                            <?php FormHelper::displayComboBox('profession_t', 'ProfessionID', 'Profession', 'profession', 'Profession'); ?>
                        </div>

                        <div class="institutions">
                            <p>Institutions</p>
                            <?php FormHelper::displayComboBox('institution_t', 'InstitutionID', 'Institution', 'institution'); ?>
                        </div>

                        <!-- Title, Authors, Other Insititutions, DOI, PUBMED, & URL -->
                        <p class="title"><textarea name="title" placeholder="TITLE *" required="required" maxlength="350"></textarea></p>
                        <p class="authors"><textarea name="authors" placeholder="FIRST AUTHOR *" required="required" maxlength="255"></textarea></p>
                        <p class="otherInstitutions"><textarea name="otherInstitutions" placeholder="OTHER INSTITUTIONS (If multiple, separate by Commas)" maxlength="500"></textarea></p>
                        <p class="doi"><textarea name="doi" placeholder="DOI" maxlength="100"></textarea></p>
                        <p class="pubmed"><textarea name="pubmed" placeholder="PUBMED" maxlength="100"></textarea></p>
                        <p class="url"><textarea name="url" placeholder="URL" maxlength="255"></textarea></p>

                        <div class="country">
                            <p>Countries</p>
                            <?php FormHelper::displayComboBox('country_t', 'CountryID', 'Country', 'country'); ?>
                        </div>
                        <div class="document_type">Type *
                            <?php FormHelper::displayDropdownBoxRequired('document_type_t', 'DocumentTypeID', 'DocumentType', 'documentType', 'Document Type'); ?>
                        </div>
                        <div class="article_abstract">
                            <h3>Original Abstract</h3>
                            <textarea name="originalAbstract" placeholder="ORIGINAL ABSTRACT *" required="required" maxlength="5000"></textarea>
                        </div>
                        <div class="article_citation">
                            <h3>Citation</h3>
                            <textarea name="citation" placeholder="CITATION (APA) FORMAT *" required="required" maxlength="500"></textarea>
                        </div>
                        <div class="article_relevant_points">
                            <h3>Relevant Points</h3>
                            <textarea name="relPoint1" placeholder="RELEVANT POINT ONE" maxlength="500"></textarea>
                            <textarea name="relPoint2" placeholder="RELEVANT POINT TWO" maxlength="500"></textarea>
                            <textarea name="relPoint3" placeholder="RELEVANT POINT THREE" maxlength="500"></textarea>
                        </div>
                        <p class="published_month_year">Published *
                            <?php FormHelper::displayDropdownBoxNotRequired('calendar_months_t', 'CalendarMonthID', 'CalendarMonth', 'publishedMonth', 'Month'); ?>
                            <?php FormHelper::displayDropdownBoxRequired('calendar_years_t', 'CalendarYear', 'CalendarYear', 'publishedYear', 'Year'); ?>
                        </p>
                        <p class="study_years">Years of Study
                            <?php FormHelper::displayDropdownBoxNotRequired('calendar_years_t', 'CalendarYear', 'CalendarYear', 'studyYearBegan', 'Year'); ?> - 
                            <?php FormHelper::displayDropdownBoxNotRequired('calendar_years_t', 'CalendarYear', 'CalendarYear', 'studyYearEnd', 'Year'); ?>
                        </p>
                    </div>
                    <div class="article_upload">
                        <input type="file" name="file" id="file" required="required">
                    </div>
                    <div class="submit_button">
                        <p><input name="studyInventory" type="submit" value="Submit" /><p>
                    </div>
                </form>
                <p class="gradient"></p>
            </div>
        </div>
        <?php
    }

}
