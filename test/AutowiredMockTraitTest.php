<?php

namespace Jefferson\Lima\Test;

use Jefferson\Lima\AutowiredMock;
use Jefferson\Lima\AutowiredMockTrait;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class AutowiredMockTraitTest extends TestCase
{
    use AutowiredMockTrait;

    /**
     * @AutowiredMock
     * @var AutowiredMockTraitTest|null|AutowiredMockProcessortTest
     */
    private $mockedProperty;

    protected function setUp(): void
    {
        $this->setupAutowiredMocks();
    }

    public function testMockedProperty(): void
    {
        $this->assertTrue($this->mockedProperty instanceof MockInterface);
        $this->assertTrue($this->mockedProperty instanceof AutowiredMockTraitTest);
    }

}