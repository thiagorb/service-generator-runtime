<?php

namespace Thiagorb\ServiceGeneratorRuntime\Transformers;

class ArrayTransformer implements TransformerInterface
{
    /**
     * @var array
     */
    protected $itemsParameters;

    public function __construct(array $itemsParameters)
    {
        $this->itemsParameters = $itemsParameters;
    }

    public function encode($value)
    {
        $encoded = [];

        foreach ($value as $key => $item) {
            $encoded[$key] = Builder::build($this->itemsParameters)->encode($item);
        }

        return $encoded;
    }

    public function decode($value)
    {
        $decoded = [];

        foreach ($value as $key => $item) {
            $decoded[$key] = Builder::build($this->itemsParameters)->decode($item);
        }

        return $decoded;
    }
}