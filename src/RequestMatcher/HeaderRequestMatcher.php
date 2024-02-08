<?php

namespace bitbirddev\TrustkeyWebhookBundle\RequestMatcher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class HeaderRequestMatcher implements RequestMatcherInterface
{
    public function __construct(private string $name)
    {
    }

    public function matches(Request $request): bool
    {
        return $request->headers->has($this->name);
    }
}
