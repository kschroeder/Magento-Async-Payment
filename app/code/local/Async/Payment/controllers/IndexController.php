<?php

use com\zend\jobqueue\Manager;
class Async_Payment_IndexController extends Mage_Core_Controller_Front_Action
{
	
	public function indexAction()
	{
		$res = array(
			'success'	=> false,
			'error'		=> false
		);
		echo json_encode($res);
	}
	
	public function taskexecAction()
	{
		$postData = $this->_request->getPost('payment');
		$job = new Async_Payment_Job_Payment(
			$postData['cc_exp_month'],
			$postData['cc_exp_year'],
			$postData['cc_number'],
			$postData['cc_type'],
			$postData['method'],
			$_COOKIE['frontend']
		);
		
		$response = $job->execute();
		Mage::getSingleton('core/session')->setJQResponse(
			$response
		);
		echo json_encode(
			array(
				'queued'	=> true
			)
		);		
	}
	
	public function oneclickpingAction()
	{
		$queue = new Manager();
		$response = Mage::getSingleton('core/session')->getJQResponse(); 
		if (($job = $queue->getCompletedJob($response)) !== null) {
			if ($job instanceof Async_Payment_Job_Payment) {
				echo $job->getResponse();
				return;
			} else {
				echo json_encode(
					array(
						'error'	=> true
					)
				);
				
			}
		}
		echo json_encode(
			array(
				'queued'	=> true
			)
		);
		
	}
	
}