<!--Update items's quantity based on the form's input-->
<?php
// Check if the Form has been submitted
if (isset($_POST['cart-submit'])) {
    foreach ($_POST['item-quantity'] as $key => $val) {
        // Remove item from the cart if the quantity is set to 0
        if ($val <= 0) {
            unset($_SESSION['cart'][$key]);
        } else {
            // If not unsetting, simply update the quantity fields
            $_SESSION['cart'][$key]['quantity'] = $val;
        }
    }
}
?>

<!--Cart Update Form-->
<form method="POST" action="index.php?page=cart">
    <a href="index.php?page=products">Back to Products Listing</a>
    <br />
    <!--Products List-->
    <table class="products-container">
        <caption><h3>Cart View</h3></caption>
        <tr>
            <th>Name</th>
            <th>Quantity</th>
            <th>Item Price</th>
            <th>Added Price</th>
        </tr>
        <!--Each rows represents a simple product listing-->
        <?php
        // Fetch all Items Data from the Cart Session with Added ID
        // Construct the SQL query from Substrings of Commands and IDs from the Cart
        if (empty($_SESSION['cart']))
            echo "<br />Empty Cart.<br />";
        else {
            $sql_sel_part = "SELECT * FROM prtc_products WHERE id IN (";
            foreach ($_SESSION['cart'] as $cart_id => $value) {
                $sql_sel_part .= $cart_id . ",";
            }
            // COmbine the Strings, using substr() to remove the last strayed comma in the first part
            $complete_cart_sel_sql = substr($sql_sel_part, 0, -1) .") ORDER BY prdt_name ASC";
            // Execute Select query
            $prdt_select_query = mysqli_query($conn, $complete_cart_sel_sql);
            $total_price = 0.0;
            $sub_total_price = [];
            if (mysqli_num_rows($prdt_select_query) > 0)
                while ($row = mysqli_fetch_array($prdt_select_query)) {
                    // Total price(s) calculating & re-calculating
                    $sub_total_price[$row['id']] = $row['price'] * $_SESSION['cart'][$row['id']]['quantity'];
                    $total_price += $sub_total_price[$row['id']];
                ?>
            <tr>
                <td><?php echo $row['prdt_name']; ?></td>
                <td>
                    <!--
                        Quantity input
                        Name of the input = quantity-id + the ID of the product showing
                    -->
                    <input 
                        type="number"
                        name="item-quantity[<?php echo $row['id']; ?>]" 
                        size="5" 
                        value="<?php echo $_SESSION['cart'][$row['id']]['quantity']; ?>" 
                    />
                </td>
                <td><?php echo $row['price']; ?>$</td>
                <!--Corresponding sub-total price-->
                <td>
                    <?php
                    if (isset($sub_total_price))
                        echo $sub_total_price[$row['id']];
                    ?>
                    $
                </td>
            </tr>
                <?php
                }
            }
        ?>
        <tr>
            <td colspan="4">Total Price: 
                <?php 
                if (isset($total_price))
                    echo $total_price;
                ?>
                $
            </td>
        </tr>
    </table>
    <br />
    <button type="submit" name="cart-submit">Update</button>
    <br />
    <p>To remove item, set its quantity to 0.</p>
</form>