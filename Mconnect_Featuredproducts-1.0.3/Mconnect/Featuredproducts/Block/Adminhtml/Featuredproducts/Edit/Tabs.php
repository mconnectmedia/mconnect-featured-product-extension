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
class Mconnect_Featuredproducts_Block_Adminhtml_Featuredproducts_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('featuredproducts_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('featuredproducts')->__('Relate Products'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('featuredproducts')->__('Manage Products'),
          'title'     => Mage::helper('featuredproducts')->__('Manage Products'),
          'content'   => $this->getLayout()->createBlock('featuredproducts/adminhtml_featuredproducts_productgrid')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
