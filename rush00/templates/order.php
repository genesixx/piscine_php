<?php
    include_once ("ressources/handle_db.php");
    include_once ("ressources/display_price.php");
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

    if (isset($_SESSION['login_id'])){
        $ret = query("SELECT * from `order` where order.user_id = $_SESSION[login_id] ");
        echo '<div class="ordered-list">';
        
        if (mysqli_num_rows($ret) > 0) {
            while($row = mysqli_fetch_assoc($ret)) {
                $orders = query("SELECT * from order_item where order_item.order_id = $row[id]");
                
                echo '<div class="order">';
                $total_price_q = query("SELECT SUM(order_item.quantity * p.price) as total_price from order_item  join product p on p.id = order_item.product_id where order_item.order_id = $row[id] GROUP BY order_item.order_id");
                if (mysqli_num_rows($total_price_q) > 0){
                    $total_price = display_price(mysqli_fetch_assoc($total_price_q)['total_price']);
                }
                else $total_price = display_price(0);
                echo <<<EOL
                <div>
                    <span>$row[date]</span>
                    <span class="order-price">Prix total de la commande: {$total_price}$</span>
                </div>
EOL;
                if (mysqli_num_rows($orders) > 0) {
                    while($products = mysqli_fetch_assoc($orders)) {
                        $product = query("SELECT * from product where id = $products[product_id]");
                        if (mysqli_num_rows($product) > 0) {
                            while($item = mysqli_fetch_assoc($product)) {
                             $price = display_price($item["price"]);
                                echo <<<OEL
                                <div class="item-ordered">
                                    <div class="description">
                                        <span>$item[name]</span>
                                        <span>quantité :$products[quantity] Prix: $item[price]$</span>
                                    </div>                                  
                                </div>
OEL;
                            }
                        }
                    }
                }
                echo "</div>";

            }
        }
        else
            echo "aucune commande";
        echo "</div>";
    }  
?>