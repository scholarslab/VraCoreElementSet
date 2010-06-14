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
</div>

<?php 
    foot(); 
?>
