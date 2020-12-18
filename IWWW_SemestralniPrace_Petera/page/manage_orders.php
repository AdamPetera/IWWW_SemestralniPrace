<?php
    $orders = OrderController::getAllDispatchedOrdersFromDB();
    $non_orders = OrderController::getAllNonDispatchedOrdersFromDB();
?>

<div class="orders_wrap">
    <h2>Nevyřízené objednávky</h2>
    <form method="post">
        <table>
            <thead class="t_head">
            <tr>
                <td>Objednal/a</td>
                <td>Číslo objednávky</td>
                <td>Cena</td>
                <td>Datum</td>
                <td>Stav</td>
                <td>Zaplaceno</td>
                <td>Detail</td>
            </tr>
            </thead>
            <tbody class="t_body">
            <?php if (empty($non_orders)): ?>
                <tr>
                    <td colspan="6" style="text-align: center">V systému nejsou žádné nevyřízené objednávky</td>
                </tr>
            <?php else: ?>
                <?php foreach ($non_orders as $order): ?>
                    <tr>
                        <td><p><?=$order['firstname']?></p><p><?=$order['lastname']?></p></td>
                        <td class="order_number">
                            <a href="index.php?page=order_detail&order_number=<?=$order['order_number']?>"><?=$order['order_number']?></a>
                        </td>
                        <td class="price"><?=$order['price']?> Kč</td>
                        <td class="order_date"><?=$order['order_date']?></td>
                        <td class="order_state"><?=$order['human_readable']?></td>
                        <td class="order_paid"><?=(int) $order['paid'] == 0 ? 'NE' : 'ANO' ?></td>
                        <td class="detail_button">
                            <a href="index.php?page=order_detail&order_number=<?=$order['order_number']?>">Detail objednávky</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </form>
    <h2>Vyřízené objednávky</h2>
    <form method="post">
        <table>
            <thead class="t_head">
            <tr>
                <td>Objednal/a</td>
                <td>Číslo objednávky</td>
                <td>Cena</td>
                <td>Datum</td>
                <td>Stav</td>
                <td>Zaplaceno</td>
                <td>Detail</td>
            </tr>
            </thead>
            <tbody class="t_body">
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="6" style="text-align: center">V systému nejsou žádné vyřízené objednávky</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><p><?=$order['firstname']?></p><p><?=$order['lastname']?></p></td>
                        <td class="order_number">
                            <a href="index.php?page=order_detail&order_number=<?=$order['order_number']?>"><?=$order['order_number']?></a>
                        </td>
                        <td class="price"><?=$order['price']?> Kč</td>
                        <td class="order_date"><?=$order['order_date']?></td>
                        <td class="order_state"><?=$order['human_readable']?></td>
                        <td class="order_paid"><?=(int) $order['paid'] == 0 ? 'NE' : 'ANO' ?></td>
                        <td class="detail_button">
                            <a href="index.php?page=order_detail&order_number=<?=$order['order_number']?>">Detail objednávky</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>
