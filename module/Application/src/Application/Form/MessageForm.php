<?php
namespace Application\Form;
 
use Zend\Form\Form;
 
class MessageForm extends Form{
    public function __construct($name=null){
    	parent::__construct('message');

    	$this->add(array(
    			'name'	=> 'email',
    			'id'	=> 'email-input',
    			'type'	=> 'Text',
    			'attributes'	=> array(
    				'id'	=> 'email-input',	
    			),
    			'options'	=> array(
    				'label'	=> 'Votre email',	
    			),
    	));
    	
    	$this->add(array(
    			'name'	=> 'subject',
    			'id'	=> 'subject-input',
    			'type'	=> 'Text',
    			'attributes'	=> array(
    					'id'	=> 'subject-input',
    			),
    			'options'	=> array(
    					'label'	=> 'L\'objet de votre message',
    			),
    	));
    	
    	$this->add(array(
    			'name'	=> 'message',
    			'id'	=> 'message-input',
    			'type'	=> 'Textarea',
    			'attributes'	=> array(
    					'id'	=> 'message-input',
    			),
    			'options'	=> array(
    					'label'	=> 'Votre message',
    			),
    	));
    	
    	$this->add(array(
    			'name'	=> 'submit',
    			'type'	=> 'Submit', 'action' => 'mmm',
    			'attributes'	=> array(
    					'value'	=> 'Envoyer',
    					'id'	=> 'submitbutton',
    			),
    	));
    	
    }
    
}