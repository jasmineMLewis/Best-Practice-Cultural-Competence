<?php

require_once 'classes/ChecklistScreen.php';
require_once 'classes/utility/Database.php';
require_once 'classes/forms/FormHelper.php';
require_once 'classes/DataExtraction.php';

class StudyInventory {

    const ARTICLE_DIRECTORY = "articles/";
    const DUMMY_FIELD = "DummyValue";
    const NON_APPLICABLE = "N/A";
    const TEMP_ARTICLE_DIRECTORY = "tempArticles/";
    const TEMP = "temp";
    const UPLOAD_FAIL = "fileNoUpload";

    private $_db;

    public function __construct() {
        $this->_db = Database::getInstance();
    }

    public function assignArticleToReviewer($studyCode, $reviewerID) {
        $arr = array(
            "StudyCode" => $studyCode,
            "ReviewerID" => $reviewerID,
            "ArticleProcessID" => "1",
            "IsDisabled" => "0");

        $this->_db->insert("article_process_stage_t", $arr);
    }

    public function assignReviewersToArticles($studyCode) {
        $isArticlesAssigned = 0;
        $reviewers = $this->getTeamMembersForCode($studyCode);
        $reviewersMinAssigned = $this->findReviewersWithMinArticleAssignments($reviewers);
        $reviewersToAssign = $this->selectReviewersToAssignArticles($reviewersMinAssigned);

        for ($i = 0; $i < count($reviewersToAssign); $i++) {
            $this->assignArticleToReviewer($studyCode, $reviewersToAssign[$i]);
            $isArticlesAssigned = 1;
        }
        return $isArticlesAssigned;
    }

    public function create($arr = array()) {
        $dummyValueFields = array("MonthPublished", "OtherInstitution", "Url", "Doi", "Pubmed",
            "OriginalAbstract", "RelevantPointOne", "RelevantPointTwo",
            "RelevantPointThree", "Citation");

        $data = $this->fillEmptyFieldsWithDefault(self::DUMMY_FIELD, $arr, $dummyValueFields);

        //get arrays
        $countries = $data['IsCountryExists'];
        $professions = $data['IsProfessionExists'];
        $institutions = $data['IsInstitutionExists'];

        //change to DB boolean value
        empty($data['IsCountryExists']) ?
                        $data['IsCountryExists'] = "0" :
                        $data['IsCountryExists'] = "1";
        empty($data['IsProfessionExists']) ?
                        $data['IsProfessionExists'] = "0" :
                        $data['IsProfessionExists'] = "1";
        empty($data['IsInstitutionExists']) ?
                        $data['IsInstitutionExists'] = "0" :
                        $data['IsInstitutionExists'] = "1";

        $months = array("Filler", "January", "Feburary", "March", "April", "May", "June", "July", "August", "September",
            "October", "November", "December");

        //Convert Month Number into Month Name
        if (is_numeric($data["MonthPublished"])) {
            $orgNum = $data["MonthPublished"];
            $data["MonthPublished"] = $months[$orgNum];
        }

        $this->_db->insert("study_inventory_t", $data);

        if (!empty($countries)) {
            $this->insertMultipleTableRows("study_inventory_country_t", $data['StudyCode'], "CountryID", $countries);
        }

        if (!empty($professions)) {
            $this->insertMultipleTableRows("study_inventory_profession_t", $data['StudyCode'], "ProfessionID", $professions);
        }

        if (!empty($institutions)) {
            $this->insertMultipleTableRows("study_inventory_institutions_t", $data['StudyCode'], "InstitutionID", $institutions);
        }
    }

    public function createStudyCode($code) {
        $query = $this->_db->query("SELECT * FROM study_inventory_t WHERE "
                . "StudyCode LIKE '$code%'");
        if ($query->getCount() == 0) {
            return $code . '-' . '001';
        } else if (($query->getCount() >= 1) && ($query->getCount() <= 9)) {
            return $code . '-' . '00' . ($query->getCount() + 1);
        } else if (($query->getCount() >= 10) && ($query->getCount() <= 99)) {
            return $code . '-' . '0' . ($query->getCount() + 1);
        } else {
            return $code . '-' . ($query->getCount() + 1);
        }
    }

    public function getEditInfo($studyCode) {
        $this->_db->query("SELECT study_inventory_t.StudyCode, study_inventory_t.ReviewerID, reviewer_t.FirstName, reviewer_t.LastName, 
            SearchMethodID, DocumentTypeID, DatabaseID, 
            YearPublished, YearStudyBegan, YearStudyEnd, IsCountryExists, IsProfessionExists, IsInstitutionExists,
            AuthorsNames, Title, ArticleUrl, MonthPublished, OtherInstitution, Url, Doi, Pubmed, OriginalAbstract,
            RelevantPointOne, RelevantPointTwo, RelevantPointThree, Citation
            FROM study_inventory_t 
            INNER JOIN reviewer_t ON study_inventory_t.ReviewerID = reviewer_t.ReviewerID
            WHERE study_inventory_t.StudyCode = '$studyCode'");

        foreach ($this->_db->getResults() as $article) {
            $reviewerID = $article->ReviewerID;
            $firstName = $article->FirstName;
            $lastName = $article->LastName;
            $searchMethod = $article->SearchMethodID;
            $documentType = $article->DocumentTypeID;
            $database = $article->DatabaseID;
            $yearPublished = $article->YearPublished;
            $yearStudyBegan = $article->YearStudyBegan;
            $yearStudyEnd = $article->YearStudyEnd;
            $isCountryExists = $article->IsCountryExists;
            $isProfessionExists = $article->IsProfessionExists;
            $isInstitutionExists = $article->IsInstitutionExists;
            $authorsNames = $article->AuthorsNames;
            $title = $article->Title;
            $articleUrl = $article->ArticleUrl;
            $monthPublished = $article->MonthPublished;
            $otherInstitution = $article->OtherInstitution;
            $url = $article->Url;
            $doi = $article->Doi;
            $pubmed = $article->Pubmed;
            $originalAbstract = $article->OriginalAbstract;
            $relevantPointOne = $article->RelevantPointOne;
            $relevantPointTwo = $article->RelevantPointTwo;
            $relevantPointThree = $article->RelevantPointThree;
            $citation = $article->Citation;
        }

        $sourceRetrievedBy = $firstName . " " . $lastName;
        $professionIDs = FormHelper::getDropDownListIDs($studyCode, $isProfessionExists, 'study_inventory_profession_t', 'profession_t', 'ProfessionID');
        $countryIDs = FormHelper::getDropDownListIDs($studyCode, $isCountryExists, 'study_inventory_country_t', 'country_t', 'CountryID');
        $institutionIDs = FormHelper::getDropDownListIDs($studyCode, $isInstitutionExists, 'study_inventory_institutions_t', 'institution_t', 'InstitutionID');

        return array(
            "StudyCode" => $studyCode,
            "ReviewerID" => $reviewerID,
            "SourceRetirevedBy" => $sourceRetrievedBy,
            "SearchMethodID" => $searchMethod,
            "DocumentType" => array($documentType),
            "DatabaseID" => array($database),
            "ArticleUrl" => $articleUrl,
            "YearPublished" => array($yearPublished),
            "YearStudyBegan" => array($yearStudyBegan),
            "YearStudyEnd" => array($yearStudyEnd),
            "CountryIDs" => $countryIDs,
            "ProfessionIDs" => $professionIDs,
            "InstitutionIDs" => $institutionIDs,
            "AuthorsNames" => $authorsNames,
            "Title" => $title,
            "MonthPublished" => array($monthPublished),
            "OtherInstitution" => FormHelper::checkIfDummyValue($otherInstitution),
            "Url" => FormHelper::checkIfDummyValue($url),
            "Doi" => FormHelper::checkIfDummyValue($doi),
            "Pubmed" => FormHelper::checkIfDummyValue($pubmed),
            "OriginalAbstract" => $originalAbstract,
            "RelevantPointOne" => FormHelper::checkIfDummyValue($relevantPointOne),
            "RelevantPointTwo" => FormHelper::checkIfDummyValue($relevantPointTwo),
            "RelevantPointThree" => FormHelper::checkIfDummyValue($relevantPointThree),
            "Citation" => $citation
        );
    }

    public function getViewInfo($studyCode) {
        $this->_db->query("SELECT study_inventory_t.StudyCode, reviewer_t.FirstName, reviewer_t.LastName, 
            search_method_t.SearchMethod, document_type_t.DocumentType, database_t.ResDatabase, 
            YearPublished, YearStudyBegan, YearStudyEnd, IsCountryExists, IsProfessionExists, IsInstitutionExists,
            AuthorsNames, Title, ArticleUrl, MonthPublished, OtherInstitution, Url, Doi, Pubmed, OriginalAbstract,
            RelevantPointOne, RelevantPointTwo, RelevantPointThree, Citation
            FROM study_inventory_t 
            INNER JOIN reviewer_t ON study_inventory_t.ReviewerID = reviewer_t.ReviewerID 
            INNER JOIN search_method_t ON study_inventory_t.SearchMethodID = search_method_t.SearchMethodID
            INNER JOIN document_type_t ON study_inventory_t.DocumentTypeID = document_type_t.DocumentTypeID
            INNER JOIN database_t ON study_inventory_t.DatabaseID = database_t.DatabaseID
            WHERE study_inventory_t.StudyCode = '$studyCode'");

        foreach ($this->_db->getResults() as $article) {
            $firstName = $article->FirstName;
            $lastName = $article->LastName;
            $searchMethod = $article->SearchMethod;
            $documentType = $article->DocumentType;
            $database = $article->ResDatabase;
            $yearPublished = $article->YearPublished;
            $yearStudyBegan = $article->YearStudyBegan;
            $yearStudyEnd = $article->YearStudyEnd;
            $isCountryExists = $article->IsCountryExists;
            $isProfessionExists = $article->IsProfessionExists;
            $isInstitutionExists = $article->IsInstitutionExists;
            $authorsNames = $article->AuthorsNames;
            $title = $article->Title;
            $articleUrl = $article->ArticleUrl;
            $monthPublished = $article->MonthPublished;
            $otherInstitution = $article->OtherInstitution;
            $url = $article->Url;
            $doi = $article->Doi;
            $pubmed = $article->Pubmed;
            $originalAbstract = $article->OriginalAbstract;
            $relevantPointOne = $article->RelevantPointOne;
            $relevantPointTwo = $article->RelevantPointTwo;
            $relevantPointThree = $article->RelevantPointThree;
            $citation = $article->Citation;
        }

        $sourceRetrievedBy = $firstName . " " . $lastName;
        $countryList = FormHelper::getDropDownListNames($studyCode, $isCountryExists, 'study_inventory_country_t', 'country_t', 'CountryID', 'Country');
        $professionList = FormHelper::getDropDownListNames($studyCode, $isProfessionExists, 'study_inventory_profession_t', 'profession_t', 'ProfessionID', 'Profession');
        $institutionList = $this->getInstitutions($studyCode, $isInstitutionExists, $otherInstitution);

        if ($monthPublished == self::NON_APPLICABLE) {
            $published = $yearPublished;
        } else {
            $published = $monthPublished . ' ' . $yearPublished;
        }

        if (!empty($yearStudyBegan) && !empty($yearStudyEnd)) {
            $studyYears = $yearStudyBegan . ' - ' . $yearStudyEnd;
        } else if (!empty($yearStudyBegan)) {
            $studyYears = $yearStudyBegan;
        } else {
            $studyYears = 'N/A';
        }

        return array(
            "StudyCode" => $studyCode,
            "ReviewerName" => $sourceRetrievedBy,
            "SearchMethod" => $searchMethod,
            "DocumentType" => $documentType,
            "Database" => $database,
            "StudyYears" => $studyYears,
            "Countries" => $countryList,
            "Professions" => $professionList,
            "Institutions" => $institutionList,
            "Authors" => $authorsNames,
            "Title" => $title,
            "ArticleUrl" => $articleUrl,
            "Published" => $published,
            "Url" => FormHelper::checkIfDummyValue($url),
            "Doi" => FormHelper::checkIfDummyValue($doi),
            "Pubmed" => FormHelper::checkIfDummyValue($pubmed),
            "OriginalAbstract" => $this->trimText($originalAbstract, 1000, true, true),
            "RelevantPointOne" => FormHelper::checkIfDummyValue($relevantPointOne),
            "RelevantPointTwo" => FormHelper::checkIfDummyValue($relevantPointTwo),
            "RelevantPointThree" => FormHelper::checkIfDummyValue($relevantPointThree),
            "Citation" => $citation
        );
    }

    public function downloadArticle($studyCode) {
        $file = self::ARTICLE_DIRECTORY . $studyCode . '.pdf';
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit();
        }
    }

    public function edit($arr = array()) {
        $dummyValueFields = array("MonthPublished", "OtherInstitution", "Url", "Doi", "Pubmed",
            "OriginalAbstract", "RelevantPointOne", "RelevantPointTwo",
            "RelevantPointThree", "Citation");
        $data = $this->fillEmptyFieldsWithDefault(self::DUMMY_FIELD, $arr, $dummyValueFields);

        $professions = $data['IsProfessionExists'];
        $institutions = $data['IsInstitutionExists'];
        $countries = $data['IsCountryExists'];

        //change to DB boolean value
        empty($data['IsCountryExists']) ?
                        $data['IsCountryExists'] = "0" :
                        $data['IsCountryExists'] = "1";
        empty($data['IsProfessionExists']) ?
                        $data['IsProfessionExists'] = "0" :
                        $data['IsProfessionExists'] = "1";
        empty($data['IsInstitutionExists']) ?
                        $data['IsInstitutionExists'] = "0" :
                        $data['IsInstitutionExists'] = "1";

        $this->_db->update('study_inventory_t', 'StudyCode', $data['StudyCode'], $data);

        if (!empty($countries)) {
            $this->_db->delete("study_inventory_country_t", array('StudyCode', '=', $data['StudyCode']));
            $this->insertMultipleTableRows("study_inventory_country_t", $data['StudyCode'], "CountryID", $countries);
        }

        if (!empty($professions)) {
            $this->_db->delete("study_inventory_profession_t", array('StudyCode', '=', $data['StudyCode']));
            $this->insertMultipleTableRows("study_inventory_profession_t", $data['StudyCode'], "ProfessionID", $professions);
        }

        if (!empty($institutions)) {
            $this->_db->delete("study_inventory_institutions_t", array('StudyCode', '=', $data['StudyCode']));
            $this->insertMultipleTableRows("study_inventory_institutions_t", $data['StudyCode'], "InstitutionID", $institutions);
        }
    }

    /**
     * Returns a list of that database fields that will be inserted into the 
     * database containing the fields that are empty and neecd to be replaced 
     * with the desired database desired field. The $data list is formatted exactly 
     * like the database therefore the fields that need to be set to the default 
     * are at the back of the table grouped together
     * @param array $data list that contains fields that need default and doesn't
     * @param array $dummyValueFields  fields to replace with default content
     * @return array $data  list that contains fields that has change to the 
     *  desired string default as well as the ones that were not supposed to
     */
    public function fillEmptyFieldsWithDefault($defaultContent, $data = array(), $dummyValueFields = array()) {
        array_reverse($data);
        for ($i = count($dummyValueFields) - 1; $i != -1; $i--) {
            if (empty($data[$dummyValueFields[$i]])) {
                $data[$dummyValueFields[$i]] = $defaultContent;
            }
        }
        return $data;
    }

    private function findReviewersWithMinArticleAssignments($reviewerIDs = array()) {
        $maxReviewer = $max = 0;
        $reviewersWithMinAssign = array();

        if (count($reviewerIDs) == 2) {
            for ($i = 0; $i < count($reviewerIDs); $i++) {
                array_push($reviewersWithMinAssign, $reviewerIDs[$i]);
            }
        } else {
            for ($i = 0; $i < count($reviewerIDs); $i++) {
                $count = $this->getMaxAssignmentCountForReviewer($reviewerIDs[$i]);

                if ($count > $max) {
                    $max = $count;
                    $maxReviewer = $reviewerIDs[$i];
                }

                if ($reviewerIDs[$i] != $maxReviewer) {
                    array_push($reviewersWithMinAssign, $reviewerIDs[$i]);
                }

                //if there is only one reviewer in the array
                if (count($reviewerIDs) == 1) {
                    array_push($reviewersWithMinAssign, $maxReviewer);
                }
            }
        }
        return $reviewersWithMinAssign;
    }

    public function getInstitutions($studyCode, $isInstitutionExists, $otherInstitutions) {
        $institutions = array();
        $institutionNames = FormHelper::getDropDownListNames($studyCode, $isInstitutionExists, 'study_inventory_institutions_t', 'institution_t', 'InstitutionID', 'Institution');
        for ($j = 0; $j < count($institutionNames); $j++) {
            array_push($institutions, $institutionNames[$j]);
        }

        if ($otherInstitutions != self::DUMMY_FIELD) {
            $otherInstitutions = explode(",", $otherInstitutions);
            for ($i = 0; $i < count($otherInstitutions); $i++) {
                array_push($institutions, $otherInstitutions[$i]);
            }
        }
        return $institutions;
    }

    private function getMaxAssignmentCountForReviewer($reviewerID) {
        $query = $this->_db->query("SELECT COUNT(ReviewerID) AS count FROM "
                . "article_process_stage_t WHERE ReviewerID = '$reviewerID'");
        foreach ($query->getResults() as $count) {
            return $count->count;
        }
    }

    public function getTeamMembersForCode($studyCode) {
        $code = explode('-', $studyCode);
        $result = $this->_db->select("team_t", array("Code", "=", $code[0]));
        $members = array();
        foreach ($result->getResults() as $member) {
            array_push($members, $member->ReviewerID);
        }
        return $members;
    }

    public function isArticleADuplicate($hash) {
        $this->_db->query("SELECT hash FROM study_inventory_t WHERE Hash = '$hash'");
        if ($this->_db->getCount() == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function insertArticleIntoFolder($newNameofArticle, $file, $folderToInsert) {
        $fileData = pathinfo(basename($_FILES["file"]["name"]));
        $file = $newNameofArticle . '.' . $fileData['extension'];

        if ($folderToInsert == self::TEMP) {
            $target_file = self::TEMP_ARTICLE_DIRECTORY . $file;
        } else {
            $target_file = self::ARTICLE_DIRECTORY . $file;
        }

        $isUploaded = move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

        if ($isUploaded == false) {
            return self::UPLOAD_FAIL;
        }
        return $file;
    }

    /**
     * Inserts content into the tables that correspond to the study inventory 
     * boolen fields. It takes array content store them into the tables 
     * specfically wherethe study code and the field are the primary keys. 
     * @table  string Table to insert content
     * @studyCode  string Code where the inventory content belongs to
     * @databaseField  string The database field where the array content should be placed
     * @arr array The valeus to be stored in the database
     */
    public function insertMultipleTableRows($table, $studyCode, $databaseField, $data = array()) {
        if (!empty($data) && is_array($data)) {
            for ($i = 0; $i < count($data); $i++) {
                $this->_db->insert($table, array(
                    "StudyCode" => $studyCode,
                    $databaseField => $data[$i]
                ));
            }
        }
    }

    private function selectReviewersToAssignArticles($reviewerIDs = array()) {
        //the number of max reviewers to assign
        if (count($reviewerIDs) < 2) {
            $maxReveiwers = 1;
        } else {
            $maxReveiwers = 2;
        }

        //if number of reviewers are only one, just select that reviewer to be
        //assigned to the article
        if (count($reviewerIDs) == 1) {
            $randReviewerIDs = array($reviewerIDs[0]);
        } else {
            $randReviewerIDs = array_rand($reviewerIDs, $maxReveiwers);
        }

        $reviewersToAssign = array();

        //if there is only one reviewer, just push it to array to assign reviewers
        if (count($reviewerIDs) == 1) {
            array_push($reviewersToAssign, $randReviewerIDs[0]);
        } else {
            for ($i = 0; $i < count($randReviewerIDs); $i++) {
                array_push($reviewersToAssign, $reviewerIDs[$randReviewerIDs[$i]]);
            }
        }
        return $reviewersToAssign;
    }

    public function trimText($input, $length, $ellipses = true, $stripHtml = true) {
        //strip tags, if desired
        if ($stripHtml) {
            $input = strip_tags($input);
        }

        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length) {
            return $input;
        }

        //find last space within length
        $lastSpace = strrpos(substr($input, 0, $length), ' ');
        $trimmedText = substr($input, 0, $lastSpace);

        //add ellipses (...)
        if ($ellipses) {
            $trimmedText .= '...';
        }

        return $trimmedText;
    }

    public function viewArticle($studyCode) {
        $file = self::ARTICLE_DIRECTORY . $studyCode . '.pdf';
        if (file_exists($file)) {
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $file . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            readfile($file);
        }
    }

    public function updateStageForArticle($studyCode, $stage) {
        $this->_db->update("article_process_stage_t", "StudyCode", $studyCode, array(
            "ArticleProcessID" => $stage
        ));
    }

    public function updateStageForReviewer($studyCode, $reviewerID, $stage) {
        $this->_db->updateMulitipleWhere("article_process_stage_t", "StudyCode", $studyCode, "ReviewerID", $reviewerID, array(
            "ArticleProcessID" => $stage
        ));
    }

}
