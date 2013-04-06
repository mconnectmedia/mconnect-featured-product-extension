/**
 * M-Connect Solutions.
 *
 * NOTICE OF LICENSE
 *

 *
 * @category   Catalog
 * @package   Mconnect_Featuredproducts
 * @author      M-Connect Solutions (http://www.magentoconnect.us)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

jQuery.noConflict();
jQuery(document).ready(function(){	
});

jQuery(window).load(function() {
	jQuery('div#featuredproducts_tabs_form_section_content div#productGrid').find('.required-entry').each(function(index,value){
	//alert(index + ': '+value.id + jQuery(this).id);
	jQuery(this).remove();
	});

	jQuery('input:checkbox.massaction-checkbox').each(function(index,value){
	jQuery(this).attr('name','product[]');
	});

	jQuery('div#featuredproducts_tabs_form_section_content div#productGrid div.grid table#productGrid_table tbody tr').each(function(index,value){
	jQuery(this).removeAttr('title');
	});

	jQuery('div#featuredproductsGrid div.grid table#featuredproductsGrid_table tbody tr').each(function(index,value){
	jQuery(this).removeAttr('title');
	});

});
