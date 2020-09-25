<?php
//Start the session
ini_set("session.save_path", "/home/unn_w16005124/sessionData");
session_start();
?>
<?php
    //Gets the files and create the nav links
    require_once ("functions.php");
    require_once ("setEnv.php");
    echo get_core_tags("Northumbria book Company |Edit Book Submission", "stylesheet")
?>
<body>
<?php
    get_login_form();

    //gets the title header with the nav links and l+ogo from the functions file
    echo get_title_header(array("index.php" => "Home",
                                "orderBooksForm.php" => "Order Books",
                                "chooseBookList.php" => "Admin",
                                "credits.php" => "Credits"), "navLinks");
?>
    <main>
        <h1>Edit Book Submission</h1>
        <?php
        //Checks if logged in.
        if (check_login('logged-in') && check_login('username'))
        {
            //make a list that would e populated by the result of validate_for
            list($input, $errors) = validate_form();
            //If $errors is not null there is errors
            if ($errors) {
                show_errors($errors, $input);
            } else {
                //No errors so process the form
                process_form($input);
            }
        }
        else
        {
            //user not logged in
            echo "<p>You do not have permission to view this page!</p>\n";
        }

        //Function checks the form for blank space and checks all the input is legal
        function validate_form()
        {
            //filter_has_var() gets the variable from the arrays
            //trim() removes any blank space
            $input = array();
            $errors = array();

            $input['bookISBN'] = filter_has_var(INPUT_GET, 'bookISBN') ? $_GET['bookISBN'] : null;

            $input['bookTitle'] = filter_has_var(INPUT_GET, 'bookTitle') ? $_GET['bookTitle'] : null;
            $input['bookTitle'] = trim($input['bookTitle']);
            $input['bookTitle'] = filter_var($input['bookTitle'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

            $input['bookYear'] = filter_has_var(INPUT_GET, 'bookYear') ? $_GET['bookYear'] : null;
            $input['bookYear'] = trim($input['bookYear']);

            $input['bookPublisher'] = filter_has_var(INPUT_GET, 'bookPublisher') ? $_GET['bookPublisher'] : null;
            $input['bookPublisher'] = trim($input['bookPublisher']);

            $input['bookCategory'] = filter_has_var(INPUT_GET, 'bookCategory') ? $_GET['bookCategory'] : null;
            $input['bookCategory'] = trim($input['bookCategory']);

            $input['bookPrice'] = filter_has_var(INPUT_GET, 'bookPrice') ? $_GET['bookPrice'] : null;
            $input['bookPrice'] = trim($input['bookPrice']);

            //create a variable and add to the array from whats in the input array
            $categories = array($input['bookCategory']);
            $publishers = array($input['bookPublisher']);

            //Checks to make sure the data not empty. If empty add to to the errors array
            if (empty($input['bookISBN']))
            {
                $errors[] = "A book has not been selected.";
            }
            if (empty($input['bookTitle']))
            {
                $errors[] = "The Book Title is empty";
            }
            if (empty($input['bookYear']))
            {
                $errors[] = "The year is empty";
            }
            if (empty($input['bookPublisher']))
            {
                $errors[] = "The book publisher is empty";
            }
            if (empty($input['bookCategory']))
            {
                $errors[] = "The book category is empty";
            }
            if (empty($input['bookPrice']))
            {
                $errors[] = "The price must have .00 on the end or the price isn't a float";
            }
            //Compares the bookCategory in the input array to the cateogroes array t make sure they match with each
            if (!in_array($input['bookCategory'], $categories))
            {
                $errors[] = "The category selected doesn't exist";
            }
            if (!in_array($input['bookPublisher'], $publishers))
            {
                $errors[] = "The publisher doesn't exist";
            }
            //Checks the price is a floating point number
            if (!filter_var($input['bookPrice'], FILTER_VALIDATE_FLOAT))
            {
                $errors[] = "The price is not a float";
            }
            if(!filter_var($input['bookYear'], FILTER_VALIDATE_INT))
            {
                $errors[] = "The year should be an int";
            }

            //returns the array
            return array($input, $errors);
        }

        function show_errors($errors, $input)
        {
            //Put the errors into an array and go back to the page
            $_SESSION['bookFormErrors'] = $errors;
            $bookISBN = $input['bookISBN'];
            header("location:http://unn-w16005124.newnumyspace.co.uk/_secondYear/assigment/editBookForm.php?bookISBN=$bookISBN");
        }

        function process_form($input)
        {
            try {
                $bookISBN = $input['bookISBN'];
                $bookTitle = $input['bookTitle'];
                $bookYear = $input['bookYear'];
                $bookPublisher = $input['bookPublisher'];
                $bookCategory = $input['bookCategory'];
                $bookPrice = $input['bookPrice'];


                //Get the DB connection from the functions file
                $db = get_connection();

                //SQL to update the book
                $updateSQL = "UPDATE nbc_books SET
                        bookTitle = :bookTitle,
                        bookYear = :bookYear,
                        catID = :bookCategory,
                        pubID = :bookPublisher,
                        bookPrice = :bookPrice
                        WHERE bookISBN = :bookISBN
                        ";

                //prepare and execute the SQL
                $stmt = $db->prepare($updateSQL);
                $stmt->execute(array(':bookTitle' => $bookTitle,
                        ':bookYear' =>$bookYear,
                        ':bookCategory' =>$bookCategory,
                        ':bookPublisher' =>$bookPublisher,
                        ':bookPrice' =>$bookPrice,
                        ':bookISBN' =>$bookISBN)
                );

                $catID = $input['bookCategory'];
                $pubID = $input['bookPublisher'];

                //SQL to get the catDesc since we don't want to display PK/FK
                $catSQL = "SELECT catDesc FROM nbc_category WHERE catID = :catID";
                $stmt = $db->prepare($catSQL);
                $stmt->execute(array(':catID' =>$catID));
                $rowObj = $stmt->fetchObject();

                $catDesc = $rowObj->catDesc;
                $catDesc = filter_var($catDesc, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

                //SQL to get the pubName or the invoice will display the ID instead
                $pubSQL = "SELECT pubName FROM nbc_publisher WHERE pubID = :pubID";

                //Prepare the statement and execute
                $stmt = $db->prepare($pubSQL);
                $stmt->execute(array(':pubID' =>$pubID));

                //fetch the objects and make the data safe
                $rowObj = $stmt->fetchObject();
                $publisher = $rowObj->pubName;
                $publisher = filter_var($publisher, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

                //Display the invoice to the user
                echo "<p>Update successful. The book you edited now reads: </p>
                          <p>Book title: $bookTitle</p>
                          <p>The book year is: $bookYear</p>
                          <p>The publisher is: $publisher</p>
                          <p>The category is: $catDesc</p>
                          <p>The price is: $bookPrice</p>
                          <p>To go back to the book list to edit click <a href='chooseBookList.php'>here</a>.</p>
                            ";
            }
            catch (Exception $e){
                if (defined("DEVELOPMENT") && DEVELOPMENT == true) {
                    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
                }
                else {
                    echo "<p>An error occurred, please try again later.</p>";
                    log_error($e);
                }
            }
        }
        ?>
    </main>
<?php
   echo get_footer("copyright: Northumbria Book Company 2017");
?>
</body>
</html>