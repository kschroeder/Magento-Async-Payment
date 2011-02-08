<?php

class Async_Payment_Model_Observer
{

	private $templates = null;
	
	public function injectJavaScript(Varien_Event_Observer $observer)
	{
		$action = $observer->getControllerAction();
		if ($action) {
			$request = $action->getRequest();
			if ($request->getParam('execInline') || !$this->doAsync()) {
				return;
			}
		}
		$template = $observer->getData('block')->getTemplate();
		if ($template !== null && in_array($template, $this->getTemplates())) {
			$transport = $observer->getData('transport');
			$html = $transport->getHtml();
			$transport->setHtml(
				$html
				. Mage::getSingleton('core/layout')->createBlock('async_payment_view_clickInterceptor')->toHtml()
			);
		}
	}
	
	public function extractPaymentProcess(Varien_Event_Observer $observer)
	{
		$action = $observer->getControllerAction();
		$request = $action->getRequest();
		if ($request->getParam('execInline') || !$this->doAsync()) {
			return;
		}
		
		$request->setPathInfo('async_payment/index/index');
		$request->setModuleName('Async_Payment')
				->setControllerName('index')
				->setActionName('index');
		$request->setDispatched(false);
	}
	
	private function doAsync()
	{
		return Mage::getStoreConfig('payment/settings/do_async') == 1;
	}
	
	private function getTemplates()
	{
		if ($this->templates === null) {
			$templates = Mage::getStoreConfig('payment/settings/template_inject');
			$this->templates = explode(',', $templates);
		}
		return $this->templates;
	}
	
}