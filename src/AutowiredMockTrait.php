<?php

namespace Jefferson\Lima;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\FqsenResolver;
use phpDocumentor\Reflection\Types\ContextFactory;
use ReflectionClass;
use ReflectionException;

trait AutowiredMockTrait
{
    /**
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function setupAutowiredMocks(): void
    {
        $autowiredMockProcessor = new AutowiredMockProcessor(
            new AnnotationReader(),
            DocBlockFactory::createInstance(),
            new ContextFactory(),
            new FqsenResolver()
        );

        $reflectionClass = new ReflectionClass(get_class());
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $property->setValue($this, $autowiredMockProcessor->getMockForProperty($property));
        }

    }
}
