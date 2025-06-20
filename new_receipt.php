<!DOCTYPE html>
<?php
    $db_success = FALSE;
    
    try {
        include 'db_connect.php';
        $conn = OpenCon();
        //echo "Connection successful!";
        
    }


    catch (mysqli_sql_Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $user_id = $_SESSION["UserSession"];
    $store_name = "";
    $receipt_date = "";
    $items = "";

    if(isset($_POST['submit'])) {
        $store_name = $_POST['store_name'];
        $receipt_date = $_POST['receipt_date'];
        $items = $_POST['items'];
        $error_msg = "";

        if($store_name == "" || $receipt_date == "" || $items == "") {
            $error_msg .= "<br /> Error: Please fill out all fields";
        }

        if ($error_msg == "") {
            $stmt = $conn->prepare("INSERT INTO receipts (user_id,store_name,receipt_date)
                                            VALUES (?,?,?)");
            $stmt->bind_param("iss",$user_id,$store_name,$receipt_date);
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
            echo "Receipt successfully added to the Database!";

            $items = $_POST['items'];

            foreach ($items as $item) {
                //echo "Item: " . htmlspecialchars($item['name']) . " - $" . number_format($item['cost'], 2) . "<br>";

                $item_name = $item['item_name'];
                $item_cost = $item['item_cost'];

                $stmt = $conn->prepare("INSERT INTO items (receipt_id,item_name,item_cost)
                                            VALUES (?,?,?)");
                $stmt->bind_param("isd",$receipt_id,$item_name,$item_cost);
                $stmt->execute();
                $item_id = $stmt->insert_id;
                //echo "New records created successfully!";
                $stmt->close();
                
                $bool = true;
                $stmt = $conn->prepare("INSERT INTO opt_ins_outs (item_id,receipt_id,user_id,opt_val)
                                            VALUES (?,?,?,?)");
                $stmt->bind_param("iiii",$item_id,$receipt_id,$user_id,$bool);
                $stmt->execute();
                $stmt->close();
            }

            echo "Items successfully added to the Database!";
        }
    }
?>

<html>
    
    <header>
        <title>HouseLedger</title>
        <?php include 'header.php'; //Header?>
    </header>

    <!--link rel="stylesheet" href="../css/master.css" -->
    <h>
        <p>Create Receipt<br/></p>
    </h>
    
    <body>
        <form id="create_receipt" name="create_receipt" method="post" enctype="multipart/form-data">
            <label for="store_name">Store Name</label>
            <input type="text" id="store_name" name="store_name" value="<?php if(isset($store_name)){echo $store_name;}?> " required> <! adding name= lets the word username be clicked on site>
            <br />

            <label for="receipt_date">Date of Purchase</label>
            <input type="date" id="receipt_date" name="receipt_date" value="<?php if(isset($receipt_date)){echo $receipt_date;}?> "required>
            <br />

            <h3>Items</h3>
            <div id="itemsContainer"></div>
            <button type="button" onclick="addItem()">+ Add Item</button><br><br>

            <input type="submit" id="submit" name="submit" value="Submit"></input>
        </form>

        <script>
        let itemCount = 0;

        function addItem() {
            const container = document.getElementById('itemsContainer');

            const itemDiv = document.createElement('div');
            itemDiv.className = 'item';
            itemDiv.innerHTML = `
            <input type="text" name="items[${itemCount}][item_name]" placeholder="Item Name" required>
            <input type="number" name="items[${itemCount}][item_cost]" placeholder="Cost" step="0.01" required>
            <button type="button" onclick="removeItem(this)">Remove</button>
            `;
            container.appendChild(itemDiv);
            itemCount++;
        }

        function removeItem(button) {
            const itemDiv = button.parentElement;
            itemDiv.remove();
        }
        </script>

    </body>
</html>