<?php

namespace horses\controller\arrival;

use RuntimeException;
use vino\VinoAbstractController;
use DateTime;

/**
 * Display list contents
 */
class Admin extends VinoAbstractController
{
    protected function prepare($id = null, $a = null)
    {
        $this->view->error = false;
        $this->view->importedCount = null;
    }
    
    protected function execute()
    {
        if ($this->request->request->get('process')) {
            try {
                $this->getEntityManager()->beginTransaction();
                $this->view->importedCount = $this->getSaqWebservice()->updateArrivals(
                    $this->request->files->get('csvfile')->getPathname(),
                    new DateTime($this->request->request->get('date')),
                    $this->request->request->get('overwrite')
                );
                $this->getEntityManager()->commit();
            } catch (RuntimeException $e) {
                $this->getEntityManager()->rollback();
                $this->view->error = $e->getMessage();
            }
        }
    }
    
    protected function prepareView()
    {
    }
}