<?php
require_once ("../functions.php");  //Gets the functions
require_once ("../setEnv.php");     //Get the development enviroment

//Gets the ISBN from the URL being called
$bookISBN = filter_has_var(INPUT_GET,'bookISBN')? $_GET['bookISBN'] : null;

try {
    $dbConn = get_connection();

    //SQL for full book details
    $sql = "SELECT bookISBN, bookTitle, bookYear, bookPrice, catDesc, pubName
            FROM nbc_books
            INNER JOIN nbc_category
            ON nbc_books.catID = nbc_category.catID
            INNER JOIN nbc_publisher
            ON nbc_books.pubID = nbc_publisher.pubID
            WHERE bookISBN = :bookISBN";

    //Prepare the statement and execute it
    $stmt = $dbConn->prepare($sql);
    $stmt->execute(array(':bookISBN' => $bookISBN));

    $rowObj = $stmt->fetchObject();

    //gets the values from the request stream
    $bookISBN = $rowObj->bookISBN;
    $bookTitle = $rowObj->bookTitle;
    $bookYear = $rowObj->bookYear;
    $catDesc = $rowObj->catDesc;
    $publisher = $rowObj->pubName;
    $bookPrice = $rowObj->bookPrice;

    //Remove any tags that might of been part of what has been selected.
    $bookTitle = filter_var($bookTitle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $bookYear = filter_var($bookYear, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $catDesc = filter_var($catDesc, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $publisher = filter_var($publisher, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $bookPrice = filter_var($bookPrice, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    //Div will be shown to the user with the data on the index page
    echo "<div>
            <p>Book ISBN: $bookISBN</p>
            <p>Book Title: $bookTitle</p>
            <p>Book Year: $bookYear</p>
            <p>Book Price: $bookPrice</p>
            <p>Category: $catDesc</p>
            <p>Publisher: $publisher</p>
          </div>";

}
catch (Exception $e) {
    //If envirment is set to development
    if(defined("DEVELOPMENT") && DEVELOPMENT == true) {
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    }
    else {
        echo "<p>Sorry something went wrong. Please try again later</p>";
        error_log($e);
    }
}