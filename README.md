# AutowiredMock

This projects provides a simple `@AutowiredMock` annotation to make easier creating mocks with [Mockery](https://github.com/mockery/mockery)

## Installation

```
composer require jefferson-lima/autowired-mock
```

## Usage

In your test class, include the `AutowiredMockTrait`;

```
use AutowiredMockTrait;
``` 

In your test class, annotate the property you want to mock with `@AutowiredMock`, and provide the type you want to mock through the `@var` tag:

```
/**
 * @AutowiredMock
 * @var RealClass
 */
private $mockedProperty;
```

Then call the `setupAutowiredMocks` to initialize the mocks:`

```
protected function setUp(): void
{
    $this->setupAutowiredMocks();
}
```

The `$mockedProperty` will be initialized with a mock for the for the type provided by the `@var` tag.

If you `@var` tag provides multiple types as in:

 ```
 /**
  * @AutowiredMock
  * @var RealClass|AnotherClass|null
  */
 private $mockedProperty;
 ```

the first one will be used.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
