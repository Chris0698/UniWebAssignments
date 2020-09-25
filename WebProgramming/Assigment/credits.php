<?php
ini_set("session.save_path", "/home/unn_w16005124/sessionData");
session_start();
?>
<?php
    require_once ("functions.php");
    echo get_core_tags("Northumbria book Company |Credits", "stylesheet");

    echo "\n\t<body>\n";

    get_login_form();
    echo get_title_header(array("index.php" => "Home",
                                "orderBooksForm.php" => "Order Books",
                                "chooseBookList.php" => "Admin",
                                "credits.php" => "Credits"), "navLinks");
?>
    <main>
        <h1>Credits</h1>
        <p>Student ID: w16005124</p>
        <p>Student Name: Chris Aston</p>
        <p>E-mail: chris.aston@northumbria.ac.uk</p>
        <p>The title logo text was my own graphics however the The book used in the title logo was obtained by a web search http://www.carechartsuk.co.uk 2014, Book Reviews - Care Charts UK[online]
            available at http://www.carechartsuk.co.uk/book-reviews [ Accessed 18 October 2017]
        <p>
        <p>The special offers image on the index page obtained by a web search http://www.chalkevalleystores.co.uk 2015, Promotions - Chalke Valley Stores [online]
            available at: http://www.chalkevalleystores.co.uk/promotions [accessed 20 October 2017]
        </p>
        <p>
            Autocomplete CSS was used by a web search http://www.jqueryui.com.com 2013, All jQuery UI Downloads | jQuery UI [online]
            available at https://jqueryui.com/download/all/ [accessed 26 November 2017]
        </p>
    </main>
<?php
    echo get_footer("Copyright: Northumbria Book Company 2017. All web pages were produced by Chris Aston w16005124.")
?>
</body>
</html><!--Start HTML tag in function-->