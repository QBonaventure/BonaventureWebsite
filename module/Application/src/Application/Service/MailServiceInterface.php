<?php
namespace Application\Service;


use Zend\Mail;

interface MailServiceInterface {
	/**
	 * Sends any type of email to the website administrator according to an autoload/mail.php
	 * config file
	 *
	 * @return boolean
	 */
	public function send(Mail\Message $mail);
	
	
	/**
	 * Returns a new instance of Zend\Mail
	 *
	 * @return Zend\Mail
	 */
	public function newMail($encoding);
	
	
}