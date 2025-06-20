<!DOCTYPE html>
<?php
    $db_success = FALSE;
    
    try {
        include 'db_connect.php';
        $conn = OpenCon();
        //echo "Connection successful!";
        $chosenId = $_GET['modifyReceipt'];
        $result1 = $conn->query("SELECT * FROM receipts WHERE receipt_id=$chosenId");
        foreach ($result1 as $row1) {
            $store_name = $row1['store_name'];
            $receipt_date = $row1['receipt_date'];
            //echo $receipt_date;
            //$items = $row['items'];
        }
        $result2 = $conn->query("SELECT * FROM items WHERE receipt_id=$chosenId");
        $items = [];
        if ($result2 && $result2->num_rows > 0) {
            while ($row = $result2->fetch_assoc()) {
                $items[] = $row;
            }
        }
    }


    catch (mysqli_sql_Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $user_id = $_SESSION["UserSession"];

    if(isset($_POST['submit'])) {
        $store_name = $_POST['store_name'];
        $receipt_date = $_POST['receipt_date'];
        $items = $_POST['items'];
        $error_msg = "";

        if($store_name == "" || $receipt_date == "" || $items == "") {
            $error_msg .= "<br /> Error: Please fill out all fields";
        }

        if ($error_msg == "") {
            $sql = "UPDATE receipts SET user_id = ? ,store_name = ? ,receipt_date = ?
                                            WHERE receipt_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issi",$user_id,$store_name,$receipt_date,$chosenId);
            $stmt->execute();
            $receipt_id = $stmt->insert_id;
            //echo "New records created successfully!";
            $stmt->close();
            $db_success = TRUE;
        }
        else {
            echo $error_msg;
        }

        if($db_success == TRUE) {
            echo "Receipt successfully modified in the Database!";

            $sql = "DELETE FROM items WHERE receipt_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i",$chosenId);
            $stmt->execute();
            $stmt->close();

            $sql = "DELETE FROM opt_ins_outs WHERE receipt_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i",$chosenId);
            $stmt->execute();
            $stmt->close();

            $items = $_POST['items'];

            foreach ($items as $item) {
                //echo "Item: " . htmlspecialchars($item['name']) . " - $" . number_format($item['cost'], 2) . "<br>";

                $item_name = $item['item_name'];
                $item_cost = $item['item_cost'];

                $stmt = $conn->prepare("INSERT INTO items (receipt_id,item_name,item_cost)
                                            VALUES (?,?,?)");
                $stmt->bind_param("isd",$chosenId,$item_name,$item_cost);
                $stmt->execute();
                $item_id = $stmt->insert_id;
                //echo "New records created successfully!";
                $stmt->close();
                
                $bool = true;
                $stmt = $conn->prepare("INSERT INTO opt_ins_outs (item_id,receipt_id,user_id,opt_val)
                                            VALUES (?,?,?,?)");
                $stmt->bind_param("iiii",$item_id,$chosenId,$user_id,$bool);
                $stmt->execute();
                $stmt->close();
            }

            echo "Items successfully modified in the Database!";
        }
    }

    if(isset($_POST['delete'])) {
        //echo 'receipt deleted';
        $sql = "DELETE FROM receipts WHERE receipt_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i",$chosenId);
        $stmt->execute();
        $stmt->close();
        echo "Receipt successfully deleted in the Database!";
        header("Location: view_receipts.php");
    }
?>

<html>
    
    <header>
        <title>HouseLedger</title>
        <?php include 'header.php'; //Header?>
    </header>

    <!--link rel="stylesheet" href="../css/master.css" -->
    <h>
        <p>Modify Receipt<br/></p>
    </h>
    
    <body>
        <form id="modify_receipt" name="modify_receipt" method="post" enctype="multipart/form-data">
            <label for="store_name">Store Name</label>
            <input type="text" id="store_name" name="store_name" value="<?php echo $store_name; ?>" required> <! adding name= lets the word username be clicked on site>
            <br />

            <label for="receipt_date">Date of Purchase</label>
            <input type="date" id="receipt_date" name="receipt_date" value="<?= htmlspecialchars($receipt_date) ?>" required>
            <br />

            <h3>Items</h3>
            <div id="itemsContainer">
                <?php 
                //print_r($items);
                foreach ($items as $index => $item): ?>
                <div class="item">
                    <input type="text" name="items[<?= $index ?>][item_name]" value="<?= $item['item_name'] ?>" required>
                    <input type="number" name="items[<?= $index ?>][item_cost]" value="<?= $item['item_cost'] ?>" step="0.01" required>
                    <button type="button" onclick="removeItem(this)">Remove</button>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" onclick="addItem()">+ Add Item</button>
            

            <input type="submit" id="submit" name="submit" value="Submit"></input>
        </form>
        <form method="post" action="">
            <input type="hidden" name="delete" value="delete">
            <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this item?')">Delete Receipt</button>
        </form>
        <script>
            let itemCount = <?= count($items) ?>;

            function addItem() {
            const container = document.getElementById('itemsContainer');
            const div = document.createElement('div');
            div.className = 'item';
            div.innerHTML = `
                <input type="text" name="items[${itemCount}][item_name]" placeholder="Item Name" required>
                <input type="number" name="items[${itemCount}][item_cost]" placeholder="Cost" step="0.01" required>
                <button type="button" onclick="removeItem(this)">Remove</button>
            `;
            container.appendChild(div);
            itemCount++;
            }

            function removeItem(button) {
            button.parentElement.remove();
            }
        </script>
    </body>
</html>