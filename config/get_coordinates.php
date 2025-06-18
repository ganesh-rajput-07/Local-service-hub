<?php
function getCoordinates($pincode) {
    $url = "https://nominatim.openstreetmap.org/search?postalcode=" . urlencode($pincode) . "&format=json&limit=1";

    $options = [
        "http" => [
            "header" => "User-Agent: LocalServiceHub/1.0\r\n"
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response === FALSE) {
        return null;
    }

    $data = json_decode($response, true);

    if (isset($data[0])) {
        return [
            'lat' => $data[0]['lat'],
            'lon' => $data[0]['lon']
        ];
    } else {
        return null;
    }
}
?>
