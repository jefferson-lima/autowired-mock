<?php

namespace Jefferson\Lima;

use Doctrine\Common\Annotations\Reader;
use Mockery;
use Mockery\MockInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\FqsenResolver;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\ContextFactory;
use phpDocumentor\Reflection\Types\Object_;
use ReflectionClass;
use ReflectionProperty;

class AutowiredMockProcessor
{
    /** @var Reader */
    private $annotationReader;

    /** @var DocBlockFactory */
    private $docBlockFactory;

    /** @var ContextFactory */
    private $fqsenContextFactory;

    /** @var FqsenResolver */
    private $fqsenResolver;

    public function __construct(
        Reader $annotationReader,
        DocBlockFactory $docBlockFactory,
        ContextFactory $fqsenContextFactory,
        FqsenResolver $fqsenResolver
    ) {
        $this->annotationReader = $annotationReader;
        $this->docBlockFactory = $docBlockFactory;
        $this->fqsenContextFactory = $fqsenContextFactory;
        $this->fqsenResolver = $fqsenResolver;
    }

    /**
     * @param ReflectionProperty $property
     * @return MockInterface|null
     * @throws AutoWiredMockException
     */
    public function getMockForProperty(ReflectionProperty $property): ?MockInterface
    {
        $mockAnnotation = $this->annotationReader->getPropertyAnnotation($property, AutowiredMock::class);

        if ($mockAnnotation) {
            $classType = $this->getAnnotatedType($property);
            $fullClassName = $this->resolveType($classType, $property->getDeclaringClass());

            return Mockery::mock($fullClassName);
        } else {
            return null;
        }
    }

    /**
     * @param ReflectionProperty $property
     * @return string
     */
    private function getAnnotatedType(ReflectionProperty $property): string
    {
        $docBlock = $this->docBlockFactory->create($property->getDocComment());
        $varTags = $docBlock->getTagsByName('var');

        if (count($varTags) !== 1) {
            throw new AutoWiredMockException('There must be exactly one @var tag');
        }

        $varTag = $varTags[0];

        if (!$varTag instanceof Var_) {
            throw new AutoWiredMockException('The @var tag is invalid');
        }

        $varTagType = $varTag->getType();

        if ($varTagType instanceof Compound) {
            $varTagType = $varTagType->get(0);
        }

        if (!$varTagType instanceof Object_) {
            throw new AutoWiredMockException('Invalid value for the @var tag');
        }

        return $varTagType->getFqsen()->__toString();
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param $classType
     * @return string
     */
    private function resolveType(string $classType, ReflectionClass $reflectionClass): string
    {
        $context = $this->fqsenContextFactory->createFromReflector($reflectionClass);

        $classType = str_replace('\\', '', $classType);
        return $this->fqsenResolver->resolve($classType, $context)->__toString();
    }
}
