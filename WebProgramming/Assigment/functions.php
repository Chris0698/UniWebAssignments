<?php
//This function connects to a database using PDO
function get_connection() {
    try {
        $connection = new PDO("mysql:host=localhost;dbname=unn_w16005124",
            "unn_w16005124", "Laptop3299");
        $connection->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
        return $connection;
    } catch (Exception $e) {
        //Create a log file
        log_error($e);
        throw new Exception("Connection error ". $e->getMessage(), 0, $e);
    }
}

//Sets the session for when a user logs in
//key is the string input
//value is input to be inserted into the session array
function set_session($key, $value) {
    // Set key element = value
    $_SESSION[$key] = $value;
    return true;
}

//Returns if the is logged in
function get_session($key) {
    $return = null;
    if (isset($_SESSION[$key])) {
        $return = $_SESSION[$key];
    }//if
    return $return;
}

//Checks of the user is logged in
function check_login($key) {
    if (get_session($key)) {
        return true;
    }
    else {
        return false;
    }
}

//This function creates the "core" title and head tags of a webpage
//PageTitle is the text between the title tags
//Stylesheet is the css sheet, can be null
function get_core_tags($pageTitle, $stylesheet = null) {
    $coreTags = <<<CORETAGS
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>$pageTitle</title>
    <link rel="stylesheet" type="text/css" href="$stylesheet.css"/>  <!--My stylesheet-->
    <link rel="stylesheet" type="text/css" href="JQuery/jquery-ui-1.12.1/jquery-ui.css"/> <!--Stylesheet from JQueury website for automcomplete-->    
</head>\n
CORETAGS;
    $coreTags.="\n";
    return $coreTags;
}

//Gets the title bar including the images and web links
function get_title_header( array $links, $class) {
    $webLink = null; //Create a web link variable

    //For each element in the link array iterate through them assigmnt each link a value from what called
    foreach ($links as  $link =>$value) {
        $webLink.="<li><a href='$link' class='$class'>$value</a></li>\n";
    }

    $titleHeader = <<<TITLEHEARE
    <div class="banner">
        <a href='index.php'><img src='titleBarLogo.png' id='titleBarLogo' alt='Title Logo'/></a>
        <nav>
            <ul>
                <!--Add the web links from the foreach loop.-->
                $webLink
            </ul>
        </nav>
    </div>\n
TITLEHEARE;
    $titleHeader.="\n";
    return $titleHeader;
}



//Gets the login form
function get_login_form() {
    //checks to make sure the user isn't already logged in
    echo "\t<div class='loginForm'>\n";
    if (!check_login('logged-in') && !check_login('username')) {
        echo "\t\t<form action='loginProcess.php' method='post' id='loginForm'>
            <fieldset>
                <legend>Log In</legend>
                <label>Username:
                    <input type='text' name='username' placeholder='Enter Username' accesskey='5' id='userInput'/>
                </label>
                
                <label>Password:
                    <input type='password' name='password' placeholder='Enter Password' accesskey='6' id='passwordInput'/>
                </label>
                <input type='submit' value='log In'/>";
                //Gets any log in errors and displays them in the form
                get_login_errors();
            echo "</fieldset>
        </form>\n";
    }
    else {
        //user already logged in so they get the log out link instead of the form.
        echo "\t<a href='logOutProcess.php' id='logOutLink' accesskey='5'>Log Out</a>\n";
    }
    echo "\t</div>\n";
}

//gets any log in errors and displays then
function get_login_errors() {
    $loginErrors = isset($_SESSION['logInErrors']) ? $_SESSION['logInErrors']:null;
    if ($loginErrors) {
        foreach ($loginErrors as $error) {
            echo "<p id='errorMessagesLogin'>*$error</p>";
        }
    }
}

//Makes and returns a footer for a webpage
function get_footer($footerText) {
    $footer = <<<FOOTER
    <footer>
        <p>$footerText</p>
    </footer>
FOOTER;
    $footer .="\n";
    return $footer;
}

/* A global exception handler that will fire if no catch block is found
* @param $e
*/
function exceptionHandler ($e) {
    echo "<p><strong>Problem </strong>" . $e->getMessage() .
        " in file " . $e->getFile() .
        ", on line " . $e->getLine() . "</p>";
}

set_exception_handler('exceptionHandler');

//Error handle
function errorHandler ($errno, $errstr, $errfile, $errline) {
    if(!(error_reporting() & $errno)) {
        return;
    }
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler('errorHandler');


//Creates an error log. param $e is the exception triggered
function log_error($e) {
    $fileHandle = fopen("error_log_file.log", "ab");
    $date = date('D M j G:i:s T Y');
    fwrite($fileHandle, "$date");
    fwrite($fileHandle,$e->getMessage().PHP_EOL);
    fclose($fileHandle);
}
