<?php

namespace vino;

use \vino\saq\Wine;

use Symfony\Component\DependencyInjection\Container;
use horses\AbstractController;

class VinoAbstractController extends AbstractController
{
    /**
     * Used to filter query (and path) parameters before passing them to magic
     * methods
     * @param string $name
     * @param mixed $value
     * @return mixed the filtered out value
     */
    protected function filterMagicParam($name, $value)
    {
        switch ($name) {
            case 'c':
            case 'id':
                return preg_replace('/[^\d]/', '', $value);
            case 'f':
                return urldecode($value);
            default:
                return parent::filterMagicParam($name, $value);
        }
    }
    
    protected function render()
    {
        $this->view->homeUrl = $this->router->buildRoute('/')->getUrl();
        $this->addJs('/js/add2home.js');
        $this->addCss('/css/add2home.css');
        parent::render();
    }
    
    /**
     * Returns a saq\Wine with this code, create one (NOT PERSISTED YET) if not
     * found
     * @param string $code
     * @return \vino\saq\Wine
     */
    protected function getWine($code)
    {
        /** @var \vino\saq\Wine $wine */
        $wine = $this->dependencyInjectionContainer
            ->get('entity_manager')
            ->getRepository('vino\\saq\\Wine')
            ->findOneBy(array('code' => $code, 'lang' => $this->dependencyInjectionContainer->get('locale')->getLang()));
        $wine || $wine = $this->dependencyInjectionContainer
            ->get('saq_webservice')
            ->getWine($code);
        
        return $wine;
    }
    
    /**
     * Gets a back url from a "from" query string param
     * @param string $from
     * @return string
     */
    protected function getBackUrl($from)
    {
        $boxes = explode('|', urldecode($from), 2);
        $parts = explode('-', array_shift($boxes), 2);
        if (count($parts) < 2) {
            return '/';
        }
        
        $params = count($boxes) ? array('f' => $boxes[0]) : array();
        switch ($parts[0]) {
            case 's':
                $params['q'] = $parts[1];
                return $this->router->buildRoute('search/', $params)->getUrl();
            case 'w':
                $params['c'] = $parts[1];
                return $this->router->buildRoute('search/wine', $params)->getUrl();
            case 'l':
                $params['id'] = $parts[1];
                return $this->router->buildRoute('lists/contents', $params)->getUrl();
            case 'm':
                $params['c'] = $parts[1];
                return $this->router->buildRoute('search/availability', $params)->getUrl();
            default:
                return '/';
        }
    }
    
    /**
     * @return \vino\User
     */
    protected function getUser()
    {
        return $this->dependencyInjectionContainer->get('user', Container::NULL_ON_INVALID_REFERENCE);
    }
    
    /**
     * @return \vino\saq\Webservice
     */
    protected function getSaqWebservice()
    {
        return $this->dependencyInjectionContainer->get('saq_webservice');
    }
}
