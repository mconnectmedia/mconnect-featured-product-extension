<?php
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
class Mconnect_Featuredproducts_Block_Adminhtml_Featuredproducts_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'featuredproducts';
        $this->_controller = 'adminhtml_featuredproducts';
	$this->_removeButton("save");
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save'),
            'onclick'   => 'saveFeaturedProductsRelation()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('featuredproducts_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'featuredproducts_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'featuredproducts_content');
                }
            }

            function saveFeaturedProductsRelation(){
                editForm.submit();
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('featuredproducts_data') && Mage::registry('featuredproducts_data')->getId() ) {
            return Mage::helper('featuredproducts')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('featuredproducts_data')->getTitle()));
        } else {
            return Mage::helper('featuredproducts')->__('Select Products');
        }
    }
}
