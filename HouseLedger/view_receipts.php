<!DOCTYPE HTML>
<html>

    <header>
            <title>HouseLedger</title>
            <?php include 'header.php'; //Header?>
        </header>

    <!-- link rel="stylesheet" href="../css/master.css" -->    
    <body>
        <br />
        <h2>All Receipts</h2>
        <!-- label for="filter_by" id="filter_by_label" -->
        <!-- input type="text" id="filter_by" name="filter_by" -->
        <!-- input type="submit" id="filter_submit" value="Go" --> 
        <table id="ordertable"> <! https://datatables.net/extensions/select/examples/initialisation/checkbox.html and https://www.washington.edu/accesscomputing/webd2/student/unit2/module5/lesson1.html>
            <thead>
                <tr>
                    <th scope="col" id="date">Date</th>
                    <th scope="col" id="store">Store</th>
                    <th scope="col" id="purchaser">Receipt Owner</th>
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
                        $user_name_inner = "";
                        $receipt_cost = "";
                        $result1 = $conn->query("SELECT household_id FROM users WHERE user_id = $user_id");
                        foreach ($result1 as $row1) {
                            $user_household_id = $row1["household_id"];
                            $result2 = $conn->query("SELECT user_id,user_name FROM users WHERE household_id = $user_household_id");
                            foreach($result2 as $row2) {
                                $user_id_inner = $row2["user_id"];
                                $user_name_inner = $row2["user_name"];
                                $result3 = $conn->query("SELECT receipt_id,store_name,receipt_date FROM receipts WHERE user_id = $user_id_inner");
                                foreach($result3 as $row3) {
                                    $store_name = $row3["store_name"];
                                    $receipt_date = $row3["receipt_date"];
                                    $receipt_id_per = $row3["receipt_id"];
                                    $result4 = $conn->query("SELECT SUM(item_cost) as items_cost FROM items WHERE receipt_id = $receipt_id_per");
                                    foreach($result4 as $row4) {
                                        $receipt_cost = $row4["items_cost"];
                                    }
                                    echo '<tr>
                                    <td>' . $receipt_date . '</td>
                                    <td>' . $store_name . '</td>
                                    <td>' . $user_name_inner . '</td>
                                    <td> $' . $receipt_cost . '</td>
                                    <td> <a href="view_receipt.php?viewReceipt=' . $receipt_id_per . '">View Receipt</td>
                                </tr>';

                                }
                            }
                        }
                    }
                    catch (mysqli_sql_Exception $e) {
                        echo "Error: " . $e->getMessage();
                    }
                ?>
            </tbody>
        </table>
</html>