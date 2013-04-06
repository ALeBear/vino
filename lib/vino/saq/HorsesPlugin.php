<?php

namespace vino\saq;

use horses\IPlugin;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;

class HorsesPlugin implements IPlugin
{
    public function bootstrap(Request $request, Container $dependencyInjectionContainer)
    {
        //Add our config
        $dependencyInjectionContainer->get('config')
            ->add('saq', new Config('saq.yml', $dependencyInjectionContainer->get('config_loader')));
        
        //Inject the saq webservice helper
        $lang = $dependencyInjectionContainer->has('locale')
            ? substr($dependencyInjectionContainer->get('locale')->getLang(), 0, 2)
            : 'fr';
        $dependencyInjectionContainer->register('saq_webservice', 'vino\\saq\\webservice')
            ->addMethodCall('injectConfig', array(new Reference('config')))
            ->addMethodCall('injectEntityManager', array(new Reference('entity_manager')))
            ->addMethodCall('injectLanguage', array($lang));
    }
    
    public function dispatch(Request $request, Container $dependencyInjectionContainer)
    {
    }
    
    /**
     * Get the module bootstrap priority, from 0 to 10. 0 = ultra high priority
     * (do not use), 10 = very low.
     * @return integer
     */
    public function getBootstrapPriority()
    {
        return 5;
    }
}
