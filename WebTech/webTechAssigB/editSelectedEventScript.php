<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Art and Events | Edit Selected Event</title>
    <meta name = "viewport" content="width=device-width, maximum-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="editSelectedEventSubmissionStyle.css"/>
</head>
<body>
<div class="wrapper">
    <header>
        <a href="index.html" tabindex="1" accesskey="1"><img src="artsAndEvents.png" alt="pageLogo" class="title_logo" title="Arts and Events"/></a>
        <a href="index.html" tabindex="1" accesskey="1"><img src="artsAndEventsSmallerLogo.png" alt="pageLogo" class="title_logo_Small" title="Arts and Events"/></a>
        <nav>
            <ul>
                <!--Links to the other pages -->
                <li><a class="navi" href="index.html" tabindex="2" title="Homepage" accesskey="2">Homepage</a></li>
                <li><a class="navi" href="viewEvents.php" tabindex="3" title="Events page" accesskey="3">View Events</a></li>
                <li><a class="navi" href="admin.html" tabindex="4" title="Admin page" accesskey="4">Admin</a></li>
                <li><a class="navi" href="Credits.html" tabindex="5" title="Credits page" accesskey="5">Credits</a></li>
                <li><a class="navi" href="Wireframe13.pdf" tabindex="6" title="Link to the wireframe" accesskey="6">Wireframe</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Update Event Confirmation</h1>
        <?php
            include 'DBConnection.php';

            $eventID = isset( $_REQUEST['eventID']) ? $_REQUEST['eventID']: null;
            $eventTitle = isset( $_REQUEST['eventTitle']) ? $_REQUEST['eventTitle']: null;
            $eventStartDate = isset( $_REQUEST['eventStartDate']) ? $_REQUEST['eventStartDate']: null;
            $eventEndDate = isset( $_REQUEST['eventEndDate']) ? $_REQUEST['eventEndDate']: null;
            $eventPrice = isset($_REQUEST['eventPrice']) ? $_REQUEST['eventPrice']: null;
            //If the retrived variable is 0 the validation detects it null therefore i said assign the value 0.00 which doesn't return null
            if ($eventPrice == "0") {
                $eventPrice = "0.00";
            }
            $category = isset($_REQUEST['category']) ? $_REQUEST['category']: null;
            $venue = isset($_REQUEST['venue']) ? $_REQUEST['venue']: null;
            $eventDescription = isset($_REQUEST['eventDescription']) ? $_REQUEST['eventDescription']: null;


            //All if empty statements will have the same tabindex and accesskey since only 1 statement can appear at one time.
            if (empty($eventTitle)) {
                echo "<p>The name of the event is required. Click <a href='editSelectedEvent.php?eventID=$eventID' tabindex='7' accesskey='7' title='Link to go back to editing event'> here </a> to go back to the list of events to edit.</p>\n ";
            }
            elseif (empty($eventStartDate)) {
                echo "<p>The start date of the event is needed. Click <a href='editSelectedEvent.php?eventID=$eventID' tabindex='7' accesskey='7' title='Link to go back to editing event'> here </a> to go back to the list of events to edit.</p>\n ";
            }
            elseif (empty($eventEndDate)) {
                echo "<p>The end date of the event is required. Click <a href='editSelectedEvent.php?eventID=$eventID' tabindex='7' accesskey='7' title='Link to go back to editing event'> here </a> to go back to the list of events to edit.</p>\n ";
            }
            elseif (empty($eventPrice)) {
                echo "<p>The price of the event is required. Click <a href='editSelectedEvent.php?eventID=$eventID' tabindex='7' accesskey='7' title='Link to go back to editing event'> here </a> to go back to the list of events to edit.</p>\n ";
            }
            elseif (empty($category)) {
                echo "<p>The category of the even is required. Click <a href='editSelectedEvent.php?eventID=$eventID' tabindex='7' accesskey='7' title='Link to go back to editing event'> here </a> to go back to the list of events to edit.</p> \n";
            }
            elseif (empty($venue)) {
                echo "<p>The venue of the event is required. Click <a href='editSelectedEvent.php?eventID=$eventID' tabindex='7' accesskey='7' title='Link to go back to editing event'> here </a> to go back to the list of events to edit.</p>\n ";
            }
            elseif (empty($eventDescription)) {
                echo "<p>The event description is required. Click <a href='editSelectedEvent.php?eventID=$eventID' tabindex='7' accesskey='7' title='Link to go back to editing event'> here </a> to go back to the list of events to edit.</p>\n ";
            }
            elseif ($eventStartDate > $eventEndDate) {
                //This if statement is to make sure that an event can not finish before it has even started
                echo "<p>The event is ending before it even starts. Click <a href='editSelectedEvent.php?eventID=$eventID' tabindex='7' accesskey='7' title='Link to go back to editing event'> here </a> to go back to the list of events to edit.</p>\n ";
            }
            else {

                $eventTitle = $dbConn->real_escape_string($eventTitle);
                $eventDescription = $dbConn->escape_string($eventDescription);

                //If all the variables have a value that not null and the end date is after the start date then update the event
                $updateSQL = "UPDATE AE_events set
                eventTitle = '$eventTitle',
                eventDescription = '$eventDescription',
                venueID = '$venue',
                catID = '$category',
                eventStartDate = '$eventStartDate',
                eventEndDate = '$eventEndDate',
                eventPrice = '$eventPrice'
                WHERE eventID = '$eventID' ";

                $success = $dbConn->query($updateSQL);


                if ($success == true) {
                    echo "<p>The event has been updated.</p>
                          <p>Click <a href='editEvents.php' title='Links to edit events' tabindex='7' accesskey='7' class='submissionLinks'> here</a> to select another event for editing.</p>
                          <p>To go back to view events click <a href='viewEvents.php' title='Link to view event' tabindex='8' accesskey='8' class='submissionLinks'> here</a>.</p>\n";
                }
                else {
                    echo "<p>An error has happened. Click <a href='editSelectedEvent.php?eventID=$eventID' tabindex='7' accesskey='7' title='Link to go back to editing event'> here </a> to go back to editing the event where you can try again. Error is: $dbConn->error</p>\n";
                }


                $dbConn->close();
            }
        ?>
    </main>

    <footer>
        <p>This page is to confirm that the event chosen to be edited has been changed or not if there are errors. </p>
    </footer>
</div>
</body>
</html>