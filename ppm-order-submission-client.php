<?php

/**
 * Upon an order being placed, we need to:
 * + Determine if it has any PPM items
 * + If so, grab just those items and quantities
 * + Use PPM SKU or fallback to regular SKU
 * + Package everything up and ship it to PPM
 * 
 * We add a note to the order if there are any interactions with PPM's API.
 */

function ppm_submit_order($order_id)
{
    $order = wc_get_order($order_id);

    $url = get_option("ppm_woo_api_url");
    $apiKey = get_option("ppm_woo_api_key");
    $ownerCode = get_option("ppm_woo_owner_code");

    $process = curl_init();

    $args = array(
        "ownerCode" => $ownerCode,
        "orderId" => $order_id,
        "url" => $url,
    );

    $curlOptions = array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $apiKey,
            "Content-Type: application/json"
        ],
        CURLOPT_POST => TRUE,
        CURLOPT_POSTFIELDS => json_encode($args),
        // CURLOPT_SSL_VERIFYPEER => FALSE,
    );

    curl_setopt_array($process, $curlOptions);

    $resultBody = curl_exec($process);

    $success = "true";
    $note = "";

    if (!curl_errno($process)) {
        switch ($http_code = curl_getinfo($process, CURLINFO_RESPONSE_CODE)) {
            case 200:
            case 201:
                $note = __("Successfully posted to PPM's API");
                break;
            default:
                $note = __("Failed to post to PPM's API - Please follow up with PPM");
                $success = "false";

        }
    } else {
        $success = "false";
        $note = __("Failed to connect to PPM's API - Error Code: " . curl_errno($process) . ". Please Check logs");
        error_log("Error while submitting order " . $order_id);
    }

    curl_close($process);

    $order->add_order_note($note);

    return array(
        "body" => $resultBody,
        "success" => $success,
        "url" => $url,
    );
}

add_action("woocommerce_order_status_processing", "ppm_submit_order", 10, 1);