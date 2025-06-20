<!DOCTYPE HTML>
<html>

    <header>
            <title>HouseLedger</title>
            <?php include 'header.php'; //Header?>
        </header>

    <!-- link rel="stylesheet" href="../css/master.css" -->    
    <body>
        <br />
        <h2>Your Receipts</h2>

        <!-- label for="filter_by" id="filter_by_label" -->
        <!-- input type="text" id="filter_by" name="filter_by" -->
        <!-- input type="submit" id="filter_submit" value="Go" --> 
        <table id="ordertable"> <! https://datatables.net/extensions/select/examples/initialisation/checkbox.html and https://www.washington.edu/accesscomputing/webd2/student/unit2/module5/lesson1.html>
            <thead>
                <tr>
                    <th scope="col" id="date">Date</th>
                    <th scope="col" id="store">Store</th>
                    <th scope="col" id="total">Total</th>
                    <th scope="col" id="select_receipt">Select Receipt</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    try {
                        include 'db_connect.php';
                        $conn = OpenCon();
                        //echo "Successful Connection!";
                        //session_start();
                        $user_id = $_SESSION["UserSession"];
                        $receipt_date = "";
                        $store_name = "";
                        $receipt_cost = "";
                        $result = $conn->query("SELECT receipt_id,store_name,receipt_date FROM receipts WHERE user_id = $user_id");
                        foreach($result as $row) {
                            $store_name = $row["store_name"];
                            $receipt_date = $row["receipt_date"];
                            $receipt_id_per = $row["receipt_id"];
                            $result2 = $conn->query("SELECT SUM(item_cost) as items_cost FROM items WHERE receipt_id = $receipt_id_per");
                            foreach($result2 as $row2) {
                                $receipt_cost = $row2["items_cost"];
                            }
                            echo '<tr>
                                <td>' . $receipt_date . '</td>
                                <td>' . $store_name . '</td>
                                <td> $' . $receipt_cost . '</td>
                                <td> <a href="modify_receipt.php?modifyReceipt=' . $receipt_id_per . '">Modify Receipt</td>
                                </tr>';
                        }
                    }
                    catch (mysqli_sql_Exception $e) {
                        echo "Error: " . $e->getMessage();
                    }
                ?>
            </tbody>
        </table>
</html>