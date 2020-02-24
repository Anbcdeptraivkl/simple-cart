<?php
// Starting new Session
session_start();
// Establishing Connection
require('db_config.inc.php');

// Page Changing on Linking (controled with URL var ?page)
if(isset($_GET['page'])){ 
    $pages = array("products", "cart"); 
    if (in_array($_GET['page'], $pages)) {   
        $current_page = $_GET['page']; 
    } else { 
        $current_page = "products"; 
    } 
} else { 
    $current_page = "products"; 
} 
?>

<!--Index: Shared Template between the pages-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>

    <link type="text/css" rel="stylesheet" href="style.css">
</head>
<body>

<main>
    <!--Main Content: Differ between Pages-->
    <?php require($current_page .".php"); ?>

    <!--Cart Listing Sidebar-->
    <section class="cart-sidebar">
        <h3>Cart</h3>
        <?php
        // If the Cart Session var is set, there certainly is products with approriate IDs insdie - at least 1 entry
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            // Construct the SQL query from Substrings of Commands and IDs from the Cart
            $sql_sel_part = "SELECT * FROM prtc_products WHERE id IN (";
            foreach ($_SESSION['cart'] as $cart_id => $value) {
                $sql_sel_part .= $cart_id . ",";
            }
            // COmbine the Strings, using substr() to remove the last strayed comma in the first part
            $complete_cart_sel_sql = substr($sql_sel_part, 0, -1) .") ORDER BY prdt_name ASC";
            // Execute Select query
            $prdt_select_query = mysqli_query($conn, $complete_cart_sel_sql);
            // Fetch and Display Each collected products entries's names & quantity
            // Update with every Add to Cart
            // Will implement AJAX methods later 
            while ($printing_row = mysqli_fetch_array($prdt_select_query)) {
        ?>
            <p>
                <?php 
                echo 
                    $printing_row['prdt_name'] . " x " . 
                    $_SESSION['cart'][$printing_row['id']]['quantity']; 
                ?></p>
        <?php
        }
        ?>
        <?php
        } else {
            // NO Products added yet
            echo "Empty Cart. Add some Products to view";
        }
        ?>
        <br />
        <!--Go to Cart page-->
        <a href="index.php?page=cart">Go to Cart</a>
    </section>
</main>

</body>
</html>