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
class Mconnect_Featuredproducts_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
	$this->loadLayout();

	$storeId = Mage::app()->getStore()->getId();
	if(Mage::getStoreConfig('featuredproducts/featuredproductsdisplay/featureddisptitletxt', $storeId) != ''){
	$title = "Featured Products";
	$this->_title($this->__($title));     
	}
	$this->renderLayout();
    }
}
