<?php

namespace horses\controller\arrival;

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
            $this->view->importedCount = $this->getSaqWebservice()->updateArrivals(
                $this->request->files->get('csvfile')->getPathname(),
                new DateTime($this->request->request->get('date')),
                $this->request->request->get('overwrite')
            );
        }
    }
    
    protected function prepareView()
    {
    }
}