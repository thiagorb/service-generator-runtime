<?php

namespace Thiagorb\ServiceGeneratorRuntime\Transformers;

class Builder
{
    /**
     * @template T of TransformerInterface
     *
     * @psalm-param array{transformer: class-string<T>, arguments: array} $transformerDefinition
     */
    public static function build(array $transformerDefinition): TransformerInterface
    {
        return new $transformerDefinition['transformer'](...($transformerDefinition['arguments'] ?? []));
    }
}