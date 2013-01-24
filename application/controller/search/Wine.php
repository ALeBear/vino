<?php

namespace horses\controller\search;

use vino\VinoAbstractController;

/**
 * Wine description page
 */
class Wine extends VinoAbstractController
{
    public function execute($c)
    {
        $code = preg_replace('/[^\d]/', '', $c);
        
        $this->view->wine = $this->getWine($code);
        $this->metas['title'] = $this->_('title', $this->view->wine->__toString(), $this->view->wine->getCode());
    }
}