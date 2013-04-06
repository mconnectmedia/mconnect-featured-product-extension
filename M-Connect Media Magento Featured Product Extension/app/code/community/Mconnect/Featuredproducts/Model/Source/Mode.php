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
class Mconnect_Featuredproducts_Model_Source_Mode
{
    public function toOptionArray()
    {
    	return array(
	        array('value' => 'random', 
			      'label' => Mage::helper('featuredproducts')->__('Auto/Random')),
        	array('value' => 'asc', 
			      'label' => Mage::helper('featuredproducts')->__('Ascending')),				  
		array('value' => 'desc', 
			      'label' => Mage::helper('featuredproducts')->__('Descending')),		
        );   
    }
}
