<?php

namespace Helpers;

class Picpay
{
    private $apiUrl;
    private $headers;
    private $endPoint;
    private $params;
    private $callback;
    private $token;

    public function __construct()
    {
        $this->apiUrl = 'https://appws.picpay.com/ecommerce/public';
        $this->token = 'SEU TOKEN';
        $this->headers = [
            'Content-Type: application/json',
            "x-picpay-token: {$this->token}",
        ];
    }

    public function payments($order, $callback, $returnUrl, $value, $firstName, $lastName, $document, $email, $phone)
    {
        $this->endPoint = '/payments';
        $this->params = [
            'referenceId' => $order,
            'callbackUrl' => $callback,
            'returnUrl' => $returnUrl,
            'value' => $value,
            'buyer' => [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'document' => $document,
                'email' => $email,
                'phone' => $phone,
            ]
        ];

        $this->post();

        return $this->callback;
    }

    public function cancel($referenceId)
    {
        $this->endPoint = "/payments/{$referenceId}/cancellations";

        $this->post();

        return $this->callback;
    }

    public function status($referenceId)
    {
        $this->endPoint = "/payments/{$referenceId}/status";

        $this->get();

        return $this->callback;
    }

    private function post()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . $this->endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->params),
            CURLOPT_HTTPHEADER => $this->headers,
        ]);
        $this->callback = json_decode(curl_exec($curl));
        curl_close($curl);
    }

    private function get()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . $this->endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $this->headers,
        ]);
        $this->callback = json_decode(curl_exec($curl));
        curl_close($curl);
    }

}
