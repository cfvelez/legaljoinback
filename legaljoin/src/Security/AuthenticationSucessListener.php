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
        $tokenJWT = $data['token'];
        $event->setData($data);
 
        $response->headers->setCookie(new Cookie('BEARER', $tokenJWT, (
             new \DateTime())
             ->add(new \DateInterval('PT' . $this->jwtTokenTTL . 'S')), '/', null, $this->cookieSecure));
    
        return $response;
    }
}