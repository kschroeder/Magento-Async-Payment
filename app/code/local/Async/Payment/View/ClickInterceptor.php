<?php

class Async_Payment_View_ClickInterceptor extends Mage_Core_Block_Template
{
	protected $_template = 'scriptInterceptor.phtml';
	
	public function renderView()
    {
    	if (!$this->_viewDir) {
    		$this->_viewDir = realpath(dirname(__FILE__) . '/../views/');
    	}
        return $this->fetchView($this->_template);
    }
}