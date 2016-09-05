<?php
namespace Application\Model;
 
use Zend\Db\TableGateway\TableGateway;
 
// A table class for the database table student
class MessageTable{
 
    // This table class does database operations using $tableGateway
    protected $tableGateway;
 
    // Service manager injects TableGateway object into this class
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }

    public function getMessage($id)
    {
    	$id  = (int) $id;
    	$rowset = $this->tableGateway->select(array('id' => $id));
    	$row = $rowset->current();
    	if (!$row) {
    		throw new \Exception("Could not find row $id");
    	}
    	return $row;
    }
    
    public function saveMessage(Message $message)
    {
    	$data = array(
    			'message' => $message->message,
    			'subject'	=> $message->subject,
    			'email'		=> $message->email,
    			'is_mailed'	=> $message->isMailed,
    	);
    
    	$id = (int) $message->id;
    	if ($id == 0) {
    		$this->tableGateway->insert($data);
    	} else {
    		if ($this->getMessage($id)) {
    			$this->tableGateway->update($data, array('id' => $id));
    		} else {
    			throw new \Exception('Message id does not exist');
    		}
    	}
    }
    
    
    public function fetchAll(){
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
}