<?php

namespace Application\Controller;

use Application\Form\Moviment\MovimentCreate;
use Application\Form\Moviment\MovimentDetail;
use Application\Form\Moviment\MovimentList;
use Application\Form\Moviment\MovimentRemove;
use Application\Form\Moviment\MovimentUpdate;
use Application\Model\Entity\Category;
use Application\Model\Entity\Moviment;
use Application\Service\MovimentDAOService;
use Core\Controller\AbstractController;
use Core\Model\DAO\Exception\DAOException;
use Exception;
use Zend\Http\Request;
use Zend\Paginator\Paginator;

/**
 * Controller for Moviment's CRUD
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class MovimentController extends AbstractController {

    public function __construct() {
        $this->daoName = 'Application\Service\MovimentDAOService';
    }

    /**
     * {@inheritDoc}
     * @return MovimentDAOService
     */
    public function dao($name = null) {
        return parent::dao($name);
    }

    public function indexAction() {
        return $this->listBy(array('category.user' => $this->getSessionUser()));
    }

    public function ofcategoryAction() {
        $number = $this->params()->fromRoute('number');

        if ($number == null)
            $number = $this->getRequest()->getPost('number');

        if ($number == null)
            return $this->redirect()->toRoute('moviment');
        else {
            $category = $this->dao()->findCategory($number);
            /* @var $category Category */
            if ($category == null) {
                return $this->redirect()->toRoute('moviment');
            }

            if ($category->getUser()->getId() === $this->getSessionUser()->getId()) {
                $parms = $this->listBy(array('category' => $category));
                $parms['category'] = $category;
                return $parms;
            } else {
                return $this->redirect()->toRoute('moviment');
            }
        }
    }

    protected function listBy($params = array()) {
        $page = $this->params()->fromRoute('page', 1);

        $adapter = $this->dao()->getAdapterPaginator($params, array('dateEmission' => 'desc'));

        $moviments = new Paginator($adapter);
        $moviments->setItemCountPerPage(10);
        $moviments->setCurrentPageNumber($page);

        $form = new MovimentList();

        $form->get('createButton')->setAttribute('formaction', $this->url()->fromRoute('moviment', array('action' => 'create')));
        $form->get('detailButton')->setAttribute('formaction', $this->url()->fromRoute('moviment', array('action' => 'detail')));
        $form->get('editButton')->setAttribute('formaction', $this->url()->fromRoute('moviment', array('action' => 'update')));
        $form->get('removeButton')->setAttribute('formaction', $this->url()->fromRoute('moviment', array('action' => 'remove')));

        return array('moviments' => $moviments, 'form' => $form);
    }

    public function createAction() {
        $request = $this->getRequest();

        /* @var $request Request */

        $form = new MovimentCreate();
        $form->get('cancel')->setAttribute('formaction', $request->getPost('returnTo'));

        if ($request->isPost()) {
            $submitAction = $request->getPost('submitAction');

            if ($submitAction === 'create') {
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    try {
                        $moviment = new Moviment();
                        $data = $form->getData();

                        unset($data['id']);

                        if (isset($data['category']) && $data['category'] !== 0) {
                            $data['category'] = $this->dao()->findCategory($data['category']);
                        } else {
                            $data['category'] = null;
                        }

                        $category = $data['category'];
                        unset($data['category']);

                        $moviment->setData($data);

                        $vCategory = $moviment->getInputFilter()->get('category');
                        $vCategory->setValue($category);

                        if ($vCategory->isValid()) {
                            if ($category)
                                $moviment->setCategory($category);

                            $this->dao()->save($moviment);
                            return $this->redirect()->toRoute('moviment', array('action' => 'detail', 'id' => $moviment->getId()));
                        } else {
                            $form->get('category')->setMessages($vCategory->getMessages());
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

        $categories = $this->dao()->fetchCategories($this->getSessionUser());
        return array('form' => $form, 'categories' => $categories);
    }

    public function updateAction() {
        $request = $this->getRequest();

        /* @var $request Request */

        $id = $this->params()->fromRoute('id');

        if ($id == null)
            $id = $request->getPost('id');

        if ($id == null)
            return $this->redirect()->toRoute('moviment');
        else {

            $moviment = $this->dao()->findById($id);

            /* @var $moviment Moviment */
            if ($moviment == null) {
                return $this->redirect()->toRoute('moviment');
            }

            $form = new MovimentUpdate();
            $form->get('cancel')->setAttribute('formaction', $request->getPost('returnTo'));
            $data = $moviment->getData();

            if ($request->isPost()) {
                $submitAction = $request->getPost('submitAction');

                if ($submitAction === 'update') {
                    $form->setData($request->getPost());

                    if ($form->isValid()) {
                        try {
                            $data = $form->getData();

                            unset($data['id']);

                            if (isset($data['category']) && $data['category'] !== 0) {
                                $data['category'] = $this->dao()->findCategory($data['category']);
                            } else {
                                $data['category'] = null;
                            }

                            $category = $data['category'];
                            unset($data['category']);

                            $moviment->setData($data);

                            $vCategory = $moviment->getInputFilter()->get('category');
                            $vCategory->setValue($category);

                            if ($vCategory->isValid()) {
                                if ($category)
                                    $moviment->setCategory($category);

                                $this->dao()->save($moviment);
                                return $this->redirect()->toRoute('moviment', array('action' => 'detail', 'id' => $moviment->getId()));
                            } else {
                                $form->get('category')->setMessages($vCategory->getMessages());
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

            if (isset($data['category']) && $data['category']) {
                $category = $data['category'];
                $data['category'] = $category->getNumber();
            } else {
                $data['category'] = null;
            }

            $form->setData($data);
        }

        $form->get('createButton')->setAttribute('formaction', $this->url()->fromRoute('moviment', array('action' => 'create', 'id' => $moviment->getId())));
        $form->get('detailButton')->setAttribute('formaction', $this->url()->fromRoute('moviment', array('action' => 'detail', 'id' => $moviment->getId())));
        $form->get('removeButton')->setAttribute('formaction', $this->url()->fromRoute('moviment', array('action' => 'remove', 'id' => $moviment->getId())));

        $categories = $this->dao()->fetchCategories($this->getSessionUser());
        return array('form' => $form, 'categories' => $categories);
    }

    public function detailAction() {

        $id = $this->params()->fromRoute('id');

        if ($id == null)
            $id = $this->getRequest()->getPost('id');

        if ($id == null)
            return $this->redirect()->toRoute('moviment');
        else {
            $moviment = $this->dao()->findById($id);

            /* @var $moviment Moviment */
            if ($moviment == null) {
                return $this->redirect()->toRoute('moviment');
            }

            $form = new MovimentDetail();

            $data = $moviment->getData();

            $data['category_number'] = $moviment->getCategory()->getNumber();
            $data['category_code'] = $moviment->getCategory()->getCode();
            $data['category_description'] = $moviment->getCategory()->getDescription();

            $form->setData($data);
        }

        $form->get('createButton')->setAttribute('formaction', $this->url()->fromRoute('moviment', array('action' => 'create', 'id' => $moviment->getId())));
        $form->get('editButton')->setAttribute('formaction', $this->url()->fromRoute('moviment', array('action' => 'update', 'id' => $moviment->getId())));
        $form->get('removeButton')->setAttribute('formaction', $this->url()->fromRoute('moviment', array('action' => 'remove', 'id' => $moviment->getId())));

        return array('form' => $form);
    }

    public function removeAction() {
        $request = $this->getRequest();
        /* @var $request Request */

        $id = $this->params()->fromRoute('id', null);

        if ($id === null && $request->isPost())
            $id = $request->getPost('id');

        if ($id === null)
            return $this->redirect()->toRoute('movimentList');

        $moviment = $this->dao()->findById($id);
        /* @var $moviment Moviment */
        if ($moviment == null) {
            return $this->redirect()->toRoute('moviment');
        }

        $form = new MovimentRemove();

        $returnTo = $request->getPost('returnTo');

        if ($returnTo === null)
            $returnTo = $this->url()->fromRoute('movimentList');

        $form->get('cancel')->setAttribute('formaction', $returnTo);

        if ($request->isPost()) {
            $submitAction = $request->getPost('submitAction');

            if ($submitAction === 'remove') {
                try {
                    $this->dao()->remove($moviment);
                    return $this->redirect()->toRoute('movimentList');
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


        $form->setData($moviment->getData());

        return array('form' => $form);
    }

}

?>
