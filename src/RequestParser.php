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
use Symfony\Component\HttpFoundation\RequestMatcher\HostRequestMatcher;
use Symfony\Component\Webhook\Exception\InvalidArgumentException;

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



            // TODO: add an IpsRequestMatcher | HostRequestMatcher if the trustkey webhook sends requests from a fixed set of ips | hostnames
            // new HostRequestMatcher('pipedream.net'),
            // new IpsRequestMatcher(['3.123.148.230']),
        ]);
    }

    protected function doParse(Request $request, #[\SensitiveParameter] string $secret): ?RemoteEvent
    {
        if (!$secret) {
            throw new InvalidArgumentException('A non-empty secret is required.');
        }
        // in this method you check the request payload to see if it contains
        // the needed information to process this webhook
        $content = $request->toArray();

        if (
            // || !isset($content['signature']['timestamp'])
            // || !isset($content['signature']['token'])
            // || !isset($content['signature']['signature'])
            !isset($content['event'])
            || !isset($content['id'])
            || !isset($content['webhook'])
            || !isset($content['url'])
        ) {
            throw new RejectWebhookException(406, 'Payload is malformed.');
        }

        $this->validateSecret($request, $secret);

        // TODO: since there is no signature in the request, we cannot validate it
        // $this->validateSignature(secret: $secret, signatur: $content['signature']);

        try {
            return $this->converter->convert($content);
        } catch (ParseException $e) {
            throw new RejectWebhookException(406, $e->getMessage(), $e);
        }
    }

    private function validateSecret(Request $request, #[\SensitiveParameter] string $secret): void
    {
        if ($request->headers->get('x-authentication') !== $secret) {
            throw new RejectWebhookException(406, 'Wrong secret.');
        }
    }

    /*
    * example function for validating a signature, has to implemented as soon as trustkey adds signatures
    * private function validateSignature(array $signature, string $secret): void
    * {
    *     if (!hash_equals($signature['signature'], hash_hmac('sha256', $signature['timestamp'].$signature['token'], $secret))) {
    *         throw new RejectWebhookException(406, 'Signature is wrong.');
    *    }
    * }
    */
}
