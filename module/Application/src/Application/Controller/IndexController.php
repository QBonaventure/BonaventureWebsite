<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Message;
use Application\Form\MessageForm;
use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Protocol\Exception\RuntimeException;
use Zend\Config\Config;

use Application\Service\MailServiceInterface;

class IndexController extends AbstractActionController
{
	protected $messageTable;
	protected $mailService;

	public function __construct(MailServiceInterface $mailService) {
		$this->mailService	= $mailService;
	}
	
	
    public function indexAction()
    {
    }
    
    public function curriculumVitaeAction() {
    	$f	= 'curriculum-vitae';
    	return new ViewModel(array('mlass', $f));
    }
    
    public function biographyAction() {
    }
    
    public function contactAction() {
    	$form	= new MessageForm();
    	$request = $this->getRequest();

    	if ($request->isPost()) {

    		$message	= new Message();
    		$form->setInputFilter($message->getInputFilter());
    		$form->setData($request->getPost());
    		
    		if($form->isValid()) {
    			$message->exchangeArray($form->getData());
    			$message->isMailed($this->_sendEmail($message));
    			$this->getMessageTable()->saveMessage($message);
    		
    		$elements = $form->getElements();

			foreach ($elements as $element) {
				if ($element instanceof \Zend\Form\Element\Text or
					$element instanceof \Zend\Form\Element\Textarea)
					$element->setValue('');
			}
    			
    		}
    	}
    	return new ViewModel(array(
    			'form'	=> $form,
    	));
    }
    
    public function getMessageTable(){
    	if(!$this->messageTable){
    		$sm = $this->getServiceLocator();
    		$this->messageTable = $sm->get("Application\Model\MessageTable");
    		return $this->messageTable;
    	}
    }
    
    private function _sendEmail($message) {
    	$mail = $this->mailService->newMail();
    	$mail->setBody($message->message);
    	$mail->setFrom($message->email);
		$mail->addTo('quentin@bonaventure.xyz');
    	$mail->setSubject($message->subject);

		return $this->mailService->send($mail);
    }
    
}
