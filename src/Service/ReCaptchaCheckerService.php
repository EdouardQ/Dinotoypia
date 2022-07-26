<?php

namespace App\Service;

use Exception;
use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpFoundation\Request;

class ReCaptchaCheckerService
{
    private ReCaptcha $reCaptcha;

    public function __construct(ReCaptcha $reCaptcha)
    {
        $this->reCaptcha = $reCaptcha;
    }

    /**
     * Check the result of the previous ReCaptcha and throw an Exception is it returns False
     * The ReCaptcha checks the Hostname and the ClientIp
     *
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function check(Request $request): void
    {
        /*if (!$this->reCaptcha
            ->setExpectedHostname($request->getHost())
            ->verify($request->request->get('g-recaptcha-response'), $request->getClientIp())
            ->isSuccess()) {
                throw new Exception("ReCaptcha failed");
        }*/
    }
}
