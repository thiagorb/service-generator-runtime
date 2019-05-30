<?php

namespace Thiagorb\ServiceGeneratorRuntime;

use Psr\Http\Message\ResponseInterface;

abstract class BaseService
{
	/**
	 * @var array
	 */
	protected $meta = [];

	/**
	 * @var ServiceContext
	 */
	protected $context;

	/**
	 * @var string
	 */
	protected $baseUrl;

	public function __construct(ServiceContext $context, string $baseUrl)
	{
		$this->context = $context;
		$this->baseUrl = $baseUrl;
	}

	/**
	 * @return mixed
	 */
	protected function processMessage(string $methodName, array $parameters)
	{
		$methodMeta = $this->meta['methods'][$methodName];
		$requestData = [];
		foreach ($methodMeta['parameters'] as $parameter => $parameterType) {
			if (array_key_exists($parameter, $parameters)) {
				$requestData[$this->decamelize($parameter)] = $this->context->encode($parameterType, $parameters[$parameter]);
			}
		}

		$response = $this->sendRequest($methodMeta, $requestData);
		return $this->handleResponse($methodMeta, $response);
	}

	/**
	 * @return mixed
	 */
	protected function sendRequest(array $methodMeta, array $requestData)
	{
		$request = $this->context->createRequest(
			$methodMeta['http_method'],
			$this->baseUrl . $methodMeta['relative_path'],
			$requestData
		);
		return $this->context->sendRequest($request);
	}

	/**
	 * @return mixed
	 */
	protected function handleResponse(array $methodMeta, ResponseInterface $response)
	{
        return $this->context->decode(
			$methodMeta['return_type'],
			json_decode((string)$response->getBody(), true)
		);
	}

	/**
	 * @return mixed
	 */
    protected function createSubcontract(string $name, string $contract)
    {
        $implementationClass = $this->context->getImplementation($contract);
		return new $implementationClass($this->context, $this->baseUrl . $name . '/');
	}

	protected function decamelize(string $string): string
	{
		return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string) ?: '');
	}
}
