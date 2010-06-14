<?php
class VraCoreElementSet_Controller_Plugin_SelectFilter extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        add_filter(array('Form','Item','VRA Core','Agent'), array($this, 'filterElement'));
    }
    
	public static function filterElement($html, $inputNameStem, $value, $options, $record, $element)
	    {
	        $db = get_db();
	        $at = $db->getTable('VraCoreElementSet_Agent')->findAll();
	        $agents = array();
	        foreach ($at as $agent) {
	        	$agents[$agent['id']] = $agent['name']; 
	        }
	        //$term = explode("\n", $simpleVocabTerm->terms);
	        $selectAgents = array('' => 'Select Below') + $agents;
	        return __v()->formSelect($inputNameStem . '[text]',
	                                 $value,
	                                 $options,
	                                 $selectAgents);
	    }
}