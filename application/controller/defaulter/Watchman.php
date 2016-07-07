<?php

namespace horses\controller\defaulter;

use vino\VinoAbstractController;
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Symfony\Component\DependencyInjection\Container;

/**
 * Display arrivals list
 */
class Watchman extends VinoAbstractController
{
    protected function prepare()
    {
        $this->view->userData = array();
        $users = $this->getEntityManager()->getRepository('vino\\User')->findAll();
        foreach ($users as $user) {
            /* @var \vino\User $user */
            $watchedQuantities = $user->getWatchingWinesQuantities($this->getEntityManager(), $this->getSaqWebservice());
            $watchedWinesLastUpdate = $user->getWatchingWinesWithQuantities();

            //Act on watched quantities
            foreach ($watchedQuantities as $wineId => $wineData) {
                $wine = $this->getWine($wineId);
                if ($wineData['maximumQuantity'] < $watchedWinesLastUpdate[$wineId] && $wineData['maximumQuantity'] < $user->getSetting('watchingWineAlertThreshold'))
                {
                    $this->forceLocaleLang($user->getSetting('lang'));
                    $this->sendMail(
                        $user->getEmail(),
                        $this->_('mail_subject', $wine->__toString()),
                        $this->_('mail_body', $wine->__toString(), $wineData['maximumQuantity'], $wineData['maximumQuantityPos']->__toString(), $wineId, $wineId)
                    );
                    $user->setWatchingWineQuantity($wineId, $wineData['maximumQuantity']);
                }
            }
            $this->getEntityManager()->persist($user);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * Does this controller have a view?
     * @return boolean
     */
    public function hasView()
    {
        return false;
    }

    protected function sendMail($to, $subject, $message)
    {
        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            ->setUsername('un.singe.en.hiver@gmail.com')
            ->setPassword('2ndjuly')
        ;

        $mailer = Swift_Mailer::newInstance($transport);
        $message = Swift_Message::newInstance($subject)
            ->setFrom(array('noreply@vino' => 'Vino'))
            ->setTo(array($to => 'Vino User'))
            ->setBody($message, 'text/html')
        ;

        $mailer->send($message);
    }

    /**
     * @param $lang
     * @return $this
     */
    protected function forceLocaleLang($lang)
    {
        $locale = $this->dependencyInjectionContainer->get('locale', Container::NULL_ON_INVALID_REFERENCE);
        if ($locale) {
            /* @var \horses\plugin\locale\Locale $locale */
            $locale->setLang($lang);
        }

        return $this;
    }
}
