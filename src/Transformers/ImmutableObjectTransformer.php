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
     *         'encodedName' => 'property_name',
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
                $encoded[$propertyParameters['encodedName']] = $encodedValue;
            }
        }

        return $encoded;
    }

    public function decode($value)
    {
        $properties = [];

        foreach ($this->propertiesParameters as $propertyName => $propertyParameters) {
            $key = $propertyParameters['encodedName'];

            $decoded = Builder::build($propertyParameters)->decode($value[$key] ?? null);

            if (!$decoded && array_key_exists('defaultValue', $propertyParameters)) {
                $decoded = $propertyParameters['defaultValue'];
            }

            $properties[] = $decoded;
        }
        return new $this->objectClass(...$properties);
    }
}