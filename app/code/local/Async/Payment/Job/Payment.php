<?php

class Async_Payment_Job_Payment extends Zendserver_Jobqueue_JobAbstract
{
	
	private $expiryMonth;
	private $expiryYear;
	private $type;
	private $ccnum;
	private $method;
	private $session;
	private $response;
	
	public function __construct($expireMonth, $expireYear, $ccnum, $type, $method, $sessionCookie)
	{
		$this->expiryMonth = $expireMonth;
		$this->expiryYear = $expireYear;
		$this->type = $type;
		$this->ccnum = $ccnum;
		$this->method = $method;
		$this->session = $sessionCookie;
	}
	
	public function getResponse()
	{
		return $this->response;
	}

    public function job()
    {
    	$http = new Zend_Http_Client(
    		Mage::getUrl(
    			'checkout/onepage/saveOrder',
    			array('execInline' => 1)
    		)
    	);
    	$http->setMethod('POST');
    	$http->setHeaders('Content-Type', 'application/x-www-form-urlencoded');
    	$http->setHeaders('Cookie', 'frontend=' . $this->session);
    	$http->setParameterPost(
    		'payment',
    		array(
    			'cc_exp_month'	=> $this->expiryMonth,
				'cc_exp_year'	=> $this->expiryYear,
				'cc_number'		=> $this->ccnum,
				'cc_type'		=> $this->type,
				'method'		=> $this->method
    		)
    	);
    	
    	$this->response = $http->request()->getBody();
    }
	
}