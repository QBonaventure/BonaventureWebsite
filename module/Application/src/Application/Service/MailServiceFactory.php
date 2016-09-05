<?php
namespace Application\Service;

use Application\Service\MailService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class MailServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$configuration = $serviceLocator->get('Configuration');

		$transport = new SmtpTransport();
		$options   = new SmtpOptions($configuration['mail']);
		$transport->setOptions($options);

		return new MailService($transport);
	}
}
