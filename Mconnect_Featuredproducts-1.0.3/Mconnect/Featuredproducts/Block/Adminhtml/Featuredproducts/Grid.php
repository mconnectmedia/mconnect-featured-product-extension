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
class Mconnect_Featuredproducts_Block_Adminhtml_Featuredproducts_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('featuredproductsGrid');
      $this->setDefaultSort('featuredproducts_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }
  protected function _getStore()
  {
      $storeId = (int) $this->getRequest()->getParam('store', 0);
      return Mage::app()->getStore($storeId);
  }

  protected function _prepareCollection()
  {
        $store = $this->_getStore();
		$collection = Mage::getModel("catalog/product")->getCollection()
		    ->addAttributeToSelect('sku')
		    ->addAttributeToSelect('name')
		    ->addAttributeToSelect('attribute_set_id')
		    ->addAttributeToSelect('type_id');

        if (Mage::helper('featuredproducts')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');
        }

	$collection->getSelect()->join(array('tbl_featured'=>Mage::getSingleton('core/resource')->getTableName('featuredproducts/featuredproducts')),"`e`.`entity_id` = `tbl_featured`.`product_id`", array('featuredproducts_id','created_time','update_time','featuredstatus'));

        if ($store->getId()) {
	   $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
            $collection->addStoreFilter($store);
            $collection->joinAttribute(
                'name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                $adminStore
            );
            $collection->joinAttribute(
                'custom_name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'status',
                'catalog_product/status',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'visibility',
                'catalog_product/visibility',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'price',
                'catalog_product/price',
                'entity_id',
                null,
                'left',
                $store->getId()
            );
	}
        else {
            $collection->addAttributeToSelect('price');
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
        }

      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
        $this->addColumn('featuredproducts_id',
            array(
                'header'=> Mage::helper('featuredproducts')->__('ID'),
                'width' => '50px',
                'type'  => 'number',
                'index' => 'featuredproducts_id',
                'sortable'  => false,
                'filter'  => false,
        ));

        $this->addColumn('entity_id',
            array(
                'header'=> Mage::helper('featuredproducts')->__('ProductID'),
                'width' => '50px',
                'type'  => 'number',
                'index' => 'entity_id',
        ));

        $this->addColumn('name',
            array(
                'header'=> Mage::helper('featuredproducts')->__('Name'),
                'index' => 'name',
        ));

        $store = $this->_getStore();
        if ($store->getId()) {
            $this->addColumn('custom_name',
                array(
                    'header'=> Mage::helper('featuredproducts')->__('Name in %s', $store->getName()),
                    'index' => 'custom_name',
            ));
        }

        $this->addColumn('type',
            array(
                'header'=> Mage::helper('featuredproducts')->__('Type'),
                'width' => '60px',
                'index' => 'type_id',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ));

        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->load()
            ->toOptionHash();

        $this->addColumn('set_name',
            array(
                'header'=> Mage::helper('featuredproducts')->__('Attrib. Set Name'),
                'width' => '100px',
                'index' => 'attribute_set_id',
                'type'  => 'options',
                'options' => $sets,
        ));

        $this->addColumn('sku',
            array(
                'header'=> Mage::helper('featuredproducts')->__('SKU'),
                'width' => '80px',
                'index' => 'sku',
		'renderer'  => 'featuredproducts/adminhtml_featuredproducts_renderer_sku',	
        ));

        $store = $this->_getStore();
        $this->addColumn('price',
            array(
                'header'=> Mage::helper('featuredproducts')->__('Price'),
                'type'  => 'price',
                'currency_code' => $store->getBaseCurrency()->getCode(),
                'index' => 'price',
        ));

        if (Mage::helper('featuredproducts')->isModuleEnabled('Mage_CatalogInventory')) {
            $this->addColumn('qty',
                array(
                    'header'=> Mage::helper('featuredproducts')->__('Qty'),
                    'width' => '100px',
                    'type'  => 'number',
                    'index' => 'qty',
            ));
        }

        $this->addColumn('visibility',
            array(
                'header'=> Mage::helper('featuredproducts')->__('Visibility'),
                'width' => '70px',
                'index' => 'visibility',
                'type'  => 'options',
                'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('websites',
                array(
                    'header'=> Mage::helper('featuredproducts')->__('Websites'),
                    'width' => '100px',
                    'sortable'  => false,
                    'index'     => 'websites',
                    'type'      => 'options',
                    'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash(),
            ));
        }

        $this->addColumn('featuredstatus',
            array(
                'header'=> Mage::helper('featuredproducts')->__('Status'),
                'width' => '70px',
                'index' => 'featuredstatus',
                'type'  => 'options',
                'sortable'  => false,
                'filter'  => false,
		'options'   => array(
		      1 => 'Enabled',
		      2 => 'Disabled',
		),
        ));


	$this->addColumn('action',
            array(
                'header'    => Mage::helper('featuredproducts')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getFeaturedproductsId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('featuredproducts')->__('Delete'),
                        'url'     => array(
                            'base'=>'*/*/delete',
                            'params'=>array('store'=>$this->getRequest()->getParam('store'))
                        ),
		        'confirm'  => Mage::helper('featuredproducts')->__('Are you sure?'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
        ));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('featuredproducts_id');
        $this->getMassactionBlock()->setFormFieldName('featuredproducts');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('featuredproducts')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('featuredproducts')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('featuredproducts/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('featuredproducts')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'featuredstatus',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('featuredproducts')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
