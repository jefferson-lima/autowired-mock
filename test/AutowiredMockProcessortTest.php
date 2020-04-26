<?php

namespace Jefferson\Lima\Test;

use Doctrine\Common\Annotations\AnnotationReader;
use Jefferson\Lima\AutowiredMock;
use Jefferson\Lima\AutoWiredMockException;
use Jefferson\Lima\AutowiredMockProcessor;
use Mockery\MockInterface;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\FqsenResolver;
use phpDocumentor\Reflection\Types\ContextFactory;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class AutowiredMockProcessortTest extends TestCase
{
    /** @var ReflectionClass */
    private static $reflectionClass;

    /** @var AutowiredMockProcessor */
    private $autowiredMockProcessor;

    /**
     * @AutowiredMock
     */
    private $noVarTag;

    /**
     * @AutowiredMock
     * @var AutoWiredMockException
     * @var AutowiredMockProcessortTest
     */
    private $multipleVarTags;

    /**
     * @AutowiredMock
     * @var
     */
    private $varTagWithNoType;

    /**
     * @AutowiredMock
     * @var null
     */
    private $varTagWithNullType;

    /**
     * @AutowiredMock
     * @var AutoWiredMockException
     */
    private $validPropertySingleType;

    /**
     * @AutowiredMock
     * @var AutoWiredMockException|null|AutowiredMockProcessortTest
     */
    private $validPropertyMultipleTypes;

    public static function setUpBeforeClass(): void
    {
        static::$reflectionClass = new ReflectionClass(get_class());
    }

    protected function setUp(): void
    {
        $this->autowiredMockProcessor = new AutowiredMockProcessor(
            new AnnotationReader(),
            DocBlockFactory::createInstance(),
            new ContextFactory(),
            new FqsenResolver()
        );
    }

    public function invalidAnnotationDataProvider(): array
    {
        return [
            'noVarTag' => ['noVarTag'],
            'multipleVarTags' => ['multipleVarTags'],
            'varTagWithNoType' => ['varTagWithNoType'],
            'varTagWithNullType' => ['varTagWithNullType']
        ];
    }

    /**
     * @dataProvider invalidAnnotationDataProvider
     * @param string $property
     * @throws ReflectionException
     */
    public function testMockPropertyWithInvalidAnnotationDataProvider(string $property): void
    {
        $this->expectException(AutoWiredMockException::class);
        $property = static::$reflectionClass->getProperty($property);
        $this->autowiredMockProcessor->getMockForProperty($property);
    }

    public function validPropertyDataProvider(): array
    {
        return [
            'validPropertySingleType' => ['validPropertySingleType', AutoWiredMockException::class],
            'validPropertyMultipleTypes' => ['validPropertyMultipleTypes', AutoWiredMockException::class],
        ];
    }

    /**
     * @dataProvider validPropertyDataProvider
     * @param string $property
     * @param string $type
     * @throws ReflectionException
     */
    public function testMockPropertyWithValidProperty(string $property, string $type): void
    {
        $property = static::$reflectionClass->getProperty($property);
        $expectedMock = $this->autowiredMockProcessor->getMockForProperty($property);

        $this->assertTrue($expectedMock instanceof MockInterface);
        $this->assertTrue($expectedMock instanceof $type);
    }
}
