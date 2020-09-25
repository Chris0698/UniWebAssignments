<?php
ini_set("session.save_path", "/home/unn_w16005124/sessionData");
session_start();
?>
<?php
    require_once ("functions.php");
    get_core_tags("Northumbria book Company | logout", "stylesheet")
?>
<body>
<?php
    echo get_title_header(array("index.php" => "Home",
                                "orderBooksForm.php" => "Order Books",
                                "chooseBookList.php" => "Admin",
                                "credits.php" => "Credits"), "navLinks");
?>
    <!--Displays a message to the user-->
    <main>
        <header>
            <h1>Log Out</h1>
            <p>Please wait while we log you out.</p>
        </header>
    </main>

<?php

    //unset the global variable
    $_SESSION = array();

    //Destroy the session
    session_destroy();

    //Go back to the previous page were the log button was clicked on
    echo "<script type='text/javascript'>
              'use strict';
              window.history.back();     
          </script>";

    echo get_footer("Copyright: Northumbria Book 2017.")

?>
</body>
</html>