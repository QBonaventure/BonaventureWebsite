<?php
namespace Application\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\EmailAddress;
use Zend\Validator\Hostname;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class Message {
	public $id;
	public $subject;
	public $message;
	public $email;
	public $isMailed = false;
	protected $inputFilter;
	
	public function isMailed($isMailed = true) {
		$this->isMailed = $isMailed;
	}
	
	public function exchangeArray($data)
	{
		$this->id		= (!empty($data['id'])) ? $data['id'] : null;
		$this->subject	= (!empty($data['subject'])) ? $data['subject'] : null;
		$this->message	= (!empty($data['message'])) ? $data['message'] : null;
		$this->email	= (!empty($data['email'])) ? $data['email'] : null;
	}
	
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}
	
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
	
			$inputFilter->add(array(
					'name'     => 'email',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
		                    array(
		                      'name' =>'NotEmpty', 
		                        'options' => array(
		                            'messages' => array(
		                                NotEmpty::IS_EMPTY => 'Vous avez oublié votre email !' 
		                            ),
									'break_chain_on_failure'	=> true,
		                        ),
		                    ),
							array(
									'name'	=> 'EmailAddress',
									'options'	=> array(
										'deep'	=> true,
										'domain'	=> true,
										'mx'	=> true,
										'useMxCheck' => true,
 										'message'	=> array('Votre adresse email semble incorrect.'
 										),
									),
							),
					),
			));
	
			$inputFilter->add(array(
					'name'     => 'subject',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
		                    array(
		                      'name' =>'NotEmpty', 
		                        'options' => array(
		                            'messages' => array(
		                                NotEmpty::IS_EMPTY => 'Vous avez oublié l\'objet votre message !' 
		                            ),
									'break_chain_on_failure'	=> true,
		                        ),
		                    ),
							array(
								'name'    => 'StringLength',
								'options' => array(
										'encoding' => 'UTF-8',
										'min'      => 2,
										'max'      => 100,
										'messages'	=> array(
        									StringLength::INVALID   => 'Entrée invalide, ce n\'est pas du texte.',
        									StringLength::TOO_SHORT => 'Celà semble très court pour un objet, non ?',
        									StringLength::TOO_LONG  => 'Celà semble un peu long pour un simple objet, non ?',
										),
									),
							),
					),
			));
	
			$inputFilter->add(array(
					'name'     => 'message',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
		                    array(
		                      'name' =>'NotEmpty', 
		                        'options' => array(
		                            'messages' => array(
		                                NotEmpty::IS_EMPTY => 'Vous avez oublié votre message !' 
		                            ),
									'break_chain_on_failure'	=> true,
		                        ),
		                    ),
							array(
								'name'    => 'StringLength',
								'options' => array(
										'encoding' => 'UTF-8',
										'min'      => 20,
										'max'      => 1000,
										'messages'	=> array(
        									StringLength::INVALID   => 'Entrée invalide, ce n\'est pas du texte.',
        									StringLength::TOO_SHORT => 'Celà semble très court pour un message, non ?',
        									StringLength::TOO_LONG  => 'Celà semble un peu long pour un simple message, non ?',
										),
									),
							),
					),
			));
	
			$this->inputFilter = $inputFilter;
		}
	
		return $this->inputFilter;
	}
	
}