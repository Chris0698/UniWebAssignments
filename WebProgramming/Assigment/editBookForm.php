<?php
ini_set("session.save_path", "/home/unn_w16005124/sessionData");
session_start();
?>
<?php
    //Import the files and get the get core tags, login form and title header
    require_once ("functions.php");
    require_once ("setEnv.php");
    echo get_core_tags("Northumbria book Company |Edit Book Form", "stylesheet");

    echo "\n\t<body>\n";

    get_login_form();
    echo get_title_header(array("index.php" => "Home",
                                "orderBooksForm.php" => "Order Books",
                                "chooseBookList.php" => "Admin",
                                "credits.php" => "Credits"), "navLinks");
?>
    <main>
        <h1>Edit Book Form</h1>
        <?php
        //Checks if the user is logged in. If so display the rest of the page, if not it shows an error saying user doesn't have
        //permission to view this page.
        if (check_login('logged-in') && check_login('username'))
        {
            echo "<!--Overview about the form.-->
                  <p>Fill out the form completely and click on submit to edit a current record. There are accesskeys enabled. Starting with
                    ALT + 7 for Book title, ALT + 8 for book year etc with category being ALT +0 and price being ALT + -.</p> 
                  <p>If there are any errors you will be redirected here and the error(s) will be displayed
                    and the original pre-edited value will be shown instead.</p>";

            //Checks to see if there are any errors found from the editBookScript
            $errors = isset($_SESSION['bookFormErrors']) ? $_SESSION['bookFormErrors'] : null;
            //Checks to see if there are any errors and if $error is an array
            if (!empty($errors) && is_array($errors))
            {
                foreach($errors as $error) {
                    //echo each error to the web browser
                    echo "<p id='errorMessages'>$error</p>";
                }

                //Empty the array so errors don't appear when clicking on a new title
                unset($_SESSION['bookFormErrors']) ;
            }
            try {
                //Gets the bookISBN
                $bookISBN = filter_has_var(INPUT_GET,'bookISBN')? $_GET['bookISBN'] : null;

                $db = get_connection();

                //SQL to select the rest of the book properties.
                $sql = "SELECT bookISBN, bookTitle, bookYear, bookPrice,  catID, pubID
                        FROM nbc_books
                        WHERE bookISBN = :bookISBN";

                //Prepare the statement and execute it
                $stmt = $db->prepare($sql);
                $stmt->execute(array(':bookISBN' => $bookISBN));

                $rowObj = $stmt->fetchObject();

                //get the data
                $bookISBN = $rowObj->bookISBN;
                $bookTitle = $rowObj->bookTitle;
                $bookYear = $rowObj->bookYear;
                $bookPrice = $rowObj->bookPrice;
                $catID = $rowObj->catID;
                $pubID = $rowObj->pubID;

                //Remove any tags that might of been part of what has been selected by the sql.
                $bookTitle = filter_var($bookTitle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $bookYear = filter_var($bookYear, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $catID = filter_var($catID, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $pubID = filter_var($pubID, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $bookPrice = filter_var($bookPrice, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

                echo "<form method='get' action='editBookScript.php'>
                      <fieldset>
                          <legend>Book Details</legend>
                          <!--Hidden since we don't want tot display the ISBN number to the user-->
                          <input type='hidden' value='$bookISBN' name='bookISBN'/>
                          
                          <label>Book Title:
                              <input type='text' value='$bookTitle' name='bookTitle' accesskey='7'/>
                          </label>
                          
                          <label>Book Year:
                              <input type='number' value='$bookYear' name='bookYear' accesskey='8' />
                          </label> 
                          
                          <label>Book Publisher:
                              <select name='bookPublisher' accesskey='9'>\n";
                                    try{
                                        //SQL to make a list from the DB.
                                        $pubSQL = "SELECT pubID, pubName
                                                   FROM nbc_publisher
                                                   WHERE pubID = :pubID";
                                        $stmt = $db->prepare($pubSQL);
                                        $stmt->execute(array(':pubID'=>$pubID));
                                        $rowObj = $stmt->fetchObject();
                                        $pubID = $rowObj->pubID;
                                        $pubName = $rowObj->pubName;

                                        $pubID = filter_var($pubID, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                                        $pubName = filter_var($pubName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

                                        echo "<option value='$pubID'>$pubName</option>\n";

                                        //Gets rest of the publishers names from the DB
                                        $pubSQL2 = "SELECT DISTINCT pubID, pubName
                                                    FROM nbc_publisher
                                                    ORDER BY pubName";
                                        $pubQuery2 = $db->query($pubSQL2);
                                        while ($rowObj = $pubQuery2->fetchObject())
                                        {
                                            $pubID2 = $rowObj->pubID;
                                            $pubName2 = $rowObj->pubName;

                                            $pubID2 = filter_var($pubID2, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                                            $pubName2 = filter_var($pubName2, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

                                            if ($pubID != $pubID2)
                                            {
                                                echo "<option value='$pubID2'>$pubName2</option>\n";
                                            }
                                        }
                                    }
                                    catch (Exception $e) {
                                        if (defined("DEVELOPMENT")&& DEVELOPMENT == true) {
                                            echo "<p>Query failed: ".$e->getMessage()."</p>\n";
                                        }
                                        else {
                                            echo "<p>There was an error please try again later.</p>";

                                            //Creates an error log
                                            log_error($e);
                                        }
                                    }
                             echo"</select>
                          </label>
                          
                          <label>Book Category:
                               <select name='bookCategory' accesskey='0'>\n";
                                    try
                                    {
                                       //Gets selected category.
                                        $catSQL="SELECT catID, catDesc 
                                                  FROM nbc_category
                                                  WHERE catID = :catID";

                                        $stmt = $db->prepare($catSQL);
                                        $stmt->execute(array(':catID'=>$catID));
                                        $rowObj = $stmt->fetchObject();
                                        $catID = $rowObj->catID;
                                        $catDesc = $rowObj->catDesc;

                                        //Filter out any tags that might of been selected
                                        $catID = filter_var($catID, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                                        $catDesc = filter_var($catDesc, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

                                        echo "<option value='$catID'>$catDesc</option>\n";

                                        //Gets the rest of the data
                                        $catSQL2 = "SELECT DISTINCT catID, catDesc 
                                                    FROM nbc_category
                                                    ORDER BY catDesc";
                                        $catQuery2 = $db->query($catSQL2);

                                        while ($rowObj = $catQuery2->fetchObject())
                                        {
                                            $catID2 = $rowObj->catID;
                                            $catDesc2 = $rowObj->catDesc;

                                            $catID2 = filter_var($catID2, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                                            $catDesc2 = filter_var($catDesc2, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

                                            //if the ID doesn't match to the one being edited then add it to the option select list
                                            if ($catID != $catID2)
                                            {
                                                echo "<option value='$catID2'>$catDesc2</option>\n";
                                            }
                                        }
                                    }
                                    catch (Exception $e) {
                                        if (defined("DEVELOPMENT")&& DEVELOPMENT == true) {
                                            echo "<p>Query failed: ".$e->getMessage()."</p>\n";
                                        }
                                        else {
                                            echo "<p>There was an error please try again later.</p>";

                                            //Creates an error log
                                            log_error($e);
                                        }
                                    }
                          echo"</select>
                          </label>
                                                        
                          <label>Book Price:
                               <input type='number' step='0.01' name='bookPrice' value='$bookPrice' accesskey='-'/>
                          </label>
                          
                      </fieldset>
                      <input type='submit' value='Submit' id='submitButton' accesskey='+'/>
                </form>\n";
            }
            catch (Exception $e){
                if (defined("DEVELOPMENT")&& DEVELOPMENT == true) {
                    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
                }
                else {
                    echo "<p>There was an error please try again later.</p>";

                    //Creates an error log
                    log_error($e);
                }
            }
        }
        else
        {
            //user not logged on and shouldnt see this page
            echo "<p>You do not have permission to view this page!</p>";
        }
        ?>
    </main>
<?php
    echo get_footer("Copyright: Northumbria Book Company 2017");
?>
</body>
</html>