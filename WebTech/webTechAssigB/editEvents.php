<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Arts and Events | Edit Events</title>
    <meta name = "viewport" content="width=device-width, maximum-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="editEventStyle.css"/>
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
        <h1>Edit Events</h1>
        <p>To edit an existing event, first click on an event from the list.</p>
        <p class="aditionalSmallScreenInfo">The first date is the start date, the second date is the end date.</p>
        <!--Adds a header to each coloumn.  -->
        <div class="events">
            <span class='eventTitleH'>Event Title</span>
            <span class='eventDescriptionH'>Event Description</span>
            <span class='catDescH'>Event Category </span>
            <span class='venueNameH'>Venue</span>
            <span class='eventStartDateH'>Event Start Date</span>
            <span class='eventEndDateH'>Event End Date</span>
            <span class='eventPriceH'>Event Price</span>
        </div>

        <?php
            include 'DBConnection.php';
            $sql = "SELECT  eventID, catDesc, eventTitle, eventDescription, venueName, eventStartDate, eventEndDate, eventPrice 
                    FROM AE_events 
			        INNER JOIN AE_category 
                    ON AE_category.catID = AE_events.catID 
			        INNER JOIN AE_venue
			        ON AE_venue.venueID = AE_events. venueID
 			        ORDER BY eventTitle";
            $queryResult = $dbConn->query($sql);
            if($queryResult === false) {
                echo "<p>Query failed: ".$dbConn->error."</p>\n</main>\n</div>\n</body>\n</html>";
                exit;
            }
            else {
                while ($rowObj = $queryResult->fetch_object()) {
                    $eventID = $rowObj->eventID;
                    $catDesc = $rowObj->catDesc;
                    $eventTitle = $rowObj->eventTitle;
                    $eventDescription = $rowObj->eventDescription;
                    $venueName = $rowObj->venueName;
                    $eventStartDate = $rowObj->eventStartDate;
                    $eventEndDate = $rowObj->eventEndDate;
                    $eventPrice = $rowObj->eventPrice;



                    echo "<div class='events'>
                              <span class='eventTitle'>
                                  <a href='editSelectedEvent.php?eventID=$eventID' class='eventTitle' title='Click to edit event for: $eventTitle'>$eventTitle</a>
                                  <!-- The links will keep there underline so they stand out-->
                              </span>    
                              <span class='eventDescription'>$eventDescription</span>
                              <span class='catDesc'>$catDesc</span>
			                  <span class='venueName'>$venueName</span>
                              <span class='eventStartDate'>$eventStartDate</span>
                              <span class='eventEndDate'> $eventEndDate</span>
                              <span class='eventPrice'>$eventPrice</span>
                        </div>\n";
                }
            }
            $queryResult->close();
            $dbConn->close();
        ?>
    </main>

    <footer>
        <p>All events are correct to the date:
            <?php
            //Prints out the month, day and year.
            echo date(" F j  Y");
            ?>
            at the time of:
            <?php
            //Prints out the hour (24 hour with a leading zero), minutes (with a leading 0) and if it AM or PM (capitalised)
            echo date("H:i A");
            ?>
            .Events can be added or removed as time goes on. All times are UK time
            <?php
            //Prints out the difference bewteen GMT time and the time someone viewing this page
            echo date("O");
            ?>
            .</p>
    </footer>
</div>
</body>
</html>