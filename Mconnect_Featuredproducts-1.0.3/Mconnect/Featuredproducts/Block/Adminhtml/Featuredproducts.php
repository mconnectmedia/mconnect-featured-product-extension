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
class Mconnect_Featuredproducts_Block_Adminhtml_Featuredproducts extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_featuredproducts';
    $this->_blockGroup = 'featuredproducts';
    $this->_headerText = Mage::helper('featuredproducts')->__('Featured Products Manager');
    $this->_addButtonLabel = Mage::helper('featuredproducts')->__('Manage Products');
    parent::__construct();
  }
}
