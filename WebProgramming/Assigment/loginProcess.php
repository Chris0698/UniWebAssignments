<?php
ini_set("session.save_path", "/home/unn_w16005124/sessionData");
session_start();
?>
<?php
    //include the files
    require_once ("functions.php");
    require_once ("setEnv.php");
    echo get_core_tags("Northumbria book Company |Login", "stylesheet");
?>
<body>
<?php
    echo get_title_header(array("index.php" => "Home",
                                "orderBooksForm.php" => "Order Books",
                                "chooseBookList.php" => "Admin",
                                "credits.php" => "Credits"), "navLinks");
?>
    <main>
        <header>
            <h1>Log On</h1>
            <p>Please wait while we try to log you on.</p>
        </header>

        <?php
        //gets the result of the login function. If errors dieplay the errors. I
        //If good then the code in the else will execute
        list($input, $errors) = validate_logon();
        if ($errors) {
            show_errors($errors);
        }
        else {
            //Set the session so the user is logged-in then re-dirceted to the choose list form
            set_session('username', $input['username']);
            set_session('logged-in', 'true');

            echo "<!--Go back to previuos page whre the login attempt was.-->
            <script type='text/javascript'>
                'use strict';
                window.history.back();
            </script>";
        }

        //Checks the form to make sure entered details are valid.
        function validate_logon() {
            $input = array();
            $errors = array();

            $input['username'] = filter_has_var(INPUT_POST,'username') ? $_POST['username']:null;
            $input['password'] = filter_has_var(INPUT_POST, 'password') ? $_POST['password'] : null;

            $input['username'] = trim($input['username']);
            $input['password'] = trim($input['password']);

            if (empty($input['username']))
            {
                $errors[] = "the username is empty";
            }
            if (empty($input['password']))
            {
                $errors[] = "The password is empty";
            }

            try {
                //Get connection
                $db = get_connection();

                //Select the user and their password hash
                $sql = "SELECT username, passwordHash FROM nbc_users WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(':username'=>$input['username']));
                $rowObj = $stmt->fetchObject();
                if ($rowObj)
                {
                    $passwordHash = $rowObj->passwordHash;

                    //If the password hash doesn't match then add to the error array
                    if (!password_verify($input['password'], $passwordHash))
                    {
                        $errors[]= "Incorrect password";
                    }
                }
                else
                {
                    //If the user clicked on log in with entering anything an incorrect login details wont be displayed
                    //with the username and password empty error message. This kind of felt pointless to have another
                    //message shown at the same time
                    if (! empty($input['username'] && ! empty($input['password']))) {
                        $errors[] = "Incorrect Login details";
                    }
                }
            }
            catch (Exception $e){
                if (defined("DEVELOPMENT")&& DEVELOPMENT == true) {
                    echo "<p>Query failed: ".$e->getMessage()."</p>\n";
                }
                else {
                    echo "<p>Sorry there been an error, try again later.</p>";
                    log_error($e);
                }

            }

            //Returns the array
            return array($input, $errors);
        }

        //Go back to the previous page
        function show_errors($errors)
        {
            $_SESSION['logInErrors'] = $errors;
            echo "<script type='text/javascript'>
                       'use strict';
                        window.history.back();         
                  </script>";
        }
        ?>
    </main>

<?php
    echo get_footer("Copyright: Northumbria Book Company 2017");
?>
</body>
</html>