<!DOCTYPE html>
<?php
    $db_success = FALSE;
    
    try {
        include 'db_connect.php';
        $conn = OpenCon();
        //echo "Connection successful!";
        $chosenId = $_GET['viewReceipt'];
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user_id = $_SESSION["UserSession"];
        $result1 = $conn->query("SELECT * FROM receipts WHERE receipt_id=$chosenId");
        foreach ($result1 as $row1) {
            $store_name = $row1['store_name'];
            $receipt_date = $row1['receipt_date'];
            $purchaser_id = $row1['user_id'];
        }
        $items = [];
        $result2 = $conn->query("SELECT * FROM items WHERE receipt_id=$chosenId");
        foreach ($result2 as $row2) {
            $item_name = $row2['item_name'];
            $item_cost = $row2['item_cost'];
            $item_id = $row2['item_id'];
            
            $opt_val = 0;

            $result3 = $conn->query("SELECT opt_val FROM opt_ins_outs WHERE item_id = $item_id AND user_id = $user_id LIMIT 1");

            if ($result3 && $result3->num_rows > 0) {
                $opt_row = $result3->fetch_assoc();
                if ($opt_row && isset($opt_row['opt_val'])) {
                    $opt_val = $opt_row['opt_val'];
                }
            }
            $items[] = [
                'item_id'    => $item_id,
                'item_name'  => $row2['item_name'],
                'item_cost'  => $row2['item_cost'],
                'opt_val'    => $opt_val
            ];
        }
    }
    
    catch (mysqli_sql_Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    if (isset($_POST['submit'])) {
        $all_ids = [];
        foreach ($items as $item) {
            $all_ids[] = $item['item_id'];
        }
    
        $checked_ids = isset($_POST['selected_items']) ? $_POST['selected_items'] : [];
    
        

        foreach ($all_ids as $id) {
            $opt_val = in_array($id, $checked_ids) ? 1 : 0;
        
            $check_sql = "SELECT * FROM opt_ins_outs WHERE item_id = ? AND user_id = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("ii", $id, $user_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();

            if ($result->num_rows > 0) {
                $update_sql = "UPDATE opt_ins_outs SET opt_val = ? WHERE item_id = ? AND user_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("iii", $opt_val, $id, $user_id);
                $update_stmt->execute();
            }
            
            else {
                $insert_sql = "INSERT INTO opt_ins_outs (item_id, opt_val, user_id) VALUES (?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("iii", $id, $opt_val, $user_id);
                $insert_stmt->execute();
            }
        
            $db_success = true;

            if($db_success == TRUE) {
                echo "Preferences successfully updated in the Database!";
                header("Refresh:0");
            }
        }
    }


?>

<html>

    <header>
        <title>HouseLedger</title>
        <?php include 'header.php'; //Header?>
    </header>

    <body>
        <br />
        <!-- label for="filter_by" id="filter_by_label" -->
        <!-- input type="text" id="filter_by" name="filter_by" -->
        <!-- input type="submit" id="filter_submit" value="Go" --> 
        <h2>View Receipt</h2>
        <h3><?php echo $store_name, "   |   ", $receipt_date, " |   ", $purchaser_id ?></h3>
        <form id="modify_opts" name="modify_opts" method="post">
            <?php
                foreach ($items as $item): ?>
                <div class="item-row">
                    <label>
                    <input 
                        type="checkbox" 
                        name="selected_items[]" 
                        value="<?= $item['item_id'] ?>" 
                        <?= $item['opt_val'] == 1 ? 'checked' : '' ?>
                >
                    <?= htmlspecialchars($item['item_name']) ?> — $<?= number_format($item['item_cost'], 2) ?>
                    </label>
                </div>
            <?php endforeach; ?>
            <input type="submit" id="submit" name="submit" value="Submit">
        </form>