<?php

namespace bitbirddev\TrustkeyWebhookBundle;

use bitbirddev\TrustkeyWebhookBundle\PayloadConverter;
use bitbirddev\TrustkeyWebhookBundle\RequestMatcher\HeaderRequestMatcher;
use Symfony\Component\HttpFoundation\ChainRequestMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher\IpsRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\IsJsonRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\MethodRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\RemoteEvent\Exception\ParseException;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Webhook\Client\AbstractRequestParser;
use Symfony\Component\Webhook\Exception\RejectWebhookException;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: 'bitbirddev.webhook.request_parser.trustkey')]
class RequestParser extends AbstractRequestParser
{
    public function __construct(
        private readonly PayloadConverter $converter
    ) {
    }

    protected function getRequestMatcher(): RequestMatcherInterface
    {
        return new ChainRequestMatcher([
            new MethodRequestMatcher(methods: ['POST']),
            new IsJsonRequestMatcher(),
            new HeaderRequestMatcher('x-authentication'),
            // new IpsRequestMatcher(['3.123.148.230']),
        ]);
    }

    protected function doParse(Request $request, string $secret): ?RemoteEvent
    {
        $this->validateSecret($request, $secret);
        // $this->validateSignature(secret: $secret, $signature);

        // in this method you check the request payload to see if it contains
        // the needed information to process this webhook
        $content = $request->toArray();

        try {
            return $this->converter->convert($content);
        } catch (ParseException $e) {
            throw new RejectWebhookException(406, $e->getMessage(), $e);
        }
    }

    private function validateSecret(Request $request, string $secret): void
    {
        if ($request->headers->get('x-authentication') !== $secret) {
            throw new RejectWebhookException(406, 'Wrong secret.');
        }
    }

    private function validateSignature(array $signature, string $secret): void
    {
        if (!hash_equals($signature['signature'], hash_hmac('sha256', $signature['timestamp'].$signature['token'], $secret))) {
            throw new RejectWebhookException(406, 'Signature is wrong.');
        }
    }
}
