<?php

namespace Thiagorb\ServiceGeneratorRuntime\Transformers;

abstract class ImmutableObjectTransformer implements TransformerInterface
{
    /**
     * @psalm-var class-string
     * @var string
     */
    protected $objectClass;

    /**
     * @var array[]
     *
     * [
     *     'propertyName' => [
     *         'transformer' => ArrayTransformers::class,
     *         'arguments' => [
     *             [
     *                 'transformer' => PrimitiveTransformers::class,
     *             ]
     *         ],
     *         'defaultValue' => null
     *     ],
     * ],
     */
    protected $propertiesParameters;

    public function encode($value)
    {
        $encoded = [];

        foreach ($this->propertiesParameters as $propertyName => $propertyParameters) {
            /** @todo: find better way to get values, but preferably without relying on reflection */
            $getter = 'get' . ucfirst($propertyName);
            $encodedValue = Builder::build($propertyParameters)->encode($value->$getter());
            if (!is_null($encodedValue)) {
                $encoded[$this->decamelize($propertyName)] = $encodedValue;
            }
        }

        return $encoded;
    }

    public function decode($value)
    {
        $properties = [];

        foreach ($this->propertiesParameters as $propertyName => $propertyParameters) {
            $key = $this->decamelize($propertyName);

            $decoded = Builder::build($propertyParameters)->decode($value[$key] ?? null);

            if (!$decoded && array_key_exists('defaultValue', $propertyParameters)) {
                $decoded = $propertyParameters['defaultValue'];
            }

            $properties[] = $decoded;
        }
        return new $this->objectClass(...$properties);
    }

    protected function decamelize(string $string): string
    {
        return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string) ?: '');
    }
}