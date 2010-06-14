<?php

/**
 * @copyright  Scholars' Lab 2010
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html
 * @version    $Id:$
 * @package VraCoreElements
 * @author Ethan Gruber: ewg4x at virginia dot edu
 */

class VraCoreElementSet_AgentsController extends Omeka_Controller_Action
{
    public function browseAction() 	
    {
    	$db = get_db();
    	
    	$currentPage = $this->_getParam('page', 1);
    	
    	
    	$count = $db->getTable('VraCoreElementSet_Agent')->count();
    	$this->view->agents =  VraCoreElementSet_Agent::getAgents($currentPage);
    	$this->view->count = $count;
		
        /** 
         * Now process the pagination
         * 
         **/
        $paginationUrl = $this->getRequest()->getBaseUrl().'/agents/browse/';

        //Serve up the pagination
        $pagination = array('page'          => $currentPage, 
                            'per_page'      => 10, 
                            'total_results' => $count, 
                            'link'          => $paginationUrl);
        
        Zend_Registry::set('pagination', $pagination);
    }
    
    public function createAction(){
    	$agent = array();
    	$form = $this->agentForm($agent);
		$this->view->form = $form;
    }
    
	public function editAction(){
        // Get all the element sets that apply to the item.
        //$this->view->elementSets = $this->_getItemElementSets();
            $db = get_db();
            $agentId = $this->_getParam('id');
            $agent = $db->getTable('VraCoreElementSet_Agent')->find($agentId);
            $form = $this->agentForm($agent);
            $this->view->id = $agentId;
            $this->view->form = $form;
            // If the user cannot edit any given item. Check if they can edit 
            // this specific item
           /* if ($this->isAllowed('editAll') 
                || ($this->isAllowed('editSelf') && $item->wasAddedBy($user))) {
                return parent::editAction();    
            }*/
        
        //$this->forbiddenAction();
    }

    public function updateAction() 
    {
		$form = $this->agentForm($agent);
		
    	if ($_POST) {
    		if ($form->isValid($this->_request->getPost())) {    
    			//get posted values		
				$uploadedData = $form->getValues();
				if ($uploadedData['vra_core_agent_method'] == 'create'){
					$data = array('name'=>$uploadedData['vra_core_agent_name'],
						'culture'=>$uploadedData['vra_core_agent_culture'],
						'earliest_date'=>$uploadedData['vra_core_agent_earliest_date'],
						'latest_date'=>$uploadedData['vra_core_agent_latest_date'],
						'role'=>$uploadedData['vra_core_agent_role']);
				} elseif ($uploadedData['vra_core_agent_method'] == 'update'){
					$data = array('id'=>$uploadedData['vra_core_agent_id'],
						'name'=>$uploadedData['vra_core_agent_name'],
						'culture'=>$uploadedData['vra_core_agent_culture'],
						'earliest_date'=>$uploadedData['vra_core_agent_earliest_date'],
						'latest_date'=>$uploadedData['vra_core_agent_latest_date'],
						'role'=>$uploadedData['vra_core_agent_role']);
				}
				
				//get db
				try{		
					$db = get_db();								
					$db->insert('vra_core_element_set_agent', $data); 
					$this->redirect->goto('browse');   
					$this->flashSuccess('Agent successfully created.');			
				} catch (Exception $e) {
					$this->flashError($e->getMessage());
        		}
    		}
    		else {
    			$this->flashError('Agent name is required.');
    			$this->view->form = $form;
    		}
    		
    	}
    	else {
    			$this->flashError('Failed to gather posted data.');
    			$this->view->form = $form;
    	}
    }
    
    public function deleteAction()
    {
        if ($user = $this->getCurrentUser()) {
			$agentId = $this->_getParam('id');
			$db = get_db();
            $agent = $db->getTable('VraCoreElementSet_Agent')->find($agentId);
            
            // Permission check
            if ($this->isAllowed('deleteAll') 
                || ($this->isAllowed('deleteSelf') && $agent->wasAddedBy($user))) {
                $agent->delete();
                $this->flashSuccess('The item was successfully deleted!');
                $this->redirect->goto('browse');
            }
        }
        
        $this->_forward('forbidden');
    }
    
	private function agentForm($agent)
	{
	    require "Zend/Form/Element.php";
    	$form = new Zend_Form();
		$form->setAction('update');    	
    	$form->setMethod('post');
    	$form->setAttrib('enctype', 'multipart/form-data');
    	  	
    	//Name
		$agentName = new Zend_Form_Element_Text('vra_core_agent_name');
		$agentName->setLabel('Name');
		$agentName->setRequired(true);
		if ($agent != NULL){
			$agentName->setValue($agent['name']);
		}
		$form->addElement($agentName);
		
		//Culture
		$agentCulture = new Zend_Form_Element_Text('vra_core_agent_culture');
		$agentCulture->setLabel('Culture');
		if ($agent != NULL){
			$agentCulture->setValue($agent['culture']);
		}
		$form->addElement($agentCulture);
		
		//Role
		$agentRole = new Zend_Form_Element_Text('vra_core_agent_role');
		$agentRole->setLabel('Role');
		if ($agent != NULL){
			$agentRole->setValue($agent['role']);
		}
		$form->addElement($agentRole);
		
		//Earliest Date
		$agentEarliestDate = new Zend_Form_Element_Text('vra_core_agent_earliest_date');
		$agentEarliestDate->setLabel('Earliest Date');
		if ($agent != NULL){
			$agentEarliestDate->setValue($agent['earliest_date']);
		}
		$form->addElement($agentEarliestDate);
		
		//Latest Date
		$agentLatestDate = new Zend_Form_Element_Text('vra_core_agent_latest_date');
		$agentLatestDate->setLabel('Latest Date');
		if ($agent != NULL){
			$agentLatestDate->setValue($agent['latest_date']);
		}
		$form->addElement($agentLatestDate);
    	
    	//Submit button
    	$form->addElement('submit','submit');
    	$submitElement=$form->getElement('submit');
    	$submitElement->setLabel('Submit');
    	
		//Id, only if it is an update    	
    	$agentId = new Zend_Form_Element_Hidden('vra_core_agent_id');
    	if ($agent != NULL){
    		$agentId->setValue($agent['id']);
    	}
    	$form->addElement($agentId);
    	
    	
    	//define method	   
    	$agentMethod = new Zend_Form_Element_Hidden('vra_core_agent_method');
		if ($agent != NULL){
    		$agentMethod->setValue('update');
    	 } else {
    	 	$agentMethod->setValue('create');
    	 }
    	$form->addElement($agentMethod);
    	
    	return $form;
	}
}

