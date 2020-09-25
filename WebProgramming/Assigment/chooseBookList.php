<?php
ini_set("session.save_path", "/home/unn_w16005124/sessionData");
session_start();
?>
<?php
    //gets the function file
    require_once ("functions.php");
    //gets the development enviroment file
    require_once ("setEnv.php");
    //Import the main core tags
    echo get_core_tags("Northumbria book Company |Choose Book List", "stylesheet");
    echo "\n\t<body>\n";
    //Gets the login form
    get_login_form();
    // gets the title header
    echo get_title_header(array("index.php" => "Home",
                                "orderBooksForm.php" => "Order Books",
                                "chooseBookList.php" => "Admin",
                                "credits.php" => "Credits"), "navLinks");
?>
    <!--HTML and opening body tag are inside get_title_header() function-->
    <main>
        <header>
            <h1>Select Book to Edit</h1>

        <?php
        //Checks to see if the user is logged on with the user name using the check_login function
        if (check_login('logged-in') && check_login('username'))
        {
            //User is logged on so they have access to restroicted content
            echo "\t<p>Click on a book title in the list to select one for editing.</p>\n";
            echo "\t\t</header>\n";
            //Adds title headers to each coloumn
            echo "\t\t<div class='item'>\n
                    <span class='bookTitleHeader'>Book Title</span>
                    <span class='bookYearHeader'>Book Year</span>
                    <span class='catDescHeader'>Category</span>
                    <span class='bookPriceHeader'>Price</span>
             </div>\n";

            try {
                //Include the getConnection function for the database connection
                $db = get_connection();

                $sql = "SELECT bookISBN, bookTitle, bookYear, bookPrice, catDesc
                        FROM nbc_books
                        INNER JOIN nbc_category
                        ON nbc_books.catID = nbc_category.catID
                        ORDER BY bookTitle";
                $query = $db->query($sql);

                //Iterate over the results of the query
                while ($rowObj = $query->fetchObject())
                {
                    $bookISBN = $rowObj->bookISBN;
                    $bookTitle = $rowObj->bookTitle;
                    $bookYear = $rowObj->bookYear;
                    $catDesc = $rowObj->catDesc;
                    $bookPrice = $rowObj->bookPrice;

                    //Remove any tags that might of been part of what has been selected.
                    $bookTitle = filter_var($bookTitle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    $bookYear = filter_var($bookYear, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    $catDesc = filter_var($catDesc, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    $bookPrice = filter_var($bookPrice, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

                    //Display the books selected
                    echo "\t\t<div class='item'>
                              <span class='bookTitle'>
                                  <a href='editBookForm.php?bookISBN=$bookISBN' 
                                  title='Click on edit this book.'
                                  class='bookTitleLinks'>$bookTitle</a>
                              </span>
                              <span class='bookYear'>$bookYear</span>                 
                              <span class='catDesc'>$catDesc</span>
                              <span class='bookPrice'>$bookPrice</span>
                        </div>\n";
                }
            }
            catch (Exception $e){
                //If enviroment is set to development
                if(defined("DEVELOPMENT") && DEVELOPMENT == true) {
                    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
                }
                else {
                    //If false create an log error
                    echo "<p>Sorry something went wrong. Please try again later</p>";
                    log_error($e);
                }
            }
        }
        else {
            //Not logged i so they dont get access to restricted content
            echo "\t<p>You do not have permission to view this page!</p>\n";

            //closing header tag needed since the original closing one will be displayed if not logged in
            echo "\t\t</header>\n";
        }
        ?>
    </main>
<?php
    echo get_footer("Copyright: Northumbria Book Company 2017. ");
?>
</body>
</html>