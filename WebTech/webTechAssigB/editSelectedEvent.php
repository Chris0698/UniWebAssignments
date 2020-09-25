<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Arts and Events | Edit Selected Event</title>
    <link rel="stylesheet" type="text/css" href="editSelectedEventStyleSheet.css" />
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
       <h1>Edit Selected Event</h1>
       <?php
           include 'DBConnection.php';

           $eventID = isset($_REQUEST['eventID']) ? $_REQUEST['eventID'] : null;
           if (empty($eventID)) {
               //I included this in the event of the URL being directly entered into the search bar by passsing where you select the event.
               //Has accesskey and tabindex of 6 since either this error would appear or it would go into the block of code fpr the form
               echo "<p>An event has not been selected. Click <a href='editEvents.php' tabindex='6' accesskey='6' title='Click to go back to the event list'>here</a> to select an event </p>\n";
           }
           else {
               echo " <p>Complete the form to edit the chosen event. Once done click on the update button.</p>\n";

               $SQL = "SELECT eventID, eventTitle, eventDescription, venueID, catID, eventStartDate, eventEndDate, eventPrice 
                       FROM AE_events 
                       WHERE eventID = '$eventID'";
               $queryResult = $dbConn->query($SQL);
               if($queryResult === false) {
                   echo "<p>Query failed: ".$dbConn->error."</p>\n</main>\n</div>\n</body>\n</html>";
                   exit;
               }
               else {
                   $rowObj = $queryResult->fetch_object();
                   $eventID = $rowObj->eventID;
                   $eventTitle = $rowObj->eventTitle;
                   $eventDescription = $rowObj-> eventDescription;
                   $venueID = $rowObj->venueID;
                   $catID = $rowObj->catID;
                   $eventStartDate = $rowObj->eventStartDate;
                   $eventEndDate = $rowObj->eventEndDate;
                   $eventPrice = $rowObj->eventPrice;

                   $eventTitle = $dbConn->real_escape_string($eventTitle);
                   $eventDescription = $dbConn->escape_string($eventDescription);

                   echo "<form method='get' action='editSelectedEventScript.php'>
                            <!--This input type is hidden from the user since we dont want to display DB details. The script can still access the name attribute though -->
                            <input type='hidden' value='$eventID' name='eventID'/>
                                            
                            <fieldset>
                                <legend>Event Details</legend>
                                <label>Event Title:
                                    <input type='text' value='$eventTitle' name='eventTitle' tabindex='7' title='Type here to change the event title.' accesskey='7'/>
                                </label>
                                
                                <label>Event Venue:
                                <select name='venue' tabindex='8' title='Select a venue to change the venue' accesskey='8'>\n";
                                    //SQL to get the venue name and the id of the event selected
                                    $venueSQL = "SELECT venueName, venueID from AE_venue WHERE '$venueID' = venueID";
                                    $queryResultVen = $dbConn->query($venueSQL);
                                    if($queryResultVen === false) {
                                        echo "<p>Query failed: ".$dbConn->error."</p>\n</select>\n</label>\n</fieldset>\n</form>\n</main>\n</div>\n</body>\n</html>";
                                        exit;
                                    }
                                    else {
                                        $rowObj = $queryResultVen->fetch_object();
                                        $venueName = $rowObj->venueName;
                                        $venueID = $rowObj->venueID;
                                        //Get the only option result and give it selected so it the default option
                                        echo "<option value='$venueID' selected >$venueName</option>\n";

                                        //SQL for rest of the options
                                        $selectsql = "SELECT  DISTINCT venueName, venueID FROM AE_venue";
                                        $queryResult = $dbConn->query($selectsql);
                                        if($queryResult === false) {
                                            echo "<p>Query failed: ".$dbConn->error."</p>\n</select>\n</label>\n</fieldset>\n</form>\n</main>\n<div>\n</body>\n</html>";
                                            exit;
                                        }
                                        else {
                                            while($rowObj = $queryResult->fetch_object()){
                                                $venueName2 = $rowObj->venueName;
                                                $venueID2 = $rowObj->venueID;
                                                //If the 2 ids are not equal then add them as a option to the select list. This will remove the duplicate enteries
                                                if ($venueID != $venueID2 ) {
                                                    echo "<option value='$venueID2'>$venueName2</option>\n";
                                                }
                                            } //Closing brace for the while loop
                                            $queryResultVen->close();
                                        }
                                    }

                                echo "</select>
                                </label>
                                
                                <label>Event Category:
                                <select name='category' tabindex='9' accesskey='9' title='Select a category to change the event category'>";
                                    $catSQLSelected = "SELECT catID, catDesc FROM AE_category WHERE catID = '$catID'";
                                    $queryResultCat = $dbConn->query($catSQLSelected);
                                    if($queryResultCat === false) {
                                        echo "<p>Query failed: ".$dbConn->error."</p>\n</select>\n</fieldset>\n</form>\n</main>\n</div>\n</body>\n</html>";
                                        exit;
                                    }
                                    else {
                                        $rowObj = $queryResultCat->fetch_object();
                                        $catID = $rowObj->catID;
                                        $catDesc = $rowObj->catDesc;
                                        echo "<option value='$catID' selected >$catDesc</option>\n";
                                        $selectsql = "SELECT DISTINCT catDesc, catID FROM AE_category";
                                        $queryResult = $dbConn->query($selectsql);
                                        if ($queryResult === false) {
                                            echo "<p>Query failed: ". $dbConn->error. "</p>\n</select>\n</label>\n</fieldset>\n</form>\n</main>\n<div>\n</body>\n</html>";
                                            exit;
                                        }
                                        else {
                                            while ($rowObj = $queryResult->fetch_object()) {
                                                $catDesc2 = $rowObj->catDesc;
                                                $catID2 = $rowObj->catID;
                                                if ($catDesc != $catDesc2) {
                                                    //If the 2 variables are not equal then add them as a option to the select list.
                                                    echo "<option value='$catID2'>$catDesc2</option>\n";
                                                }
                                            }
                                            $queryResultCat->close();
                                        }
                                    }

                                    echo "</select>
                                </label>
                            
                                <label>Event Start Date:
                                    <input type='date' value='$eventStartDate' name='eventStartDate' tabindex='10' title='Select or type (YYYY-MM-DD) to change the start date' accesskey='a'/>
                                </label>
                              
                                <label>Event End Date:
                                    <input type='date' value='$eventEndDate' name='eventEndDate' tabindex='11' title='Select or type (YYYY-MM-DD) to change the end date' accesskey='b'/>
                                </label>
                              
                                <label>Event Price:
                                    <input type='number' value='$eventPrice' min='0.00' step='0.01' name='eventPrice' tabindex='12' title='Enter a number here to change the price' accesskey='c'/>
                                </label>
                                
                                <label>Event Description:
                                    <textarea name='eventDescription' tabindex='13' accesskey='d' title='Modify the text here if you want to change the event description'>$eventDescription</textarea>
                                </label>
                            </fieldset>
                         
                            <input type='submit' value='Update' tabindex='14' accesskey='e' title='Click here to submit the changes' id='submitButton'/>
                    
                       </form>\n";
                      $queryResult->close();
                      $dbConn->close();
               }

           }
       ?>
   </main>

    <footer>
        <p>The boxes where you need to enter a date may turn up blank on some web browsers. If it does the date format must be entered as  YYYY-MM-DD. On web browsers where you get the selection it may insert it as DD-MM-YYYY, this doesn't matter.</p>
    </footer>

</div>

</body>
</html>