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
class Mconnect_Featuredproducts_Adminhtml_FeaturedproductsController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('featuredproducts/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('featuredproducts/featuredproducts')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('featuredproducts_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('featuredproducts/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('featuredproducts/adminhtml_featuredproducts_edit'))
				->_addLeft($this->getLayout()->createBlock('featuredproducts/adminhtml_featuredproducts_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('featuredproducts')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction()
	{

		if ($data = $this->getRequest()->getPost()) {

		if(is_array($data['product']) && count($data['product']) > 0){

		$_resource = Mage::getSingleton('core/resource');
		$_tbl_featuredproducts = $_resource->getTableName('featuredproducts/featuredproducts');
		$_readHandle = $_resource->getConnection('core_read');
		$_featuredproducts_query = "SELECT GROUP_CONCAT(DISTINCT `product_id`) FROM ".$_tbl_featuredproducts;
		$_featured_ProductIDs = $_readHandle->fetchCol($_featuredproducts_query);
		$_deservedProducts = array();
		if($_featured_ProductIDs[0] != ''){
		$_deservedProducts = explode(',',$_featured_ProductIDs[0]);
		}

		$model = Mage::getModel('featuredproducts/featuredproducts');	
		$_dataAddedFlag = false;	
		foreach($data['product'] as $_pID){
		if(!array_search($_pID,$_deservedProducts)){
		$_tmpArr = array();
		$_tmpArr['product_id'] = $_pID;
		$_tmpArr['featuredstatus'] = 1;

		$model->setData($_tmpArr);
		try {
			if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
			$model->setCreatedTime(now())
				->setUpdateTime(now());
			} else {
			$model->setUpdateTime(now());
			}
			$model->save();
			$_dataAddedFlag = true;			

		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			Mage::getSingleton('adminhtml/session')->setFormData($data);
			$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			return;
		} // ends try-catch

		} // ends if
		} // ends foreach loop

		} // ends product count check If-condition		
	
	        } // ends main if

		if($_dataAddedFlag == false){
		Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('featuredproducts')->__('Requested product(s) already saved as Featured'));
		} else {
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('featuredproducts')->__('Requested product(s) saved as Featured'));
		}

		$this->_redirect('*/*/');
	}
 
	public function deleteAction()
	{
		if( $this->getRequest()->getParam("id") > 0 ) {
			try {
				$_featuredproductsModel = Mage::getModel("featuredproducts/featuredproducts");
				$_featuredproductsModel->setId($this->getRequest()->getParam("id"))->delete();
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Product was successfully deleted from Featured List"));
				$this->_redirect("*/*/");
				} 
				catch (Exception $e) {
					Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
					$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
				}
			}
			$this->_redirect("*/*/");
		}

	public function massDeleteAction() {

        $featuredproductsIds = $this->getRequest()->getParam('featuredproducts');
        if(!is_array($featuredproductsIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {

		$_featuredproductsColl = Mage::getModel('featuredproducts/featuredproducts')->getCollection()
		->addFieldToFilter('product_id',array('in'=>$featuredproductsIds));

                foreach ($_featuredproductsColl as $featuredproduct) {
		if($featuredproduct->getFeaturedproductsId()){
                    $featuredproducts = Mage::getModel('featuredproducts/featuredproducts')->load($featuredproduct->getFeaturedproductsId());
                    $featuredproducts->delete();
		}
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($featuredproductsIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $featuredproductsIds = $this->getRequest()->getParam('featuredproducts');
        if(!is_array($featuredproductsIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {

		$_featuredproductsColl = Mage::getModel('featuredproducts/featuredproducts')->getCollection()
		->addFieldToFilter('product_id',array('in'=>$featuredproductsIds));

                foreach ($_featuredproductsColl as $featuredproduct) {
		if($featuredproduct->getFeaturedproductsId()){
                    $featuredproducts = Mage::getSingleton('featuredproducts/featuredproducts')
                        ->load($featuredproduct->getFeaturedproductsId())
                        ->setFeaturedstatus($this->getRequest()->getParam('featuredstatus'))
                        ->setIsMassupdate(true)
                        ->save();
		}
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($featuredproductsIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'featuredproducts.csv';
        $content    = $this->getLayout()->createBlock('featuredproducts/adminhtml_featuredproducts_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'featuredproducts.xml';
        $content    = $this->getLayout()->createBlock('featuredproducts/adminhtml_featuredproducts_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}
