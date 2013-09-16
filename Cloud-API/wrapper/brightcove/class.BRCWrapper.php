<?php

require_once('interface/interface.iWrapper.php');
require_once('exception/class.WrapperException.php');

class BRCWrapper
	implements iWrapper
{
	
	protected $parameters;
	
	protected $prefix = 'BRC';
	
	public function setParameters($parameters) {
		$this->parameters = $parameters;
	}
	
	public function callService($service, $method, $requestVO) {
		$class = $this->prefix.$service;
		switch($service) {
			case 'Channel' :
				$service = new $class();
				break;
			default :
				throw new WrapperException(500, "No service $service defined in ".__CLASS__);
				break;
		}
		if(!method_exists($service, $method)) {
			throw new WrapperException(500, "No method $method defined for service $service in ".__CLASS__);
		}
		$returnVO = $service->{$method}($requestVO);
		return $returnVO;
	}
	
}

