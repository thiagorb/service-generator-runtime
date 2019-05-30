<?php

namespace Thiagorb\ServiceGeneratorRuntime\Transformers;

class NullableTransformer implements TransformerInterface
{
    /**
     * @var array
     */
    protected $innerParameters;

    public function __construct(array $innerParameters)
    {
        $this->innerParameters = $innerParameters;
    }

    public function encode($value)
    {
        return is_null($value) ? null : Builder::build($this->innerParameters)->encode($value);
    }

    public function decode($value)
    {
        return is_null($value) ? null : Builder::build($this->innerParameters)->decode($value);
    }
}