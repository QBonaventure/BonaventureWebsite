<?php
namespace Application\Service;

use Zend\Mail;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Protocol\Exception\RuntimeException;

class MailService implements MailServiceInterface
{
	protected $transport;
	
	public function __construct(Smtp $transport) {
		$this->transport = $transport;
	}
	
	public function send(Mail\Message $mail) {
		try {
    		$this->transport->send($mail);
		}
		catch(RuntimeException $e) {
			return false;
		}
		return true;
	}
	
	public function newMail($encoding = 'UTF-8') {
		return new Mail\Message($encoding);
	}
	
}