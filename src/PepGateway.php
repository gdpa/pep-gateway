<?php

namespace gdpa\PepGateway;

use gdpa\PepGateway\RSA\RSAProcessor;
use gdpa\PepGateway\RSA\RSAKeyType;
use gdpa\PepGateway\RSA\Parser;

class PepGateway
{
    protected $processor;
    protected $merchantCode;
    protected $terminalCode;
    protected $buyAction = 1003;
    protected $refundAction = 1004;
    protected $gatewayUrl = 'https://pep.shaparak.ir/gateway.aspx';
    protected $checkURL = 'https://pep.shaparak.ir/CheckTransactionResult.aspx';
    protected $verifyUrl = 'https://pep.shaparak.ir/VerifyPayment.aspx';
    protected $refundUrl = 'https://pep.shaparak.ir/doRefund.aspx';

    public function __construct($merchantCode, $terminalCode)
    {
        $this->merchantCode = $merchantCode;
        $this->terminalCode = $terminalCode;
        $this->processor = new RSAProcessor("./pep-certificate.xml",RSAKeyType::XMLFile);
    }

    /**
     * @param $invoiceNumber
     * @param $invoiceDate
     * @param $amount
     * @param $redirectAddress
     * @param $timestamp
     * @return array
     */
    public function buy($invoiceNumber, $invoiceDate, $amount, $redirectAddress, $timestamp)
    {
        $action = $this->gatewayUrl;
        $sign = base64_encode($this->sign($invoiceNumber, $invoiceDate, $amount, $redirectAddress, $timestamp, $this->buyAction));
        return compact($action, $sign, $this->merchantCode, $this->terminalCode, $invoiceNumber, $invoiceDate, $amount, $redirectAddress, $timestamp);
    }

    /**
     * @param $invoiceNumber
     * @param $invoiceDate
     * @param $amount
     * @param $timestamp
     * @return array
     */
    public function verify($invoiceNumber, $invoiceDate, $amount, $timestamp)
    {
        $action = $this->gatewayUrl;
        $sign = base64_encode($this->sign($invoiceNumber, $invoiceDate, $amount, null, $timestamp, null));
        $fields = compact($action, $sign, $this->merchantCode, $this->terminalCode, $invoiceNumber, $invoiceDate, $amount, $timestamp);
        $result = Parser::post2https($fields, $this->verifyUrl);
        return Parser::makeXMLTree($result);
    }

    /**
     * @param $invoiceNumber
     * @param $invoiceDate
     * @param $amount
     * @param $timestamp
     * @return array
     */
    public function refund($invoiceNumber, $invoiceDate, $amount, $timestamp)
    {
        $sign = base64_encode($this->sign($invoiceNumber, $invoiceDate, $amount, null, $timestamp, $this->refundAction));
        $fields = compact($sign, $this->merchantCode, $this->terminalCode, $invoiceNumber, $invoiceDate, $amount, $timestamp);
        $result = Parser::post2https($fields, $this->refundUrl);
        return Parser::makeXMLTree($result);
    }

    /**
     * @param $invoiceNumber
     * @param $invoiceDate
     * @return array
     * @internal param $invoiceUID
     */
    public function check($invoiceNumber, $invoiceDate)
    {
        $fields = compact($invoiceNumber, $invoiceDate, $this->merchantCode, $this->terminalCode);
        $result = Parser::post2https($fields, $this->checkURL);
        return Parser::makeXMLTree($result);
    }

    /**
     * @param $invoiceNumber
     * @param $invoiceDate
     * @param $amount
     * @param $redirectAddress
     * @param $timestamp
     * @param $action
     * @return string
     */
    protected function sign($invoiceNumber, $invoiceDate, $amount, $redirectAddress = null, $timestamp , $action = null)
    {
        $redirectAddress = $redirectAddress ? '#' . $redirectAddress : '';
        $action = $action ? '#' . $action : '';

        return $this->processor->sign("#". $this->merchantCode
            . "#" . $this->terminalCode
            . "#" . $invoiceNumber
            . "#" . $invoiceDate
            . "#" . $amount
            . $redirectAddress
            . $action
            . "#" . $timestamp
            . "#");
    }
}
