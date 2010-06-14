<?php
    head(array('title' => 'VRA Core Agents', 'bodyclass' => 'primary', 'content_class' => 'horizontal-nav'));
?>
<h1>Manage VRA Core Agents</h1>
<p id="add-item" class="add-button"><a class="add" href="<?php echo html_escape(uri('vra-core-element-set/agents/create')); ?>">Add an Agent</a></p>

<div id="primary">
	<?php echo flash(); ?>
	<?php
            if (!empty($err)) {
                echo '<p class="error">' . html_escape($err) . '</p>';
            }
        ?>
	<?php if ($count == 0): ?>
	    <div id="no-items">
	    <p>There are no items in the archive yet.
	    
	    <?php  if (get_acl()->checkUserPermission('VraCoreElementSet_Agents', 'index')): ?>
	          Why don&#8217;t you <?php echo link_to('agents', 'create', 'add one'); ?>?</p>
	    </div>
    <?php endif; ?>
    <?php else: ?>
		<div class="pagination"><?php echo pagination_links(); ?></div>
	    <table class="simple" cellspacing="0" cellpadding="0">
	            <thead>
	                <tr>
	                	<th>ID</th>
	                    <th>Name</th>
	                    <th>Culture</th>
	                    <th>Earliest Date</th>
	                    <th>Latest Date</th>
	                    <th>Role</th>
						<th>Edit?</th>
	                </tr>
	            </thead>
	            <tbody>
	                <?php foreach($agents as $agent): ?>
	                <tr>
						<td><?php echo html_escape($agent['id']); ?></td>
	                    <td><?php echo html_escape($agent['name']); ?></td>
	                    <td><?php echo html_escape($agent['culture']); ?></td>
	                    <td><?php echo html_escape($agent['earliest_date']); ?></td>
	                    <td><?php echo html_escape($agent['latest_date']); ?></td>
	                    <td><?php echo html_escape($agent['role']); ?></td>
						<td><a href="<?php echo html_escape(uri('vra-core-element-set/agents/edit')); ?>?id=<?php echo $agent['id']; ?>" class="edit">Edit</a></td>
	                </tr>
	                <?php endforeach; ?>
	            </tbody>
	        </table>    
    <?php endif; ?>
</div>

<?php 
    foot(); 
?>
