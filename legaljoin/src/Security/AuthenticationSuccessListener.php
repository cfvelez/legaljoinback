<?php
namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;

class AuthenticationSuccessListener
{   
   
    private $jwtTokenTTL;

    private $cookieSecure = false;

    public function __construct($ttl)
    {
        $this->jwtTokenTTL = $ttl;
    }

    /**
    * @param AuthenticationSucessEvent $event
    */
    public function onAuthenticationSucessResponse(AuthenticationSuccessEvent $event)
    {   
        $response = $event->getResponse();
        $data = $event->getData();
        //unset($data['token']);
        //unset($data['refresh_token']);
 
         $response->headers->setCookie(new Cookie('BEARER', $data['token'], (
             new \DateTime())
             ->add(new \DateInterval('PT' . $this->jwtTokenTTL . 'S')), '/', null, $this->cookieSecure));
    
        return $response;
    }
}