<?php
class SmsAlert extends ObjectModel
{
    /**
     * Method to call SMSto api
     * 
     * @author Panayiotis Halouvas <phalouvas@kainotomo.com>
     *
     * @param string $api_key
     * @param string $method
     * @param string $url
     * @param string|array|null $payload
     * @return string
     */
    public static function callSmsto(string $api_key, string $method, string $url, $payload = null)
    {

        if ($api_key == '' ) {
            throw new \Exception('No API/Secret key Provided');
        }
        $method = strtoupper($method);

        $curl = curl_init();
        $curlParams = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $api_key",
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ];

        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $curlParams[CURLOPT_CUSTOMREQUEST] = $method;
            $curlParams[CURLOPT_POSTFIELDS] = json_encode($payload);
        }

        curl_setopt_array($curl, $curlParams);

        $response = curl_exec($curl);

        $err = curl_error($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err) {
            throw new \Exception('Retry again.');
        }

        return $response;
    }
}