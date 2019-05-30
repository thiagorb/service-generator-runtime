<?php

namespace Thiagorb\ServiceGeneratorRuntime;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Thiagorb\ServiceGeneratorRuntime\Transformers\Builder;

abstract class ServiceContext
{
	/**
	 * @var array
	 */
	protected $meta = [];

	/**
	 * @var ClientInterface
	 */
	protected $client;

	/**
	 * @var RequestFactory
	 */
	protected $requestFactory;

	public function __construct(ClientInterface $client, RequestFactory $requestFactory)
	{
		$this->client = $client;
		$this->requestFactory = $requestFactory;
	}

	public function createRequest(string $httpMethod, string $url, array $data): RequestInterface
	{
		return $this->requestFactory->create($httpMethod, $url, $data);
	}

	public function sendRequest(RequestInterface $request): ResponseInterface
	{
		return $this->client->sendRequest($request);
	}

	/**
	 * @psalm-return class-string
	 */
    public function getImplementation(string $contract): string
    {
        return $this->meta['contracts'][$contract]['implementation'];
	}

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public function encode(array $typeArray, $value)
	{
		return Builder::build($typeArray)->encode($value);
	}

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public function decode(array $typeArray, $value)
	{
		return Builder::build($typeArray)->decode($value);
	}
}
