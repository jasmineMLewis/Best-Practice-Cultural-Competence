<?php
require_once 'classes/ChecklistScreen.php';
require_once 'classes/utility/Database.php';
require_once 'classes/DataExtraction.php';
require_once 'classes/forms/FormHelper.php';
require_once 'classes/KirkpatrickRating.php';
require_once 'classes/StudyInventory.php';
require_once 'classes/QuestsAppraisal.php';

class ReviewerPhasesData {

    public static function displayDataExtraction($studyCode) {
        $extraction = new DataExtraction();
        $list = $extraction->getViewInfo($studyCode);
        ?>
        <div id="wrapper_display">
            <div id="display_form">
                <h1>Data Extraction (<?php echo $studyCode; ?>) </h1>
                <!-- Background -->
                <div id="background">
                    <h2>Background/Objective</h2>
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
                                <td style="vertical-align:middle" class="text-center">Was there is a literature review?</td>
                                <td style="vertical-align:middle"  class="text-center"><p> <?php echo $list['IsLiteratureReview']; ?></p></td>
                                <td></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">Is there a theory or theoretical framework for the study?</td>
                                <td style="vertical-align:middle"  class="text-center"> <?php echo $list['IsTheoryForStudy']; ?></td>
                                <td style="vertical-align:middle" class="text-center">YES: <?php echo $list['TheoryStudyYesDescrpt']; ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Is there a learning outcome for the study?</td>
                                <td style="vertical-align:middle" class="text-center"> <?php echo $list['IsLearningOutcomeForStudy']; ?></td>
                                <td style="vertical-align:middle" class="text-center">YES:  <?php echo $list['IsLearningOutcomeForStudy']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">Is there a clear, well-defined objective?</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['IsObjectiveClear']; ?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">What levels of learner outcomes are included?</td>
                                <td style="vertical-align:middle"  class="text-center"><?php FormHelper::displayOrderedNumberedArray($list['IsLearnerOutcomeExists']) ?></td>
                                <td style="vertical-align:middle" class="text-center">OTHER: <?php echo $list['LearnerOutcomeOtherDescrpt']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Methods -->
                <div id="methods">
                    <h2>Methods</h2>
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
                                <td style="vertical-align:middle" class="text-center">Is the study design reported?</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['IsStudyDesignReported']; ?></td>
                                <td></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">Study Design</td>
                                <td style="vertical-align:middle" class="text-center"><?php FormHelper::displayOrderedNumberedArray($list['IsStudyDesignExists']); ?></td>
                                <td style="vertical-align:middle" class="text-center">OTHER: <?php echo $list['StudyDesignOtherDescrpt']; ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Is the design appropriate to answer the research question?</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['IsDesignAppropriate']; ?></td>
                                <td style="vertical-align:middle" class="text-center">UNSURE: <?php echo $list['IsDesignAppropriateUnsureDescrpt']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">What instruments were used?</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['InstrumentsUsed']; ?></td>
                                <td class="text-center"></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">What is the total study duration?</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['TotalStudyDuration']; ?></td>
                                <td style="vertical-align:middle" class="text-center">SPECIFICATION: <?php echo $list['TotalStudyDurationSpecification']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">Is there bias in the methods?</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['IsMethodsBias']; ?></td>
                                <td style="vertical-align:middle" class="text-center">
                                    <p>YES: <?php echo $list['MethodsBiasYesDescrpt']; ?></p>
                                    <p>NOT SURE: <?php echo $list['MethodsBiasUnsureDescrpt']; ?></p></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Participants -->
                <div id="participant">
                    <h2>Participants</h2>
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
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['TotalParticipants']; ?></td>
                                <td style="vertical-align:middle" class="text-center">SPECIFICATION: <?php echo $list['TotalParticipantsSpecification']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">Type of participants</td>
                                <td class="text-center"><?php FormHelper::displayOrderedNumberedArray($list['IsParticipantExists']); ?></td>
                                <td style="vertical-align:middle" class="text-center">
                                    <p>STUDENT ACADEMIC LEVEL: <?php echo $list['ParticipantStudentAcademicLevel']; ?></p>
                                    <p>OTHER: <?php echo $list['ParticipantOtherDescrpt']; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Age</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['AgeDemographic']; ?></td>
                                <td class="text-center">SPECIFICATION: <?php echo $list['AgeDemographicSpecification']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">Gender</td>
                                <td style="vertical-align:middle" class="text-center"><?php FormHelper::displayOrderedNumberedArray($list['IsGenderExists']); ?></td>
                                <td style="vertical-align:middle" class="text-center">OTHER: <?php echo $list['GenderOtherDescrpt']; ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Professions</td>
                                <td style="vertical-align:middle" class="text-center"><?php FormHelper::displayOrderedNumberedArray($list['IsProfessionExists']); ?></td>
                                <td style="vertical-align:middle" class="text-center">OTHER: <?php echo $list['ProfessionOtherDescrpt']; ?> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Interventions -->
                <div id="intervention">
                    <h2>Interventions</h2>
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
                                <td style="vertical-align:middle" class="text-center">Is the educational intervention clearly described?</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['IsEduIntervDescriptionClear']; ?></td>
                                <td style="vertical-align:middle" class="text-center">UNSURE: <?php echo $list['EduIntervDescriptionUnsureDescrpt']; ?> </td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">Number of intervention groups</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['NumberIntervGroups']; ?></td>
                                <td style="vertical-align:middle" class="text-center">SPECIFICATION: <?php echo $list['NumberIntervGroupsSpecification']; ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Number of participants allocated to each intervention group</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['NumberParticipantsInIntervGroups']; ?></td>
                                <td style="vertical-align:middle" class="text-center">SPECIFICATION: <?php echo $list['NumberParticipantsInIntervGroupsSpecification']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">Topics covered</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['TopicsCover']; ?></td>
                                <td style="vertical-align:middle" class="text-center">SPECIFICATION: <?php echo $list['TopicsCoverSpecification']; ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Type of intervention/Teaching methods</td>
                                <td style="vertical-align:middle" class="text-center"><?php FormHelper::displayOrderedNumberedArray($list['IsTeachingMethodExists']); ?></td>
                                <td style="vertical-align:middle" class="text-center">
                                    <p>NUMBER CREDIT HOURS: <?php echo $list['TeachingMethodCreditHrs']; ?></p>
                                    <p>OTHER: <?php echo $list['TeachingMethodOtherDescrpt']; ?></p>
                                </td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">Instructional resources used</td>
                                <td style="vertical-align:middle" class="text-center"><?php FormHelper::displayOrderedNumberedArray($list['IsInstructionalRescourceExists']); ?></td>
                                <td style="vertical-align:middle" class="text-center">OTHER: <?php echo $list['InstructionalRescourceOtherDescrpt']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Assessment -->
                <div id="assessments">
                    <h2>Assessment</h2>
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
                                <td style="vertical-align:middle" class="text-center"><?php FormHelper::displayOrderedNumberedArray($list['IsAssessmentIntervExists']); ?>  </td>
                                <td style="vertical-align:middle" class="text-center">OTHER: <?php echo $list['AssessmentIntervOther']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">Time points collected and reported</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['TimePointsCollected']; ?></td>
                                <td style="vertical-align:middle" class="text-center">SPECIFICATION: <?php echo $list['TimePointsCollectedSpecification']; ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Unit of measurement (if relevant)</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['UnitOfMeasurement']; ?></td>
                                <td style="vertical-align:middle" class="text-center">SPECIFICATION: <?php echo $list['UnitOfMeasurementSpecification']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">For scales, upper and lower limits and interpretation (if relevant)</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['ScaleLimitInterpretation']; ?></td>
                                <td style="vertical-align:middle" class="text-center">SPECIFICATION: <?php echo $list['ScaleLimitInterpretationSpecification']; ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Evaluation Criteria</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['EvaluationCriteria']; ?></td>
                                <td style="vertical-align:middle" class="text-center">SPECIFICATION: <?php echo $list['EvaluationCriteriaSpecifciation']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Results -->
                <div id="results">
                    <h2>Results</h2>
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
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['SampleSize']; ?></td>
                                <td style="vertical-align:middle" class="text-center">SPECIFICATION: <?php echo $list['SampleSizeSpecification']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">Response Rate</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['ResponseRate']; ?></td>
                                <td style="vertical-align:middle" class="text-center">SPECIFICATION: <?php echo $list['ResponseRateSpecifcation']; ?> </td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Missing participants</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['MissingParticipants']; ?></td>
                                <td style="vertical-align:middle" class="text-center">SPECIFICATION: <?php echo $list['MissingParticipantsSpecification']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">Summary data for each intervention</td>
                                <td style="vertical-align:middle" class="text-center">
                                    <p>MEAN: <?php echo $list['SummaryDataMean']; ?> </p>
                                    <p>CI: <?php echo $list['SummaryDataCI']; ?></p>
                                    <p>SD: <?php echo $list['SummaryDataSD']; ?> </p>
                                    <p>P-VALUE: <?php echo $list['SummaryDataPValue']; ?></p></td>
                                <td style="vertical-align:middle" class="text-center">OTHER: <?php echo $list['SummaryDataOther']; ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Subgroup analyses, if applicable</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['SubgroupAnalyses']; ?></td>
                                <td style="vertical-align:middle" class="text-center"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Miscellaneous -->
                <div id="miscellaneouses">
                    <h2>Miscellaneous</h2>
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
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['AuthorConclusion']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">Limitations of Study</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['StudyLimitation']; ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Comments from Authors</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['AuthorComments']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center">References to other relevant studies</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['ReferenceToStudies']; ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center">Correspondence required</td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['IsCorrespondenceRequired']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="gradient"></p>
            </div>
        </div>
        <?php
    }

    public static function displayKirkpatrickRating($studyCode, $reviewerID) {
        $rating = new KirkpatrickRating();
        $list = $rating->getInfo($studyCode, $reviewerID);
        ?>
        <div id="wrapper_display_info">
            <div id="display_info">
                <h1>Kirkpatrick Rating (<?php echo $studyCode; ?>) </h1>
                <table class="table table-bordered">
                    <thead>
                        <tr class="success">
                            <th class="text-center">Question</th>
                            <th class="text-center">Response</th>
                            <th class="text-center">Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class = "danger">
                            <td style="vertical-align:middle" class="text-center"><b>Level 1</b><p> Learner's reaction to the Educational Intervention</p></td>
                            <td style="vertical-align:middle" class="text-center"><?php echo $list['IsLevelOne']; ?></td>
                            <td style="vertical-align:middle" class="text-center"><?php echo $list['LevelOneAComments']; ?></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:middle" class="text-center"><b>Level 2a</b><p> Change of Attitudes/Perceptions</p></td>
                            <td style="vertical-align:middle" class="text-center"><?php echo $list['IsLevelTwoA']; ?> </p></td>
                            <td style="vertical-align:middle" class="text-center"><?php echo $list['LevelTwoAComments']; ?></td>
                        </tr>
                        <tr class = "danger">
                            <td style="vertical-align:middle" class="text-center"><b>Level 2b</b><p> Change of Knowledge and/or Skills</p></td>
                            <td style="vertical-align:middle" class="text-center"><?php echo $list['IsLevelTwoB']; ?></td>
                            <td style="vertical-align:middle" class="text-center"><?php echo $list['LevelTwoBComments']; ?></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:middle" class="text-center"><b>Level 3a</b><p> Self-reported Behavioral Change</p></td>
                            <td style="vertical-align:middle" class="text-center"><?php echo $list['IsLevelThreeA']; ?></td>
                            <td style="vertical-align:middle" class="text-center"><?php echo $list['LevelThreeAComments']; ?></td>
                        </tr>
                        <tr class = "danger">
                            <td style="vertical-align:middle" class="text-center"><b>Level 3b</b><p> Observed Behavioral Change</p></td>
                            <td style="vertical-align:middle" class="text-center"><?php echo $list['IsLevelThreeB']; ?></td>
                            <td style="vertical-align:middle" class="text-center"><?php echo $list['LevelThreeBComments']; ?></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:middle" class="text-center"><b>Level 4a</b><p> Changes in Professional Practice</p></td>
                            <td style="vertical-align:middle" class="text-center"><?php echo $list['IsLevelFourA']; ?></td>
                            <td style="vertical-align:middle" class="text-center"><?php echo $list['LevelFourAComments']; ?></td>
                        </tr>
                        <tr class="danger">
                            <td style="vertical-align:middle" class="text-center"><b>Level 4b</b><p> Benefits to Patients</p></td>
                            <td style="vertical-align:middle" class="text-center"><?php echo $list['IsLevelFourB']; ?></td>
                            <td style="vertical-align:middle" class="text-center"><?php echo $list['LevelFourBComments']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="gradient"></p>
        </div>
        <?php
    }

    public static function displayQuestsAppraisal($studyCode, $reviewerID) {
        $quests = new QuestsAppraisal();
        $list = $quests->getInfo($studyCode, $reviewerID);
        ?>
        <div id="wrapper_display">
            <div id="display_form">
                <h1>QUESTS Appraisal (<?php echo $studyCode; ?>) </h1>
                <!-- Purpose of Research -->
                <div id="purposeOfResearch">
                    <h2>Purpose of Research</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Question</th>
                                <th class="text-center">Is that Clear?</th>
                                <th class="text-center">Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center"><b>Description</b> <p>What was done?</p></td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['IsPRDescription']; ?></td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['PRDescriptionComments']; ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center"><b>Justification</b> <p>Did it work?</p></td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['IsPRJustification']; ?></td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['PRJustificationComments']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center"><b>Clarification</b>  <p>Why or how did it work?</p></td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['IsPRClarification']; ?></td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['PRClarificationComments']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- QUESTS Dimension -->
                <div id="questsDimension">
                    <h2>QUESTS Dimension</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">Question</th>
                                <th class="text-center">Score</th>
                                <th class="text-center">Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center"><b>Quality</b>
                                    <p>How reliable if the evidence?</p>
                                </td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['QDQualityScore']; ?></td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['QDQualityComments']; ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center"><b>Utility</b>
                                    <p>To what extent (time, cost, resources, flexibility, effort) can the method be transferred and adopted without modification?</p>
                                </td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['QDUtilityScore']; ?></td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['QDUtilityComments']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center"><b>Extent</b>
                                    <p>What is the magnitude of the evidence?</p>
                                </td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['QDExtentScore']; ?></td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['QDExtentComments']; ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center"><b>Strength</b>
                                    <p>How strong is the evidence?</p>
                                </td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['QDStrengthScore']; ?></td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['QDStrengthComments']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center"><b>Target</b>
                                    <p>What educational outcomes are measured?</p>
                                </td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['QDTargetScore']; ?></td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['QDTargetComments']; ?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle" class="text-center"><b>Setting</b>
                                    <p>How relevant or applicable is the evidence to healthcare-related practice?</p>
                                </td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['QDSettingScore']; ?></td>
                                <td style="vertical-align:middle" class="text-center"><?php echo $list['QDSettingComments']; ?></td>
                            </tr>
                            <tr class="danger">
                                <td style="vertical-align:middle" class="text-center"><b>Final Score</b></td>
                                <td style="vertical-align:middle" class="text-center"><b><?php echo $list['FinalScore']; ?></b></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <p class="gradient"></p>
        </div>
        <?php
    }

    public static function displayScreenChecklist($studyCode, $reviewerID) {
        $checklist = new ChecklistScreen();
        $list = $checklist->getInfo($studyCode, $reviewerID);
        ?>
        <div id="wrapper_display">
            <div id="display_form">
                <h1>Screen CheckList (<?php echo $studyCode; ?>)</h1>
                <table class="table table-bordered">
                    <thead>
                        <tr class="success">
                            <th>Question</th>
                            <th>Response</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="danger">
                            <td>Is the article published between 2000-2015?</td>
                            <td><b> <?php echo $list['IsPublishedWithinTimeFrame']; ?></b></td>
                        </tr>
                        <tr>
                            <td>Is the target population students, residents, and providers in healthcare-related professions providing direct patient contact such
                                as medicine, nursing, pharmacy, psychology, and allied health?</td>
                            <td><b> <?php echo $list['IsPopProvideDirectPatientContact']; ?></b></td>
                        </tr>
                        <tr class="danger">
                            <td>Is the publication peer-reviewed?</td>
                            <td><b> <?php echo $list['IsPeerReviewed']; ?></b></td>
                        </tr>
                        <tr>
                            <td>Does the study describe a planned education intervention that 
                                is designed to lead to a positive change in learners cultural competence(i.e., cultural competence is an outcome)?</td>
                            <td><b> <?php echo $list['IsDescribedPlanEducationIntervention']; ?></b></td>
                        </tr>
                        <tr class="danger">
                            <td>Is the cultural competence topic related to race/ethnicity, origin/ancestry, and/or culture?</td>
                            <td><b> <?php echo $list['IsCulturalCompetenceTopicOriginRelated']; ?></b></td>
                        <tr>
                            <td>Decision</td>
                            <td class="success"><b><i><?php echo $list['Decision']; ?></b></i></td>
                        </tr>
                        <tr>
                            <td><b>COMMENTS</b> <?php echo $list['DecisionComments']; ?></td>
                        </tr>
                    </tbody>
                </table>
                <p class="gradient"></p>
            </div>
        </div>
        <?php
    }

    public static function displayStudyInventory($studyCode) {
        $studyInventory = new StudyInventory();
        $list = $studyInventory->getViewInfo($studyCode);
        ?>
        <div id="wrapper_display_info">
            <div id="display_info">
                <h1>Study Inventory (<?php echo $list['StudyCode']; ?>)</h1>
                <h3>Title</h3>
                <div class="title"><?php echo $list['Title']; ?></div>
                </br>
                <h3>Author(s)</h3>
                <div class="authors"> <?php echo $list['Authors']; ?></div>
                <br>
                <h3>Article Code</h3>
                <div class="article_code"><?php echo $list['StudyCode']; ?></div>
                <br>

                <h3>Article Retrieval</h3>
                <div class="source_retrieved"><b>Source Retrieved By</b> <?php echo $list['ReviewerName']; ?></div>
                <div class="search_method"><b>Search Method</b> <?php echo $list['SearchMethod']; ?></div>
                <div class="database"><b>Database</b> <?php echo $list['Database']; ?></div>
                <br><br><br><br>

                <h3>Article Info</h3>
                <div class="date_published"><b>Published</b> <?php echo $list['Published']; ?></div>
                <div class="years_of_study"><b>Year(s) of Study </b> <?php echo $list['StudyYears']; ?></div>
                <div class="document_type"><b>Document Type</b> <?php echo $list['DocumentType']; ?></div>
                <div class="country"><b>Countries</b> <?php FormHelper::displayOrderedNumberedArray($list['Countries']); ?></div>
                <div class="institutions"><b>Institution(s)</b>  <?php FormHelper::displayOrderedNumberedArray($list['Institutions']); ?></div>
                <div class="target_professions"><b>Target Profession(s)</b> <?php FormHelper::displayOrderedNumberedArray($list['Professions']); ?></div>
                <div class="doi"><b>DOI</b> <?php echo $list['Doi']; ?></div>
                <div class="pubmed"><b>PUBMED</b> <?php echo $list['Pubmed']; ?></div>
                <div class="url"><b>URL</b> <?php echo $list['Url']; ?></div>
                <div class="article_url"><b>Article URL</b> <?php echo $list['ArticleUrl']; ?></div>
                <div class="abstract"><h3>Original Abstract</h3>
                    <div class="abstract_content"> <?php echo $list['OriginalAbstract']; ?></div>
                </div>
                <div class="relevant_points"><h3>Relevant Points</h3>
                    <div class="points">
                        <ol>
                            <li> <?php echo $list['RelevantPointOne']; ?></li>
                            <li> <?php echo $list['RelevantPointTwo']; ?></li>
                            <li> <?php echo $list['RelevantPointThree']; ?></li>
                        </ol>
                    </div>
                </div>
                <div class="citation"><h3>Citation</h3>
                    <div class="citation_content"><?php echo $list['Citation']; ?></div>
                </div>
                <p class="gradient"></p>
            </div>
        </div>
        <?php
    }
}
