<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Arts and Events | Add new event</title>
    <link rel="stylesheet" type="text/css" href="AddNewEventStyleSheet.css"/>
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
        <h1>Add New Event</h1>
        <p>Fill the form to add a new event to the database. Click on add event button at the bottom once the form is complete. </p>
        <form action="addNewEventSubmission.php" id="AddNewEventForm" method="get">
            <fieldset>
                <legend>Event Details</legend>
                <label>Event Title:
                    <input type="text" name="eventTitle" placeholder="Event Title" tabindex="7" title="Enter text here for the name of the event" accesskey="7"/>
                </label>

                <label>Event Venue:
                    <select name="venueID" tabindex="8" title="Select a venue for the event here" accesskey="8" >
                        <?php
                        include "DBConnection.php";
                        //To get the venues i quuery the data using the select statement then echo the results in an option tag
                        $venueSelectSQL = "SELECT venueName, venueID FROM AE_venue";
                        $queryResult = $dbConn->query($venueSelectSQL);
                        if($queryResult === false) {
                            echo "<p>Query failed: ".$dbConn->error."</p>\n</select>\n</label>\n</fieldset>\n</form>\n</main>\n</div>n</body>\n</html>";
                            exit;
                        }
                        else {
                            while($rowObj = $queryResult->fetch_object()){
                                $venueName = $rowObj->venueName;
                                $venueID = $rowObj->venueID;
                                echo "<option value='$venueID' title='Select the wanted venue'>$venueName</option>\n";
                            }
                        }

                        $queryResult->close();
                        $dbConn->close();
                        ?>
                    </select>
                </label>

                <label>Event Category:
                    <select name="catID" tabindex="9" title="Select the event category from here" accesskey='9'>
                        <?php
                        include "DBConnection.php";
                        //To get the categories i quuery the data using the select statement then echo the results in an option tag
                        $catSelectSQL = "SELECT catDesc, catID FROM AE_category";
                        $queryResult = $dbConn ->query($catSelectSQL);
                        if($queryResult === false) {
                            echo "<p>Query failed: ".$dbConn->error."</p>\n</select>\n</label>\n</fieldset>\n</form>\n</main>\n</div>n</body>\n</html>";
                            exit;
                        }
                        else {
                            while($rowObj = $queryResult->fetch_object()){
                                $catDesc = $rowObj->catDesc ;
                                $catID = $rowObj->catID;
                                echo "<option value='$catID' title='Select the wanted category' >$catDesc</option>\n";
                            }
                        }
                        $queryResult->close();
                        $dbConn->close();
                        ?>
                    </select>
                </label>

                <label>Event Start Date:
                    <input type="date" name="eventStartDate"  tabindex="10" title="Select or type a start date (YYYY-MM-DD)" accesskey="a" />
                </label>

                <label>Event End Date:
                    <input type="date" name="eventEndDate"  tabindex="11" title="Select or type an end date (YYYY-MM-DD)" accesskey="b" />
                </label>

                <label>Event Price:
                    <input type="number" name="eventPrice" placeholder="Event Price" tabindex="12" min="0" step="0.01" title="Enter a number for the price of the event" accesskey="c"/>
                </label>

                <label>Event Description:
                    <textarea name="eventDescription"  tabindex="13" title="Enter a description for the event here" accesskey="d" placeholder="Enter a descirption for the event here"></textarea>
                </label>

            </fieldset>
            <input type="submit"  value="Add Event" tabindex="14" class="updateButton" title="Click here to submit the data to the database." accesskey="e" />
            <input type="reset" value="Clear" tabindex="15" class="clearButton" title="Click here to clear the form." accesskey="f"/>
        </form>
    </main>

    <footer>
        <p>The boxes where you need to enter a date may turn up blank on some web browsers. If it does the date format must be entered as YYYY-MM-DD. On web browsers where you get the selection it may insert it as DD-MM-YYYY, this doesn't matter.</p>
    </footer>
</div>
</body>
</html>