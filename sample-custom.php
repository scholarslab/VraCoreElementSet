<?php 
/**
 * @version $Id$
 * @copyright Scholars' Lab 2010
 * @license http://www.apache.org/licenses/LICENSE-2.0.txt
 * @package VraCoreElementSet
 **/
 
/**
 * @package VraCoreElementSet
 * @author Ethan Gruber
 * @copyright Scholars' Lab 2010
 **/
 
function show_agents($html){
	$db = get_db();

	//get all agents in the item record
	foreach (item('VRA Core', 'Agent', 'all') as $agentId){
		//access the corresponding record from the Agents table and output in HTML
		//there may be a better way to do this...
		$agent = $db->getTable('VraCoreElementSet_Agent')->find($agentId);
		$html .= '<div class="element-text"><b>' . $agent['name'] . '</b><br/>';
		if ($agent['role'] != NULL){
			$html .= 'Role: ' . $agent['role'] . '<br/>'; 
		}
		if ($agent['culture'] != NULL){
			$html .= 'Culture: ' . $agent['culture'] . '<br/>'; 
		}
		if ($agent['earliest_date'] != NULL){
			$html .= 'Birth Date: ' . $agent['earliest_date'] . '<br/>'; 
		}
		if ($agent['latest_date'] != NULL){
			$html .= 'Death Date: ' . $agent['latest_date'] . '<br/>'; 
		}
		$html .= '<br/></div>';
	}
	return $html;
}

/**
 * execute the function in your view with this:
 * <?php echo show_agents($html); ?>
 */