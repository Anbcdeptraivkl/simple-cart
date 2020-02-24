<?php
// When the user Clicks to Add Product , Setting up Session Variables to make Anchor Links work
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    // ID of the Currently Adding product
    $id = intval($_GET['id']);
    // If the ID already exist in the cart, simply increase quantity
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity']++;
    } else {
        // Check if the ID exist in the Database
        $id_select_query = "SELECT * FROM prtc_products WHERE id= {$id}";
        $id_check_results = mysqli_query($conn, $id_select_query);
        if (mysqli_num_rows($id_check_results) > 0) {
            $adding_row = mysqli_fetch_array($id_check_results);
            // Create the Cart - ID Session(s)
            $_SESSION['cart'][$adding_row['id']] = array(
                // Adding the Product Buying Data into Session Cart
                // For the sake of simplicity, quantity is calculated with steps of 1
                'quantity' => 1,
                'price' => $adding_row['price']
            );
        } else {
            // Wrong ID Error
            $error_message = "Invalid Product ID";
        }
    }
}
?>

<?php
//Displaying Error Product ID
if (isset($error_message)) {
    echo '<h3>{$error_message}</h3><br />';
}
?>

<!--Products List-->
<table class="products-container">
    <caption><h3>Products List</h3></caption>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Action</th>
    </tr>
    <!--Each rows represents a simple product listing-->
    <?php
    // Fetch Data
    $prdt_select_query = "SELECT * FROM prtc_products ORDER BY id ASC";
    $results = mysqli_query($conn, $prdt_select_query);
    // Checking if fetched anything
    if (mysqli_num_rows($results) > 0) {
        // Output Row by Row the Results Fetched
        while($row = mysqli_fetch_array($results))
        {
    ?>
        <tr>
            <td><?php echo $row['prdt_name']; ?></td>
            <td><?php echo $row['prdt_desc']; ?></td>
            <td><?php echo $row['price'].$currency; ?></td>
            <!--Pass extra Variables with anchor links-->
            <td><a href="index.php?page=products&action=add&id=<?php echo $row['id']; ?>" class="add_to_cart">Add To Cart</a></td>
        </tr>
    <?php
        }
    }
    ?>
</table>