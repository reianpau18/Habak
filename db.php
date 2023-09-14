<?php
$item_name = $_GET['item_name'];
$item_code = $_GET['item_code'];
$item_price = $_GET['item_price'];
$item_stock_count = $_GET['item_stock_count'];

$promo_approved = false;
$discount;
$_SESSION['item_price'];

//Product purchase table//
echo
    "<table>",
    "<tr>",
    "<th>Title</th>",
    "<th>Item Price</th>",
    "<th>Item Code</th>",
    "</tr>",
    "<tr>",
    "<th> $item_name  </th>",
    "<th> $item_price </th>",
    "<th> $item_code </th>",
    "</tr>",
    "</table>";


//Is the login variable set? error prevention//
if (isset($_SESSION['loggedin'])) {
    //Is the customer logged in?//
    if ($_SESSION['loggedin'] == true) {
        //Is the product in stock?//

        //Connect to db//
        $connect = mysqli_connect('localhost', 'root', 'root')
            or die("Failed to connect to MySQL Database:" . mysqli_connect_error);

        $db = mysqli_select_db($connect, 'sm17977')
            or die("Could not open the Database");

        if ($item_stock_count > 0) {
            //If Buy button has been clicked//
            if (isset($_POST['Buy'])) {
                //Reduce stock count by 1//

                //Stock reduction query//
                $reduce_stock =
                    "update inventory
                    set item_stock_count = $item_stock_count
                    where item_code = '$item_code'";

                mysqli_query($connect, $reduce_stock);

                echo "</br>Stock Count: " . $item_stock_count;
                $item_stock_count = $item_stock_count - 1;

                //Did the query update the stock?//
                if (mysqli_affected_rows($connect) == 1) {
                    echo "</br>Stock Updated.</br>";
                    echo "</br>New Stock Count: " . $item_stock_count;
                }
                //If the query did not update the stock//
                else {
                    echo "</br> Failed to update stock.";
                    echo mysqli_error($connect);
                    echo "</br>New Stock Count: " . $item_stock_count;
                }
            }
        }
        //If the item is not in stock//
        else {
            echo
                "<script>
                    alert('Item currently unavailable.');
            </script>";
        }
    }
    //If the user isn't logged in//
    else {
        echo
            "<p>You must be logged in to make purchases.</p>";
    }
}
?>
</br></br>
<form method="POST">
    </br>
    <p>Promotional Code:</p>
    <input id="code" type="text" name="promo" />
    </br>
    <input name="Buy" value="Buy" type="submit" id='purchase_btn' <?php if ($_SESSION['loggedin'] == false) { ?> disabled
        <?php } ?> />
</form>

</html>