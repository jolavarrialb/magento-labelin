<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api;

use Labelin\PitneyBowesRestApi\Api\OauthInterface;
use Labelin\PitneyBowesShipping\Helper\Config\FreeShippingConfig as ConfigHelper;
use Laminas\Http\Client;
use Laminas\Http\Client\Adapter\Curl;
use Laminas\Http\Headers;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Stdlib\Parameters;
use Psr\Log\LoggerInterface;

class Oauth implements OauthInterface
{
    protected const URI = '/oauth/token';

    /** @var Headers */
    protected $headers;

    /** @var Request */
    protected $request;

    /** @var Parameters */
    protected $parameters;

    /** @var Client */
    protected $client;

    /** @var ConfigHelper */
    protected $configHelper;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(
        Headers $headers,
        Request $request,
        Parameters $parameters,
        Client $client,
        ConfigHelper $configHelper,
        LoggerInterface $logger
    ) {
        $this->headers = $headers;
        $this->request = $request;
        $this->parameters = $parameters;
        $this->client = $client;

        $this->configHelper = $configHelper;
        $this->logger = $logger;
    }

    /**
     * @return string
     * @throws \JsonException
     */
    public function getAuthToken(): string
    {
        $token = base64_encode($this->configHelper->getApiKey() . ':' . $this->configHelper->getApiSecret());

        $this->headers->addHeaders([
            'Authorization' => 'Basic ' . $token,
            'Content-Type' => Client::ENC_URLENCODED,
        ]);

        $this->request->setHeaders($this->headers);
        $this->request->setUri($this->configHelper->getApiUrl() . static::URI);
        $this->request->setMethod(Request::METHOD_POST);

        $this->request->setPost($this->parameters->set('grant_type', 'client_credentials'));

        $this->client->setOptions([
            'adapter' => Curl::class,
            'maxredirects' => 0,
            'timeout' => 30,
        ]);

        $this->logger->info('REQUEST:');
        $this->logger->info($this->request->toString() . $this->request->getPost()->toString());

        $response = $this->client->send($this->request);
        $content = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $this->logger->info('RESPONSE:');

        if (!array_key_exists('access_token', $content) || $response->getStatusCode() !== Response::STATUS_CODE_200) {
            $errors = $content['errors'];

            foreach ($errors as $error) {
                $this->logger->error('Code: ' . $error['errorCode'] . '. Description: ' . $error['errorDescription']);
            }

            return '';
        }

        $this->logger->info($response->getBody());

        return $content['access_token'];
    }
}
