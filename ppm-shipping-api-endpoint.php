<?php

/**
 * Register POST /ppm/v1/update-shipments, with a body like the following:
 * 
 *  {
 *   "OrderId": "000000001",
 *   "TrackingNumber": "trackNumber",
 *   "Carrier": "carrier",
 *   "LineItems": [
 *     { "ProductId": "ppmsku", "Quantity": 1, "LotNumber": "12345", "SerialNumber": "ABCXYZ" },
 *     { "ProductId": "24-MB04", "Quantity": 2, "LotNumber": "678", "SerialNumber": "" },
 *   ]
 * }
 * 
 * Upon receiving an update, we:
 * 1. Fetch the order
 * 2. Add tracking info
 * 3. Fetch Line Items
 * 4. Add notes around lot number and serial number
 */

 add_action("rest_api_init", function() {
    register_rest_route( 
        "ppm/v1/", 
        "/update-shipments", 
        array(
            "methods" => "POST",
            "callback" => "update_ppm_tracking_info",
        )
    );
 });

 function update_ppm_tracking_info(WP_REST_REQUEST $request) {
    $order_id = $request["OrderId"];
    $tracking_number = $request["TrackingNumber"];
    $carrier = $request["Carrier"];
    
    error_log("REQUEST RECEIVED!");
    error_log($order_id);
    error_log($tracking_number);
    error_log($carrier);

    $response = new WP_REST_Response(array("success" => TRUE));
    $response->set_status(200);
    return $response; 
 }