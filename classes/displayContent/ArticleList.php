<?php

require_once 'classes/utility/Database.php';

class ArticleList {
    
     public static function displayArticles() {
        $db = Database::getInstance();
        $db->query("SELECT StudyCode, Title, AuthorsNames FROM study_inventory_t");
        ?>
        <div id="wrapper_article_list">
            <div id="article_list">
                <h1>Articles</h1>
                <?php
                if ($db->getCount() == 0) {
                    echo '<h1>No Articles submitted</h1>';
                } else {
                    ?>          
                    <form class="form-inline"  method="post" action="studyinventory.php?type=searchArticles">
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" id="searchArticle" name="searchArticle" placeholder="Search Title or Author Name" required="required">
                            </div>
                            <input id="articleSearchBtn" type="submit" name="submit" value="Search" />
                        </div>
                    </form>
                    <br>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="success">
                                <th class="text-center">StudyCode</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Author Names</th>
                                <th class="text-center">Article</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($db->getResults() as $article) {
                                ?>
                                <tr>
                                    <td style="vertical-align:middle" class="text-center"><?php echo $article->StudyCode; ?></td>
                                    <td style="vertical-align:middle" class="text-center"><?php echo $article->Title; ?></td>
                                    <td style="vertical-align:middle" class="text-center"><?php echo $article->AuthorsNames; ?></td>
                                    <td style="vertical-align:middle" class="text-center"><a href="studyinventory.php?type=viewArticle&StudyCode=<?php echo $article->StudyCode; ?>"><span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span></a></td>
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
    
        public static function displaySearchArticles() {
        $db = Database::getInstance();
        ?>
        <div id="wrapper_article_list">
            <div id="article_list">
                <h1>Articles</h1>
                <form class="form-inline"  method="post" action="studyinventory.php?type=searchArticles">
                    <div class="form-group">
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" id="searchArticle" name="searchArticle" placeholder="Search Title or Author Name" required="required">
                        </div>
                        <input id="articleSearchBtn" type="submit" name="submit" value="Search" />
                    </div>
                </form>
                <br>
                <?php
                if (Input::exists('post')) {
                    $searchArticle = Input::get('searchArticle');
                    $searchArticle = strip_tags($searchArticle);
                    $searchArticle = trim($searchArticle);

                    echo "<h2>Search Results for '" . $searchArticle . "'</h2>";

                    $db->query("SELECT StudyCode, Title, AuthorsNames FROM study_inventory_t WHERE Title LIKE '%$searchArticle%' OR AuthorsNames LIKE '%$searchArticle%'");
                    if ($db->getCount() == 0) {
                        ?>
                        <br/>
                        <h2>No Results</h2>
                        <?php
                    } else {
                        ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr class="success">
                                    <th class="text-center">StudyCode</th>
                                    <th class="text-center">Title</th>
                                    <th class="text-center">Author Names</th>
                                    <th class="text-center">Article</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($db->getResults() as $article) {
                                    ?>
                                    <tr>
                                        <td style = "vertical-align:middle" class = "text-center"> <?php echo $article->StudyCode; ?></td>
                                        <td style="vertical-align:middle" class="text-center"><?php echo $article->Title; ?></td>
                                        <td style="vertical-align:middle" class="text-center"><?php echo $article->AuthorsNames; ?></td>
                                        <td style="vertical-align:middle" class="text-center"><a href="studyinventory.php?type=viewArticle&StudyCode=' <?php echo $article->StudyCode; ?> '"><span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span></a></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }
}
