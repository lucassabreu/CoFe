<?php

namespace Application\Controller;

use Application\Form\Category\CategoryCreate;
use Application\Form\Category\CategoryDetail;
use Application\Form\Category\CategoryList;
use Application\Form\Category\CategoryRemove;
use Application\Form\Category\CategoryUpdate;
use Application\Model\Entity\Category;
use Core\Controller\AbstractController;
use Core\Model\DAO\Exception\DAOException;
use Exception;
use Zend\Http\Request;

/**
 * CRUD of entity Category
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class CategoryController extends AbstractController {

    public function __construct() {
        $this->daoName = 'Application\Service\CategoryDAOService';
    }

    /**
     * List categories with pagination
     */
    public function indexAction() {

        $form = new CategoryList();

        $categories = $this->dao()->fetchAllTop($this->getSessionUser());

        return array(
            'form' => $form,
            'categories' => $categories,
        );
    }

    /**
     * View for create a new Category
     */
    public function createAction() {
        $request = $this->getRequest();

        /* @var $request Request */

        $form = new CategoryCreate();
        $form->get('cancel')->setAttribute('formaction', $request->getPost('returnTo'));

        if ($request->isPost()) {
            $submitAction = $request->getPost('submitAction');

            if ($submitAction === 'create') {
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    try {
                        $category = new Category();
                        $data = $form->getData();

                        unset($data['number']);

                        if (isset($data['parent']) && $data['parent'] !== 0) {
                            $data['parent'] = $this->dao()->findById($data['parent']);
                        } else {
                            $data['parent'] = null;
                        }

                        $data['user'] = $this->getSessionUser();
                        $parent = $data['parent'];
                        unset($data['parent']);

                        $category->setData($data);

                        $vParent = $category->getInputFilter()->get('parent');
                        $vParent->setValue($parent);

                        if ($vParent->isValid()) {
                            if ($parent)
                                $category->setParent($parent);

                            $this->dao()->save($category);
                            return $this->redirect()->toRoute('category', array('action' => 'detail', 'number' => $category->getNumber()));
                        } else {
                            $form->get('parent')->setMessages($vParent->getMessages());
                        }
                    } catch (Exception $e) {
                        if ($e instanceof DAOException) {
                            $form->addExceptionMessage($e);
                        } else {
                            throw $e;
                            $form->addExceptionMessage('Occurred internal errors: ' . $e->getMessage());
                        }
                    }
                }
            }
        }

        $categories = $this->dao()->fetchAllTop($this->getSessionUser());
        return array('form' => $form, 'categories' => $categories);
    }

    /**
     * View for create a new Category
     */
    public function updateAction() {
        $request = $this->getRequest();

        /* @var $request Request */

        $number = $this->params()->fromRoute('number');

        if ($number == null)
            $number = $request->getPost('number');

        if ($number == null)
            return $this->redirect()->toRoute('category');
        else {

            $category = $this->dao()->findById($number);

            /* @var $category Category */
            if ($category == null) {
                return $this->redirect()->toRoute('category');
            }

            $form = new CategoryUpdate();
            $form->get('cancel')->setAttribute('formaction', $request->getPost('returnTo'));
            $data = $category->getData();

            if ($request->isPost()) {
                $submitAction = $request->getPost('submitAction');

                if ($submitAction === 'update') {
                    $form->setData($request->getPost());

                    if ($form->isValid()) {
                        try {
                            $data = $form->getData();

                            if ($data['parent'] !== 0) {
                                $data['parent'] = $this->dao()->findById($data['parent']);
                            } else {
                                $data['parent'] = null;
                            }

                            $data['user'] = $this->getSessionUser();
                            $parent = $data['parent'];
                            unset($data['parent']);
                            $flowType = $data['flowType'];
                            unset($data['flowType']);

                            $category->setData($data);
                            $data['parent'] = $parent;

                            $vParent = $category->getInputFilter()->get('parent');
                            $vParent->setValue($parent);

                            if ($vParent->isValid()) {
                                if ($parent)
                                    $category->setParent($parent);

                                $vFlowType = $category->getInputFilter()->get('flowType');
                                $vFlowType->setValue($flowType);

                                if ($vFlowType->isValid()) {
                                    $category->setFlowType($flowType);

                                    $this->dao()->save($category);
                                    return $this->redirect()->toRoute('category', array('action' => 'detail', 'number' => $category->getNumber()));
                                } else {
                                    $form->get('flowType')->setMessages($vFlowType->getMessages());
                                    $data['flowType'] = $category->getFlowType();
                                }
                            } else {
                                $form->get('parent')->setMessages($vParent->getMessages());
                            }
                        } catch (Exception $e) {
                            if ($e instanceof DAOException) {
                                $form->addExceptionMessage($e);
                            } else {
                                throw $e;
                                $form->addExceptionMessage('Occurred internal errors: ' . $e->getMessage());
                            }
                        }
                    }
                }
            }

            if (isset($data['parent']) && $data['parent']) {
                $parent = $data['parent'];
                $data['parent'] = $parent->getNumber();
            } else {
                $data['parent'] = null;
            }

            $form->setData($data);
        }

        $categories = $this->dao()->fetchAllTop($this->getSessionUser());
        return array('form' => $form, 'categories' => $categories);
    }

    /**
     * Detail view of Category
     */
    public function detailAction() {

        $number = $this->params()->fromRoute('number');

        if ($number == null)
            $number = $this->getRequest()->getPost('number');

        if ($number == null)
            return $this->redirect()->toRoute('category');
        else {
            $category = $this->dao()->findById($number);
            /* @var $category Category */
            if ($category == null) {
                return $this->redirect()->toRoute('category');
            }
            $form = new CategoryDetail();

            $data = $category->getData();

            if ($category->getParent()) {
                $data['parent_number'] = $category->getParent()->getNumber();
                $data['parent_code'] = $category->getParent()->getCode();
                $data['parent_description'] = $category->getParent()->getDescription();
            } else {
                $data['parent_number'] = null;
                $data['parent_code'] = null;
                $data['parent_description'] = null;
            }

            $form->setData($data);

            return array('form' => $form);
        }

        return array('form' => $form);
    }

    /**
     * Remove a category
     */
    public function removeAction() {
        $request = $this->getRequest();
        /* @var $request Request */

        $number = $this->params()->fromRoute('number', null);

        if ($number === null && $request->isPost())
            $number = $request->getPost('number');

        if ($number === null)
            return $this->redirect()->toRoute('categoryList');

        $category = $this->dao()->findById($number);
        /* @var $category Category */
        if ($category == null) {
            return $this->redirect()->toRoute('category');
        }

        $form = new CategoryRemove();

        $returnTo = $request->getPost('returnTo');

        if ($returnTo === null)
            $returnTo = $this->url()->fromRoute('categoryList');

        $form->get('cancel')->setAttribute('formaction', $returnTo);

        if ($request->isPost()) {
            $submitAction = $request->getPost('submitAction');

            if ($submitAction === 'remove') {
                try {
                    $this->dao()->remove($category);
                    return $this->redirect()->toRoute('categoryList');
                } catch (Exception $e) {
                    if ($e instanceof DAOException) {
                        $form->addExceptionMessage($e);
                    } else {
                        throw $e;
                        $form->addExceptionMessage('Occurred internal errors: ' . $e->getMessage());
                    }
                }
            }
        }


        $form->setData($category->getData());

        return array('form' => $form);
    }

}

?>
