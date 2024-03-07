<?php
require_once("lib/Conekta.php");
\Conekta\Conekta::setApiKey("key_pq42sBrvzq9u0WnQ6aZvtrg");
\Conekta\Conekta::setApiVersion("2.0.0");

try {
    $thirty_days_from_now = (new DateTime())->add(new DateInterval('P30D'))->getTimestamp();
    
    $order = \Conekta\Order::create([
        "line_items" => [
            [
                "name" => "Zapatos adidas",
                "unit_price" => 1500*100,
                "quantity" => 1
            ]
        ],
        "shipping_lines" => [
            [
                "amount" => 1500,
                "carrier" => "DHL"
            ]
        ],
        "currency" => "MXN",
        "customer_info" => [
            "name" => "Aaron de Jesus Cordoba",
            "email" => "cordobamolinaaaron@gmail.com",
            "phone" => "+525546475229"
        ],
        "shipping_contact" => [
            "address" => [
                "street1" => "Calle 123, int 2",
                "postal_code" => "06100",
                "country" => "MX"
            ]
        ],
        "charges" => [
            [
                "payment_method" => [
                    "type" => "oxxo_cash",
                    "expires_at" => $thirty_days_from_now
                ]
            ]
        ]
    ]);

    var_dump(json_decode($order));  
    
    echo "ID: ". $order->id;
    echo "Payment Method:". $order->charges[0]->payment_method->service_name;
    echo "Reference: ". $order->charges[0]->payment_method->reference;
    echo "$". $order->amount/100 . $order->currency;
    echo "Order";
    echo $order->line_items[0]->quantity .
          "-". $order->line_items[0]->name .
          "- $". $order->line_items[0]->unit_price/100;

} catch (\Conekta\ParameterValidationError $error) {
    echo $error->getMessage();
} catch (\Conekta\Handler $error) {
    echo $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detalle de Orden</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="opps">
    <?php if ($order !== null): ?>
        <div class="opps-header">
            <div class="opps-reminder">Ficha digital. No es necesario imprimir.</div>
            <div class="opps-info">
                <div class="opps-brand"><img src="oxxopay_brand.png" alt="OXXOPay"></div>
                <div class="opps-ammount">
                    <h3>Monto a pagar</h3>
                    <h2>$<?php echo number_format($order->amount/100, 2) ?> <sup>MXN</sup></h2>
                    <p>OXXO cobrará una comisión adicional al momento de realizar el pago.</p>
                </div>
            </div>
            <div class="opps-reference">
                <h3>Referencia</h3>
                <h1><?php echo $order->charges[0]->payment_method->reference ?></h1>
            </div>
        </div>
    <?php else: ?>
        <p>No se pudo obtener la información de la orden.</p>
    <?php endif; ?>
    <div class="opps-instructions">
        <h3>Instrucciones</h3>
        <ol>
            <li>Acude a la tienda OXXO más cercana. <a href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala aquí</a>.</li>
            <li>Indica en caja que quieres realizar un pago de servicio<strong></strong>.</li>
            <li>Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la pantalla de venta.</li>
            <li>Realiza el pago correspondiente con dinero en efectivo.</li>
            <li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
        </ol>
        <div class="opps-footnote">Al completar estos pasos recibirás un correo de <strong>Nombre del negocio</strong> confirmando tu pago.</div>
    </div>
</div>
</body>
</html>
