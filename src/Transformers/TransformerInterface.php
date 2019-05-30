<?php

namespace Thiagorb\ServiceGeneratorRuntime\Transformers;

interface TransformerInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function encode($value);

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function decode($value);
}