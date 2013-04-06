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
class Mconnect_Featuredproducts_Block_Adminhtml_Featuredproducts_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('featuredproducts_form', array('legend'=>Mage::helper('featuredproducts')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('featuredproducts')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('featuredproducts')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('featuredproducts')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('featuredproducts')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('featuredproducts')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('featuredproducts')->__('Content'),
          'title'     => Mage::helper('featuredproducts')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getFeaturedproductsData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getFeaturedproductsData());
          Mage::getSingleton('adminhtml/session')->setFeaturedproductsData(null);
      } elseif ( Mage::registry('featuredproducts_data') ) {
          $form->setValues(Mage::registry('featuredproducts_data')->getData());
      }
      return parent::_prepareForm();
  }
}
