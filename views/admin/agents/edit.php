<?php
    head(array('title' => 'VRA Core Agents', 'bodyclass' => 'primary', 'content_class' => 'horizontal-nav'));
?>
<h1>Manage VRA Core Agents</h1>

<div id="primary">
	<?php
            if (!empty($err)) {
                echo '<p class="error">' . html_escape($err) . '</p>';
            }
        ?>
     <?php echo $form; ?>
		<p id="delete_item_link">
			<a class="delete delete-item" href="<?php echo html_escape(uri('vra-core-element-set/agents/delete?id=')) . $id ; ?>">Delete This Agent</a>
		</p>
</div>

<?php 
    foot(); 
?>
