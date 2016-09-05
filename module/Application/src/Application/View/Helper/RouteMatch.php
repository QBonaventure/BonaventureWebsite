<?php
namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;



class RouteMatch extends AbstractHelper
{

    public function __construct($routeMatch)
    {
        $this->routeMatch = $routeMatch;
    }
    
	public function __invoke($variable)
	{
		if($variable == 'controller' OR $variable == 'module')
		{
			$array	= explode("\\", $this->routeMatch->getParam('controller'));
			if($variable == 'controller')
				return end($array);
			else
				return $array[0];
		}
		elseif($variable == 'action') {
			return $this->routeMatch->getParam('action');
		}
		else {
			throw new Exception('Argument has to be set to "controller" or "action"');
		}
		
	}
}