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
?>
<?php

class Mconnect_Featuredproducts_Block_Adminhtml_Featuredproducts_Renderer_Sku extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
 
    public function render(Varien_Object $row)
    {   
	$_html = '';
	if($row->getEntityId()){
	$_productRecord = Mage::getModel('catalog/product')->load($row->getEntityId());
	$key = Mage::getSingleton('adminhtml/url')->getSecretKey("catalog_product/edit/","product");
	$_html .= '<a target="_blank" href="'.Mage::getUrl('adminhtml/catalog_product/edit', array('_secure'=>true,'id'=>$row->getEntityId(),'key'=>$key)).'">'.$_productRecord->getSku().'</a>';
	}
        return $_html;
    }
}
?>
