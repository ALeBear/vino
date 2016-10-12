<?php

namespace horses\controller\defaulter;

use vino\VinoAbstractController;
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Symfony\Component\DependencyInjection\Container;

/**
 * Sens emails when signature arrivals change
 */
class SignatureWatchman extends VinoAbstractController
{
    protected function prepare()
    {
        $this->view->userData = array();
        $users = $this->getEntityManager()->getRepository('vino\\User')->findAll();

        $filePath = '/tmp/vinoSignature'

        $currentArrivals = explode(',', @file_get_contents($filePath));
        $newArrivals = $this->getSaqWebservice()->getSignatureArrivals();
        $email = '';
        if (is_array($currentArrivals) && count($currentArrivals) > 1) {
            //Act only if the file was already there and not empty
            foreach ($newArrivals as $id => $name) {
                if (!in_array($id, $currentArrivals)) {
                    $email .= $name . "<br/>";
                }
            }
        }
        file_put_contents($filePath, implode(',', array_keys($newArrivals)));
        if ($email) {
            $this->forceLocaleLang('fr_CA');
            $this->sendMail(
                'vino@pouch.name',
                $this->_('mail_subject'),
                $this->_('mail_body', $email)
            );
        }
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
            ->setUsername(base64_decode('dW4uc2luZ2UuZW4uaGl2ZXJAZ21haWwuY29t'))
            ->setPassword(base64_decode('Mm5kanVseQ=='))
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
