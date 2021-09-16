<?php

namespace GetNet\Parts;

use GetNet\Exception\SDKException;
use GetNet\GetNet;

/**
 * Create a transaction with GetNet
 */
class Transaction
{

    public const CURRENCY_BR = 'BRL';

    public const PRODUCT_TYPE_CASH_CARRY =  'cash_carry';
    public const PRODUCT_TYPE_DIGITAL_CONTENT =  'digital_content';
    public const PRODUCT_TYPE_DIGITAL_GOODS =  'digital_goods';
    public const PRODUCT_TYPE_DIGITAL_PHYSICAL =  'digital_physical';
    public const PRODUCT_TYPE_PHYSICAL_GOODS =  'physical_goods';
    public const PRODUCT_TYPE_RENEW_SUBS =  'renew_subs';
    public const PRODUCT_TYPE_SHAREWARE =  'shareware';
    public const PRODUCT_TYPE_SERVICE =  'service';

    public const ANTIFRAUD_ENV_TEST = '1snn5n9w';
    public const ANTIFRAUD_ENV_PRODUCTION = 'k8vif92e';

    public const TRANSACTION_TYPE_FULL = 'FULL';
    public const TRANSACTION_TYPE_INSTALL_NO_INTEREST = 'INSTALL_NO_INTEREST';
    public const TRANSACTION_TYPE_INSTALL_WITH_INTEREST = 'INSTALL_WITH_INTEREST';

    private $trackNumber;

    private $amount;
    private $currency;
    private $order;
    private $customer;
    private $device;
    private $shippings;
    private $subMerchant;
    private $paymentAttributes; // Credit and Debit

    private $antiFraud;
    private $deviceId;


    /** @var GetNet */
    private $getnet;

    function __construct(GetNet $getnet, string $trackNumber)
    {
        $this->getnet = $getnet;
        $this->trackNumber = $trackNumber;

        $this->loadCustomer();
    }

    /**
     * Load customer from getnet object data
     *
     * @return void
     */
    private function loadCustomer(): void
    {
        $customer = $this->getnet->getClientData();
        $this->customer = new \stdClass();
        $this->customer->customer_id = $customer->customerId;
        $this->customer->first_name = $customer->firstName;
        $this->customer->last_name = $customer->lastName;
        $this->customer->name = $customer->getFullName();
        $this->customer->email = $customer->email;
        $this->customer->document_type = $customer->documentType;
        $this->customer->document_number = $customer->documentNumber;
        $this->customer->phone_number = ($customer->celphoneNumber ?? $customer->phoneNumber);

        $address = $this->getnet->getClientAddress();
        $this->customer->billing_address = new \stdClass();
        $this->customer->billing_address->street = $address->street;
        $this->customer->billing_address->number = $address->number;
        $this->customer->billing_address->complement = $address->complement;
        $this->customer->billing_address->district = $address->district;
        $this->customer->billing_address->city = $address->city;
        $this->customer->billing_address->state = $address->state;
        $this->customer->billing_address->country = $address->country;
        $this->customer->billing_address->postal_code = $address->postalCode;
    }

    public function setAmount(float $amount): Transaction
    {
        $this->amount = number_format($amount, 2, '.', '') * 100;
        return $this;
    }

    public function setCurrency(string $currency): Transaction
    {
        $this->currency = $currency;
        return $this;
    }

    public function setOrder(string $orderId, float $salesTax, string $productType): Transaction
    {
        $this->order = new \stdClass();

        $this->order->order_id = $orderId;
        $this->order->sales_tax = $salesTax;
        $this->order->product_type = $productType;

        return $this;
    }

    public function setShipping(float $shippingAmount = 0): Transaction
    {

        $clientData = $this->getnet->getClientData();
        $clientAddress = $this->getnet->getClientAddress();

        $shipping = new \stdClass();
        $shipping = new \stdClass();
        $shipping->first_name = $clientData->firstName;
        $shipping->name = $clientData->getFullName();
        $shipping->email = $clientData->email;
        $shipping->phone_number = ($clientData->celphoneNumber ?? $clientData->phoneNumber);
        $shipping->shipping_amount = $shippingAmount * 100;

        $shipping->address = new \stdClass();
        $shipping->address->street = $clientAddress->street;
        $shipping->address->number = $clientAddress->number;
        $shipping->address->complement = $clientAddress->complement;
        $shipping->address->district = $clientAddress->district;
        $shipping->address->city = $clientAddress->city;
        $shipping->address->state = $clientAddress->state;
        $shipping->address->country = $clientAddress->country;
        $shipping->address->postal_code = $clientAddress->postalCode;

        if ($this->shippings && is_array($this->shippings)) {
            array_push($this->shippings, $shipping);
        } else {
            $this->shippings = array();
            $this->shippings[] = $shipping;
        }

        return $this;
    }

    /*
    public function setSubMerchant()
    {

    }
    */

    public function setPaymentAttributes(
        bool $delayed,
        bool $preAuthorization,
        bool $saveCardData,
        string $transactionType,
        int $numberInstallments,
        string $softDescriptor,
        int $dynamicMcc = null
    ): Transaction {

        $this->paymentAttributes = new \stdClass();
        $this->paymentAttributes->delayed = $delayed;
        $this->paymentAttributes->pre_authorization = $preAuthorization;
        $this->paymentAttributes->save_card_data = $saveCardData;
        $this->paymentAttributes->transaction_type = $transactionType;
        $this->paymentAttributes->number_installments = $numberInstallments;
        $this->paymentAttributes->soft_descriptor = $softDescriptor;
        $this->paymentAttributes->dynamic_mcc = $dynamicMcc;

        $clientPaymentMethod = $this->getnet->getClientMethodPayment();
        $this->paymentAttributes->card = new \stdClass();
        $this->paymentAttributes->card->number_token = $clientPaymentMethod->tokenCard;
        $this->paymentAttributes->card->cardholder_name = $clientPaymentMethod->cardHolderName;
        $this->paymentAttributes->card->security_code = $clientPaymentMethod->securityCode;
        $this->paymentAttributes->card->brand = $clientPaymentMethod->brand;
        $this->paymentAttributes->card->expiration_month = $clientPaymentMethod->expirationMonth;
        $this->paymentAttributes->card->expiration_year = $clientPaymentMethod->expirationYear;

        return $this;
    }

    // Criar um validator para validar os campos antes de executar a transação...

    public function runWithAntiFraud(string $identityCompany)
    {

        if (!$identityCompany) {
            throw new SDKException("For antifraud transaction, inform a identity company data (CNPJ or company code)");
        }

        $this->antiFraud = true;

        $this->device = new \stdClass();
        $this->device->ip_address = $this->getnet->getClientData()->ipv4Access;
        $this->device->device_id = preg_replace('/[^a-zA-Z0-9\-\_]/i', '', $identityCompany . '_' . $this->trackNumber);

        $env = $this->getnet->getEnv() === GetNet::ENV_PRODUCTION ? self::ANTIFRAUD_ENV_PRODUCTION : self::ANTIFRAUD_ENV_TEST;

        $client = new \GuzzleHttp\Client();
        $response = $client->get("https://h.online-metrix.net/fp/tags.js?org_id={$env}&session_id={$this->device->device_id}");

        if ($response->getStatusCode() != 200) {
            throw new SDKException("Failed on create a antifraud transaction. Reason: " . $response->getReasonPhrase());
        }

        return $this->execute();
    }

    public function run()
    {
        $this->antiFraud = false;

        return $this->execute();
    }

    private function execute()
    {
        if ($this->antiFraud) {
            // validate field with antifraud
        } else {
            // validate fields without antifraud
        }

        // Mount JSON
        $json = [
            'seller_id' => $this->getnet->getSellerId(),
            'amount' => $this->amount,
            'currency' => $this->currency,
            'order' => (array)$this->order,
            'customer' => (array)$this->customer
        ];

        $json['customer']['billing_address'] = (array)$json['customer']['billing_address'];

        if ($this->device) {
            $json['device'] = (array)$this->device;
        }

        if ($this->shippings) {
            $json['shippings'] = [];
            foreach ($this->shippings as $key => $shipping) {
                $json['shippings'][$key] = (array)$shipping;
                $json['shippings'][$key]['address'] = (array)$json['shippings'][$key]['address'];
            }
        }

        // if($this->subMerchant){
        //     $json['sub_merchant'] = (array)$this->subMerchant;
        // }

        $paymentMethod = $this->getnet->getClientMethodPayment()->getType();
        $json[$paymentMethod] = (array)$this->paymentAttributes;
        $json[$paymentMethod]['card'] = (array)$json[$paymentMethod]['card'];

        $body = json_encode($json);

        $res = $this->getnet->getRequester()->makeRequest('POST', '/v1/payments/credit', [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization' => $this->getnet->getAuth()->getAuthorization()
            ],
            'body' => $body
        ]);

        if (!$res) {
            throw new SDKException("Fail on make request: " . $this->getnet->getRequester()->getError());
        }

        $res = $this->getnet->getRequester()->getRespose();

        echo '<pre>';
        var_dump($res, $json);
        die;
    }
}
