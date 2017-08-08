# PepGateway
PepGateway provides Pardakht Electronic Pasargad (_PEP_) internet payment gateway (_IPG_) code for using in PHP projects.  
This package is base on PEP company sample code [(pep-phpsample(v3.3.3).rar)](https://pep.co.ir//uploads/pep-phpsample(v3.3.3).rar) that is provided in their website.  
For more information you can see their documentation at [pep.co.ir](https://www.pep.co.ir/ipg/).    
## Usage

`$ composer require "gdpa/pep-gateway":"dev-master"`

Create pep-certificate.xml in your root directory and provide your private key provided by PEP company in it.

In your classes you can use package this way:

```PHP 
use gdpa\PepGateway;
class payment
{
    protected $merchantCode; // Your merchand code
    protected $terminalCode; // Your terminal code
    protected $certificate; // Path to certificate xml
    
    public function buySomething()
    {
        $gateway = new PepGateway($this->merchantCode, $this->terminalCode, $this->certificate);
        $buyHiddenFields = $gateway->buy($invoiceNumber, $invoiceDate, $amount, $redirectAddress, $timestamp);
        ...
    }
    
    public function verifyPurchase()
    {
        $gateway = new PepGateway($this->merchantCode, $this->terminalCode, $this->certificate);
        $verify = $gateway->verify($invoiceNumber, $invoiceDate, $amount, $timestamp);
    }
    
    public function refundTransaction()
    {
        $gateway = new PepGateway($this->merchantCode, $this->terminalCode, $this->certificate);
        $refund = $gateway->refund($invoiceNumber, $invoiceDate, $amount, $timestamp);
    }
    
    public function check()
    {
        $gateway = new PepGateway($this->merchantCode, $this->terminalCode, $this->certificate);
        $check = $gateway->check($invoiceNumber, $invoiceDate);
    }
}
```  
* `$buyHiddenFields` is contain all necessary fields for using in sending user to gateway:
    * action (_Using in form action_)
    * merchantCode
    * terminalCode
    * invoiceNumber
    * invoiceDate
    * amount
    * redirectAddress (_Your redirect address that you implement settlement codes_)
    * timestamp  
* `$verify` values:
    * result (true or false)
    * resultMessage
* `$refund` values:
    * result (true or false)
    * resultMessage
* `$check` values:
    * result (true or false)
    * resultMessage

