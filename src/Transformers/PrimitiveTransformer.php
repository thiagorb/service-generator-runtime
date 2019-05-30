<?php

namespace Thiagorb\ServiceGeneratorRuntime\Transformers;

class PrimitiveTransformer implements TransformerInterface
{
    public function encode($value)
    {
        return $value;
    }

    public function decode($value)
    {
        return $value;
    }
}