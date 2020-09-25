<?php
require_once("../functions.php");
require_once("../setEnv.php");

//Gets the value from the autocomplete book since it uses a term
$term = isset($_REQUEST['term']) ? $_REQUEST['term'] : null;

//the search box is empty
if(empty($term)) {
    echo "";
    return;
}

try {
    $dbConn = get_connection();

    //SQL for books to be selected. Selected book title twice so the book would be displayed
    //in the box not the bookISBN or anything else
    $sql = "SELECT bookISBN AS 'value', bookTitle AS 'label'
            FROM nbc_books 
            WHERE bookTitle LIKE :term";

    $stmt = $dbConn->prepare($sql);

    // term is a wildcard
    $stmt->execute(array(':term' => "%{$term}%"));

    //fetch all returns an array fetch_assoc return with array index by coloumn name
    $array = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Change the header
    header('Content-Type: application/json');

    //Display the data as JSON
    echo json_encode($array);
}
catch (Exception $e) {
    if (defined("DEVELOPMENT")&& DEVELOPMENT == true) {
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    }
    else {
        echo "<p>Sorry there been an error, try again later.</p>";
        log_error($e);
    }
}
