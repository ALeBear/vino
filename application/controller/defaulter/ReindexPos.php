<?php

namespace horses\controller\defaulter;

use vino\VinoAbstractController;

/**
 * Homepage
 */
class ReindexPos extends VinoAbstractController
{
    /**
     * Default code which should have max availability (here: Menage a Trois)
     * @var string
     */
    const DEFAULT_CODE = '10709152';
    
    
    protected function prepare()
    {
        if (!$this->getUser()->isAdmin()) {
            $this->redirect('/');
        }
        $this->view->error = null;
    }
    
    protected function execute($c = null)
    {
        if ($c) {
            $this->view->error = sprintf(
                "Reindexation of all the points of sale done, %s found.",
                $this->getSaqWebservice()->updateAllPos($c));
        }
    }
    
    protected function prepareView($c = null)
    {
        $this->metas['title'] = "Points of sale reindexation";
        $this->view->code = $c ?: self::DEFAULT_CODE;
    }
}