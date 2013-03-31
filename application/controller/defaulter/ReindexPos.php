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
    
    
    public function prepare()
    {
        if (!$this->dependencyInjectionContainer->get('user')->isAdmin()) {
            $this->redirect('/');
        }
        $this->view->error = null;
        $this->metas['title'] = "Points of sale reindexation";
    }
    
    public function execute()
    {
        $code = $this->request->get('c');
        $this->view->code = $code ? preg_replace('/[^\d]/', '', $code) : self::DEFAULT_CODE;
    }
    
    public function post()
    {
        $count = $this->dependencyInjectionContainer
            ->get('saq_webservice')
            ->generatePosFile(
                $this->request->attributes->get('DIR_HTDOCS') . $this->dependencyInjectionContainer->get('config')->get('saq.availability.posFile'),
                preg_replace('/[^\d]/', '', $this->request->get('c')));
        $this->view->error = sprintf("Reindexation of all the points of sale done, %s found.", $count);
    }
}