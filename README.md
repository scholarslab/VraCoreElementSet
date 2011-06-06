

  About VraCoreElementSet 

----------

VraCoreElementSet is a plugin developed by the [Scholars' Lab][1] at the University of Virginia Library.  VRA Core is a semantically appropriate schema for describing art and artifacts. Since it was conceived as an XML standard (not strictly a flat list of fields), some elements have hierarchical sub-componenets. For example, a work may have several agents involved in its production, and each agent has a name as well as a role, culture, birth date, and, as the case may be, a death date. The VraCoreElementSet plugin creates a table for agents so that a user may enter this data separately. Then in the Edit Item form, the user may select VRA Core agents from a drop down menu restricted by the records in the agents table. Items may also be exported to schema-compliant VRA Core XML.

  Download 

----------

* Subversion: [https://addons.omeka.org/svn/plugins/VraCoreElementSet/trunk/][2]

* Package: [VraCoreElementSet 0.9][3]

  Installing and Configuring 

----------

1.  Checkout from svn or download/extract zipped package to omeka/plugins (see [Installing_a_Plugin][4]).

2.  Install VraCoreElementSet on the Settings->Plugins page.

3. Agents and associated metadata may be entered by clicking on the VRA Core Agents tab in the administrative section of Omeka.

4. The VRA Core Element Set fields will be available when creating or editing items.  Data entered into VRA Core fields will be duplicated to appropriate Dublin Core fields, if applicable.  The Agent field in the form is restricted to a drop-down menu of values in the agent table.

###   Adjusting the Public Item View 
 ###

Since VRA Core element texts are mapped to associated Dublin Core element texts, it is probably unnecessary to use the default show_item_metadata() function in the public item view in your theme.  You may prefer to show only the VRA Core fields.  Moreover, the element text for an agent is the id from the agents table, not the name, so showing agent metadata requires a specially designed function.  Therefore, the plugin includes a vra_core_show_item_metadata() function to accomplish this view.

1. Edit themes/[theme name]/items/show.php

2. Replace the call to show_item_metadata with the following conditional:

	<?php $hasVraElements = has_vra_core_element_texts($item); 
	if ($hasVraElements == true){ echo vra_core_show_item_metadata($item);} else { echo show_item_metadata(); } ?>

has_vra_core_element_texts() returns true or false as to whether or not the item has VRA Core element texts attached to it.  Thus, VRA Core item metadata can be displayed correctly with the new plugin functions while all other items are displayed with the Omeka default.

  Notes on Use 

----------

If an item contains one or more VRA Core fields, the crosswalk to map VRA Core to Dublin Core is executed.  DC fields are removed before this process begins, so it is important to note that if all VRA Core fields are removed from an item in an edit, corresponding DC fields will not be removed.  If a user wishes to use VRA Core to describe an item, it is not possible for the user to additionally enter Dublin Core metadata manually since the DC fields are removed before the VRA Core-to-Dublin Core crosswalk.  If the VraCoreElementSet plugin is removed the item's Dublin Core fields are retained.  In summation, only use VRA Core or Dublin Core to describe an item--do not mix and match metadata standards.

The Identifier element in the VRA Core element set is not actually a VRA Core 4.0 element, but rather a container for the work refid attribute, which is used to capture local identifiers, such as accession number.  Since the refid can only occur once in a VRA Core item, only the first Identifier from the VRA Core Element Set is used in the VRA Core XML export, so the field should not be repeated in the Edit Item form.

<!-- 
NewPP limit report
Preprocessor node count: 9/1000000
Post-expand include size: 0/2097152 bytes
Template argument size: 0/2097152 bytes
Expensive parser function count: 0/100
-->

Retrieved from "[http://omeka.org/codex/Plugins/VraCoreElementSet](http://omeka.org/codex/Plugins/VraCoreElementSet)"

[1]: http://scholarslab.org/ "http://scholarslab.org/"
[2]: https://addons.omeka.org/svn/plugins/VraCoreElementSet/trunk/ "https://addons.omeka.org/svn/plugins/VraCoreElementSet/trunk/"
[3]: http://www.scholarslab.org/wp-content/uploads/2010/09/VraCoreElementSet-0.9.zip "http://www.scholarslab.org/wp-content/uploads/2010/09/VraCoreElementSet-0.9.zip"
[4]: /codex/Installing_a_Plugin "Installing a Plugin"
