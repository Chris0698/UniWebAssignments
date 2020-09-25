<!DOCTYPE html>
<!--The purpose of this page is so the admin can check all the details before commiting their choice to the db -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arts and Events | Add New Event: Confirmation</title>
    <link rel="stylesheet" type="text/css" href="addNewEventPHPStyle.css">
</head>
<body>
<div class="wrapper">
    <header>
        <a href="index.html" tabindex="11" accesskey="z"><img src="artsAndEvents.png" alt="pageLogo" class="title_logo" title="Arts and Events" /></a>
        <a href="index.html" tabindex="11" accesskey="z"><img src="artsAndEventsSmallerLogo.png" alt="pageLogoSmall" class="title_logo_Small" title="Arts and Events" /></a>
        <!--This is the smaller logo that appears when the screen goes smaller.-->
        <nav>
            <ul>
                <li><a class="navi" href="index.html" tabindex="1" title="Homepage" accesskey="1">Homepage</a></li>
                <li><a class="navi" href="ViewEvents.php" tabindex="2" title="Events page" accesskey="2">View Events</a></li>
                <li><a class="navi" href="Admin.html" tabindex="3" title="Admin page" accesskey="3">Admin</a></li>
                <li><a class="navi" href="Credits.html" tabindex="4" title="Credits page" accesskey="4">Credits</a></li>           
                <li><a class="navi" href="Wireframe13.pdf" tabindex="5" title="Link to the wireframe" accesskey="5">Wireframe</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Add New Event: Confirmation</h1>
       <p>Please review what you have entered first. Once you are happy with the events click on the button below or click <a href="addNewEvent.php">here</a> to return to the previous page. </p>
        <?php
            //checks that data has been entered
            $eventName = isset( $_REQUEST['eventName']) ? $_REQUEST['eventName']: null;
            $startDate = isset( $_REQUEST['startDate']) ? $_REQUEST['startDate']: null;
            $endDate = isset( $_REQUEST['endDate']) ? $_REQUEST['endDate']: null;
            $price = isset($_REQUEST['eventPrice']) ? $_REQUEST['eventPrice']: null;
            $category = isset($_REQUEST['category']) ? $_REQUEST['category']: null;
            $venue = isset($_REQUEST['venue']) ? $_REQUEST['venue']: null;
            $description = isset($_REQUEST['eventDetails']) ? $_REQUEST['eventDetails']: null;

            if (empty($eventName)) {
            echo '<p>Event name is needed. Click <a href="addNewEvent.php"> here </a> to add the name.</p> ';
            }

		    echo "<p>The name of the new event you wish to add is called $eventName.</p>\n";
            echo "<p>The start date of the event is at : $startDate.</p>\n";
            echo "<p>The end date of the event is at : $endDate.</p>\n";
            echo "<p>The price of the event is: $price.</p>\n";
            echo "<p>The category of the event is: $category.</p>\n";
            echo "<p>The venue of the event is: $venue.</p>\n";
            echo "<p>The description of the event you entered: $description.</p>\n";

            if ($category == "c8") {
                echo "<img src='sportImage.jpg'/>";
            }

        ?>
        <form method="get" action="addNewEventSubmission.php">
            <!--Submits to the db -->
            <input type="submit"  value="Submit to the database" />
        </form>
    </main>
</div>
</body>
</html>