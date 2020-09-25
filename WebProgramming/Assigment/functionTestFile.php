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
        /* We should log the error to a file so the developer can look at any logs. However, for now we won't */
        throw new Exception("Connection error ". $e->getMessage(), 0, $e);
    }
}

//Sets the session for when a user logs in
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
function get_core_tags($page, $stylesheet = null) {
    $coreTags = <<<CORETAGS
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>$page</title>
        <link rel="stylesheet" type="text/css" href="$stylesheet.css"/>
    </head>\n
CORETAGS;
    return $coreTags;
}

//Gets the title bar including the images and web links
function get_title_header() {
    $titleHeader = <<<TITLEHEARE
    <div class="banner">
        <a href='index.php'><img src='titleBarLogo.png' id='titleBarLogo' alt='Title Logo'/></a>
        <nav>
            <ul>
                <li><a href='index.php' class='navLinks' accesskey='1' title='Click to go to the homepage'>Home</a></li>
                <li><a href='orderBooksForm.php' class='navLinks' accesskey='2' title='Click to buy books'>Buy Books</a></li>
                <li><a href='chooseBookList.php' class='navLinks' accesskey='3' title='Click to do Admin stuff'>Admin</a></li>
                <li><a href='credits.php' class='navLinks' accesskey='4' title='Click to see credits'>Credits</a></li>
            </ul>
        </nav>
    </div>\n
TITLEHEARE;
    return $titleHeader;
}

//gets a login form so the user can log in
function get_login_form() {
    echo "\t\t<div id='loginForm'>\n";
    if (!check_login('logged-in') && !check_login('username')) {
        echo "\t\t\t<form action='loginProcess.php' method='post'>
            \t<fieldset>
                \t<legend>Log In</legend>
                \t<label>Username
                    \t<input type='text' name='username' placeholder='Enter Username' accesskey='1' id='userInput'/>
                \t</label>
                
                \t<label>Password
                    \t<input type='password' name='password' placeholder='Enter Password' accesskey='2' id='passwordInput'/>
                \t</label>
                \t<input type='submit' value='log In'/>\n";
        get_login_errors();
        echo "
            </fieldset>
        </form>";

    }
    else {
        echo "\t<a href='logOutProcess.php' id='logOutLink'>Log Out</a>\n";
    }
    echo "\t</div>\n";
}


function get_login_errors() {
    $loginErrors = isset($_SESSION['logInErrors']) ? $_SESSION['logInErrors']:null;
    if ($loginErrors) {
        foreach ($loginErrors as $error) {
            echo "\t\t\t\t\t<p id='errorMessagesLogin'>*$error</p>\n";
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



function errorHandler ($errno, $errstr, $errfile, $errline) {
    if(!(error_reporting() & $errno)) {
        return;
    }
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler('errorHandler');


//Creates an error log
function log_error($e) {
    $fileHandle = fopen("error_log_file.log", "ab");
    $date = date('D M j G:i:s T Y');
    fwrite($fileHandle, "$date", "\n");
    fwrite($fileHandle, "$e->getMessage()");
    fclose($fileHandle);
}
