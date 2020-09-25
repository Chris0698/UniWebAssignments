<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Arts and Events | Add New Event: Submission</title>
    <link rel="stylesheet" type="text/css" href="addNewEventSubmissionStyleSheet.css"/>
    <meta name = "viewport" content="width=device-width, maximum-scale=1.0" />
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
        <h1>Add New Event: Submission</h1>
	<?php
	    include 'DBConnection.php';

	    //Gets the variables from the addNewEvent form page
        $eventTitle = isset( $_REQUEST['eventTitle']) ? $_REQUEST['eventTitle']: null;
        $eventStartDate = isset( $_REQUEST['eventStartDate']) ? $_REQUEST['eventStartDate']: null;
        $eventEndDate = isset( $_REQUEST['eventEndDate']) ? $_REQUEST['eventEndDate']: null;
        $eventPrice = isset($_REQUEST['eventPrice']) ? $_REQUEST['eventPrice'] : null;
        //If the retrived variable is 0 the validation detects it null therefore i said assign the value 0.00 which doesn't return null
        if ($eventPrice == "0") {
            $eventPrice = "0.00";
        }

        $catID = isset($_REQUEST['catID']) ? $_REQUEST['catID']: null;
        $venueID = isset($_REQUEST['venueID']) ? $_REQUEST['venueID']: null;
        $eventDescription = isset($_REQUEST['eventDescription']) ? $_REQUEST['eventDescription']: null;

        //SQl to retrieve the venue name for if the adding new event is successfull then the venue name will be displayed. Dont have to hard code the venue name
        $eventSQl = "SELECT venueName from AE_venue WHERE '$venueID' = venueID";
        $queryResult = $dbConn->query(($eventSQl));
        $rowObj = $queryResult->fetch_object();
        if($queryResult === false) {
            echo "<p>Query failed: ".$dbConn->error."</p>\n</main>\n</div>\n</body>\n</html>";
            exit;
        }
        else {
            $venueName = $rowObj->venueName;
        }

        //SQL gto retrieve the category description of the event if successful. Prevents hard-coding the value.
        $catSQl = "SELECT catDesc from AE_category WHERE catID = '$catID' ";
        $queryResult = $dbConn->query(($catSQl));
        $rowObj = $queryResult->fetch_object();
        if($queryResult === false) {
            echo "<p>Query failed: ".$dbConn->error."</p>\n</main>\n</div>\n</body>\n</html>";
            exit;
        }
        else {
            $catDesc = $rowObj->catDesc;
        }

         //Checks to make sure all variables have a value. I decided to include all in the unlikely event that the web address is entered bypassing the page
         //where you select the event details
         //All if statements will have a tabindex and accesskey =7 becasue only 1 if empty statement can appear at one time
         if (empty($eventTitle)) {
             echo "<p>The name of the event is required. Click <a href='addNewEvent.php' tabindex='7' accesskey='7' title='Link to go back to add new page form'> here </a> to add the name.</p>\n ";
         }
         elseif (empty($eventStartDate)) {
             echo "<p>The start date of the event is needed. Click <a href='addNewEvent.php' tabindex='7' accesskey='7' title='Link to go back to add new page form'> here </a> to add the start date.</p>\n ";
         }
         elseif (empty($eventEndDate)) {
             echo "<p>The end date of the event is required. Click <a href='addNewEvent.php' tabindex='7' accesskey='7' title='Link to go back to add new page form'> here </a> to add the end date.</p>\n ";
         }
         elseif( empty($eventPrice)) {
             echo "<p>The price of the event is required. Click <a href='addNewEvent.php' tabindex='7' accesskey='7' title='Link to go back to add new page form'> here </a> to add the price.</p>\n ";
         }
         elseif (empty($catDesc)) {
             echo "<p>The category of the event is required. Click <a href='addNewEvent.php' tabindex='7' accesskey='7' title='Link to go back to add new page form'> here </a> to add the end category.</p>\n ";
         }
         elseif (empty($venueName)) {
             echo "<p>The venue of the event is required. Click <a href='addNewEvent.php' tabindex='7' accesskey='7' title='Link to go back to add new page form'> here </a> to add the venue.</p>\n ";

         }
         elseif (empty($eventDescription)) {
             echo "<p>The description of the event is required. Click <a href='addNewEvent.php' tabindex='7' accesskey='7' title='Link to go back to add new page form'>here </a> to add the description.</p>\n  ";

         }
         elseif ($eventStartDate > $eventEndDate) {
            //This if statement is to make sure that an event can not finish before it has even started
             echo "<p>The event is ending before it even starts. Click <a href='addNewEvent.php' tabindex='7' accesskey='7' title='Link to go back to add new page form'> here </a> to change the date of the event </p>\n";
         }
         else {
            //I decided that the event information to be shown on the submission page should have the escaped characters since it would have
            //backslashes and look a bit weird. I decided to create a new variable that gets in the information from the eventTitle and decription
            //insert. I then escaped the special characters and then the new variable is insert into the db while the variable echo out isn't escaped.
            $eventTitleInsert = $eventTitle;
            $eventDescriptionInsert = $eventDescription;

            $eventDescriptionInsert = $dbConn->escape_string($eventDescriptionInsert);
            $eventTitleInsert = $dbConn->escape_string($eventTitleInsert);


            //SQL to insert into the DB if all variables have a value that not null and the end date is after the event start date
            $insertsql = "insert into AE_events(eventTitle, eventDescription, venueID, catID, eventStartDate, eventEndDate, eventPrice)
			  VALUES ( '$eventTitleInsert', '$eventDescriptionInsert' , '$venueID' , '$catID' ,'$eventStartDate', '$eventEndDate','$eventPrice')";

            $success = $dbConn ->query($insertsql);

            if ($success == true) {
                //The webpage will list what the user has selected when the data to the DB is successful.
                echo"<p>The new event has been added to the database. Click <a href='viewEvents.php'> here</a> to view the event page. </p>";
                if ($catID == "c9") {
                    echo '<img src="dance.jpg" alt="dance" title="Dance image" width="490" height="280" class="catImg" />';
                }
                elseif ($catID == "c8") {
                    echo '<img src="sportImage.jpg" width="500" height="245" title="Sport Image" alt="sport_image" class="catImg"/>';
                }
                elseif ($catID == "c7") {
                    echo '<img src="musicNote.jpg" title="Music Note" alt="music_note_image" width="300" height="300" class="catImg"/>';
                }
                elseif ($catID == "c6") {
                    echo '<img src="family.jpg" title="Family" alt="Family" class="catImg" height="300" width="400" />';
                }
                elseif ($catID == "c5") {
                    echo "<img src='festival.jpg' title='Festival' alt='Festival' width='500' height='300' class='catImg'/>";
                }
                elseif ($catID == "c4") {
                    echo "<img src='exhibition.jpg' title='Exhibition' alt='Exhibition' width='500' height='300' class='catImg'/>";
                }
                elseif ($catID == "c3") {
                    echo '<img src="comedy.jpg" title="Comedy" alt="Comedy" width="340" height="300" class="catImg"/>';
                }
                elseif ($catID == "c2") {
                    echo '<img src="theatre.png" title="Theatre" alt="Theatre" width="500" height="300" class="catImg"/>';
                }
                else {
                    echo "<img src='carnival.jpg' title='Carnival' alt='carnival' width='400' height='300' class='catImg'/>";
                }

                echo" <article> 
                          <p>The title of the event is called : $eventTitle </p> 
                          <p>The category of the event is: $catDesc</p>
                          <p>The venue of the event is: $venueName</p>
                          <p>The start date of the event is: $eventStartDate</p>
                          <p>The end date of the event is: $eventEndDate </p>"; ?> <!-- Closing 1st PHP tag. I know its in a bad place but the article tag closes it in-->
                          <!--If the users enter a price such as 1 it is echo as price: 1. The number format adds the .00 at the end. If the number 1.23 for example then it will remain unchanged-->
                          <p>The price of the event is :   <?php  echo number_format($eventPrice, 2); ?> </p>  <?php //Opening php tag that will close near the end of the page

                          echo" <p>The description of the event is: $eventDescription</p>
                      </article>";
            }
            else {
                //Same tabindex and accesskey since either this paragraph will be echoed or if empty will be instead
                echo "<p>Error when saving the data. Click <a href='addNewEvent.php' tabindex='7' accesskey='7' title='Link to go back to add new page form'>here</a> to redo the form and try again. </p>\n";
            }

            $dbConn->close();
         }

        ?>
    </main>

    <footer>
        <!-- Explains what the page is about-->
        <p>This page is to confirm that the created event has been added to the database or rejected with the reason why it has.</p>
    </footer>
</div>
</body>
</html>