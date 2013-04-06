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
class Mconnect_Featuredproducts_Block_Featuredproducts extends Mage_Core_Block_Template
{
     public function _prepareLayout()
     {
		return parent::_prepareLayout();
     }
    
     public function getFeaturedproducts()     
     { 
        if (!$this->hasData('featuredproducts')) {
            $this->setData('featuredproducts', Mage::registry('featuredproducts'));
        }
        return $this->getData('featuredproducts');
        
    }


    public function getmcsfeaturedenabled($storeId){
    return Mage::getStoreConfig('featuredproducts/featuredproductsdisplay/enabledfeatured', $storeId);
    }

    public function getmcsfeaturedconfig($storeId){
	$_configData = array();

	$_configData['dispTitleFlagFeatured'] = Mage::getStoreConfig('featuredproducts/featuredproductsdisplay/featureddisptitle', $storeId);
	$_configData['dispTitleFeatured'] = Mage::getStoreConfig('featuredproducts/featuredproductsdisplay/featureddisptitletxt', $storeId);
	$_configData['dispModeFeatured'] = Mage::getStoreConfig('featuredproducts/featuredproductsdisplay/featureddisporder', $storeId);

	$_columnCount = Mage::getStoreConfig('featuredproducts/featuredproductsdisplay/featureddispcolcnt', $storeId);
	if(function_exists('filter_var')){
	$_columnCount = filter_var($_columnCount, FILTER_SANITIZE_NUMBER_INT);
	}
	$_configData['columnCount'] = $_columnCount;

	$_dispCntFeatured = Mage::getStoreConfig('featuredproducts/featuredproductsdisplay/featureddispcnt', $storeId);
	if(function_exists('filter_var')){
	$_dispCntFeatured = filter_var($_dispCntFeatured, FILTER_SANITIZE_NUMBER_INT);
	}
	$_configData['dispCntFeatured'] = $_dispCntFeatured;

	return $_configData;
    }

}
