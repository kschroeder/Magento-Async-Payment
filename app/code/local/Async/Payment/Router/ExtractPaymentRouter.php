<?php

class Async_Payment_Router_ExtractPaymentRouter extends Mage_Core_Controller_Varien_Router_Abstract
{

	public function match (Zend_Controller_Request_Http $request)
    {
    	$request->setDispatched(true);
    	return true;
    }
}