<!DOCTYPE html>
<html>
    <header>
        <title>HouseLedger</title>
        <?php include 'header.php'; //Header?>
    </header>

    <?php

        try {
            include 'db_connect.php';
            $conn = OpenCon();
        }

        catch (mysqli_sql_Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user_id = $_SESSION["UserSession"];

        $owed = [];

        $sql = "
            SELECT 
                r.user_id AS payer_id,
                o.user_id AS ower_id,
                i.item_id,
                i.item_cost
            FROM 
                items i
            JOIN receipts r ON i.receipt_id = r.receipt_id
            JOIN opt_ins_outs o ON o.item_id = i.item_id
            WHERE o.opt_val = 1
        ";

        $result = $conn->query($sql);
        $itemShares = [];

        foreach ($result as $row) {
            $item_id = $row['item_id'];
            $payer_id = $row['payer_id'];
            $ower_id = $row['ower_id'];
            $item_cost = $row['item_cost'];

            if (!isset($itemShares[$item_id])) {
                $itemShares[$item_id] = [
                    'cost' => $item_cost,
                    'payer' => $payer_id,
                    'owers' => []
                ];
            }
            $itemShares[$item_id]['owers'][] = $ower_id;
        }

        foreach ($itemShares as $item) {
            $share = $item['cost'] / count($item['owers']);
            foreach ($item['owers'] as $ower_id) {
                if ($ower_id == $item['payer']) continue;

                $owed[$ower_id][$item['payer']] = ($owed[$ower_id][$item['payer']] ?? 0) + $share;
            }
        }

        /*foreach ($owed as $ower => $payers) {
            foreach ($payers as $payer => $amount) {
                echo "User $ower owes User $payer: $" . number_format($amount, 2) . "<br>";
            }
        }*/

        $net = [];

        foreach ($owed as $ower => $payers) {
            foreach ($payers as $payer => $amount) {
                if (!isset($net[$ower][$payer])) $net[$ower][$payer] = 0;
                if (!isset($net[$payer][$ower])) $net[$payer][$ower] = 0;
                
                $net[$ower][$payer] += $amount;
                $net[$payer][$ower] -= $amount;
            }
        }

        $finalBalances = [];

        foreach ($net as $userA => $others) {
            foreach ($others as $userB => $amount) {
                if ($userA < $userB) {
                    if ($amount > 0) {
                        $finalBalances[] = [
                            'from' => $userA,
                            'to' => $userB,
                            'amount' => round($amount, 2)
                        ];
                    } elseif ($amount < 0) {
                        $finalBalances[] = [
                            'from' => $userB,
                            'to' => $userA,
                            'amount' => round(abs($amount), 2)
                        ];
                    }
                }
            }
        }

        $user_names = [];
        $result = $conn->query("SELECT user_id, user_name FROM users");

        while ($row = $result->fetch_assoc()) {
            $user_names[$row['user_id']] = $row['user_name'];
        }

        /*foreach ($finalBalances as $entry) {
            $fromName = $user_names[$entry['from']] ?? "User {$entry['from']}";
            $toName = $user_names[$entry['to']] ?? "User {$entry['to']}";
            echo "{$fromName} owes {$toName}: $". number_format($entry['amount'], 2) . "<br>";
        }*/
    ?>

    <h2>View Balances</h2>
    <table class="balances-table">
        <thead>
            <tr>
                <th>Owed</th>
                <th>Owes</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($finalBalances as $row): ?>
                <?php if ($row['amount'] != 0): ?>
                    <tr>
                    <td><?= htmlspecialchars($user_names[$row['to']] ?? "User {$row['to']}") ?></td>
                    <td><?= htmlspecialchars($user_names[$row['from']] ?? "User {$row['from']}") ?></td>
                    <td>$<?= number_format($row['amount'], 2) ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>


</html>