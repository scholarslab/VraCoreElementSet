<?php echo '<?xml version="1.0"?>'; ?>
<vra  xmlns="http://www.vraweb.org/vracore4.htm" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.vraweb.org/vracore4.htm http://www.vraweb.org/projects/vracore4/vra-4.0-restricted.xsd">
<?php 
require_once 'ItemVraCoreXml.php';
$convert = new ItemVraCoreXml; 
while ($item = loop_items()) {
    echo $convert->recordToVraCoreXml($item);
}
?>
</vra>