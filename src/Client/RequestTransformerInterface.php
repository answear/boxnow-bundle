<?php

namespace Answear\BoxNowBundle\Client;

use Answear\BoxNowBundle\Request\RequestInterface;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;

interface RequestTransformerInterface
{
    public function transform(RequestInterface $request): PsrRequestInterface;
}
