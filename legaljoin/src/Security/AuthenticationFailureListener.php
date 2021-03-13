<?php
namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

class AuthenticationFailureListener
{   
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
    * @param AuthenticationFailureEvent $event
    */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
    {   
        $message = $this->translator->trans('Authentication.Fail');
        $response = new JWTAuthenticationFailureResponse($message,'403');
        $event->setResponse($response);
    }
}