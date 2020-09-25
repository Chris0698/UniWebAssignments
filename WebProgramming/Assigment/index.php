<?php
ini_set("session.save_path", "/home/unn_w16005124/sessionData");
session_start();
?>
<?php
require_once ("functions.php");
echo get_core_tags("Northumbria book Company | Home", "stylesheet");
?>
<body>
    <!--Gets the getRequest JS script for AJAX-->
    <script type="text/javascript" src="getRequest.js"></script>

<?php
    //Gets the login form and title header with the logo and links
    get_login_form();
    echo get_title_header(array("index.php" => "Home",
                                "orderBooksForm.php" => "Order Books",
                                "chooseBookList.php" => "Admin",
                                "credits.php" => "Credits"), "navLinks");
?>
   <div class="search">
       <label>Search for Books:
           <input type="text" id="searchBox" placeholder="Search Books"/>
       </label>
   </div>

    <div class="indexWrapper">
        <main id="indexMain">
            <header>
                <h1>Homepage</h1>
                <p>Welcome to Northumbria Book Company we sell a range of different computing books from: PHP, Databases and UNIX.</p>
            </header>

            <!--Some page information.-->
            <p>
                We have a great range of books. To search for books use the search box on this page and the book information will be retrieved.
                If you want to buy books then click on the Buy Book link or <a href="orderBooksForm.php"
                                                                               accesskey="7">click here</a>.
            </p>

            <!--Section for the auto complete box-->
            <section>
                <h2>Searched Book</h2>
                <!--This paragraph will dissapear once a book is selected from the menu-->
                <p id="overView">Book details will appear if you search and select a book from the search box.</p>
                <div id="returnedBook">
                    <!--Returned content goes here.-->
                </div>
            </section>
        </main>

        <!--For the special offers AJAX-->
        <aside>
            <section>
                <h2>Special Offers</h2>
                <img src="special-offers.jpg" width="100" height="100" alt="Special Offers image" title="Special offers"/>
                <!--This paragraph will be replaced with the offer retrieved. -->
                <p id="offers">If you see this text there has been an error. Check that javascript is enabled in your web browser.</p>
                <script type="text/javascript">
                    //<![CDATA[
                    'use strict';
                    //Add the event listener for when page loads
                    window.addEventListener('load', function () {

                        //Updates the paragraph with the book offer
                        function updateText(newOffer) {
                            'use strict';
                            document.getElementById('offers').innerHTML = newOffer;
                        }

                        //Gets the book offer using AJAX getRequest function
                        var getOffer = function () {
                            getRequest('getOffers.php', updateText);

                            //Times the function and call it again after every 5 seconds
                            setTimeout(getOffer, 5000);
                        };

                        //Cal the variable again
                        getOffer();
                    });
                    //]]>
                </script>
            </section>
        </aside>
    </div>
<?php
    echo get_footer("Copyright: Northumbria Book Company 2017. Contact us by E-mailing: BNC@outlook.com or telephone 00000 123456");
?>
    <!--Scripts for the Jquery autocomplete.-->
    <script type='text/javascript' src='//code.jquery.com/jquery-2.1.3.js'></script>
    <script type="text/javascript" src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <script type='text/javascript' src='JQuery/autocomplete.js'></script>
</body>
<!--Closing html tag in function that will be imported-->
</html>