# Konomi Project Coding Standards

## General Guidelines
- This project is a WordPress plugin that combines PHP backend with TypeScript/JavaScript frontend
- Follow modular architecture patterns where each feature has its own dedicated module
- Use strict typing in PHP (`declare(strict_types=1)`)

## PHP Guidelines

### Structure and Namespaces
- Follow PSR-4 autoloading standard
- Use namespace `Widoz\Wp\Konomi` for all PHP code
- Place PHP files in the `sources/` directory
- Organize code by feature (module-based architecture)

### Architecture Principles
- **Dependency Injection**: Use constructor injection for dependencies
  - Inject interfaces rather than concrete implementations whenever possible
  - Use the static `::new()` factory method to handle dependency instantiation
  - Do not use global state or Singletons; dependencies should be explicit
- **SOLID Principles**:
  - **Single Responsibility**: Each class should have only one reason to change
  - **Open/Closed**: Classes should be open for extension but closed for modification
  - **Liskov Substitution**: Subtypes must be substitutable for their base types
  - **Interface Segregation**: Many client-specific interfaces are better than one general-purpose interface
  - **Dependency Inversion**: Depend on abstractions, not concretions
- **Separation of Concerns**:
  - Separate business logic from WordPress-specific code
  - Use Modules to encapsulate features (see Module pattern in `sources/`)
  - Separate data access (Repository classes) from business logic
  - Keep controllers thin and move complex logic to dedicated service classes

### PHP Coding Style
- Follow PSR-12 coding style
- Use `function` instead of arrow functions in tests
- Use static constructors with `::new()` pattern for instantiation
- Use type declarations for parameters and return types
- Prefer immutable objects where appropriate
- Use interfaces to define contracts between components

### PHP Testing
- Tests are organized into three categories: unit, integration, and functional
- Place tests in corresponding directories: `tests/unit/php/`, `tests/integration/php/`, and `tests/functional/php/`
- Use Pest PHP for testing (Pest extends PHPUnit with a more expressive syntax)
- Use `describe` and `it` functions for test organization
- Mock WordPress functions using Brain\Monkey
- Mock classes using Mockery
- Test against interfaces rather than implementations when possible

## TypeScript/JavaScript Guidelines

### Structure
- Organize TypeScript code by feature within the `sources/` directory
- Client-side code should be in a `client/` subdirectory within each module
- WordPress blocks belong in the `sources/Blocks/` directory

### TypeScript Coding Style
- Use TypeScript with strict typing
- Define custom types in the `@types` directory
- Define WordPress-related types in `@types/wp/`
- Use the `Konomi` namespace for project-specific types
- Use `Readonly<>` for immutable data structures

### JavaScript/TypeScript Testing
- Place test files in `tests/unit/js/` directory
- Use `.spec.ts` extension for test files
- Organize test files to mirror the structure of source files
- Test files should import Jest globals at the top:
  ```typescript
  import { jest, describe, it, expect, beforeEach } from '@jest/globals';
  ```
- Tests should be organized with `describe` blocks for modules/components
- Use nested `describe` blocks for methods/functions
- Use `it` blocks for specific test cases
- Follow the Arrange-Act-Assert pattern in tests
- Clear mocks between tests with `beforeEach(() => { jest.clearAllMocks() })`

### Mocking in TypeScript Tests
- Mock dependencies using `jest.mock()`
- Use `jest.fn()` for individual functions
- Use `jest.mocked()` for type-safe mocking
- For named imports, mock like this:
  ```typescript
  jest.mock('../path/to/module', () => ({
    namedExport: jest.fn(),
  }));
  ```

## Examples

### Example PHP Test Structure

```php
<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Module;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Module;

describe('ModuleName', function (): void {
    describe('methodName', function (): void {
        it('should do something specific when condition is met', function (): void {
            // Arrange
            $mockDependency = \Mockery::mock(Module\Dependency::class);
            $mockDependency
                ->expects('method')
                ->once()
                ->andReturn('expected value');

            $module = Module\ModuleName::new($mockDependency);

            // Act & Assert
            expect($module->methodName())->toBe('expected value');
        });
    });
});
```

### Example TypeScript Test Structure

```typescript
import { jest, describe, it, expect, beforeEach } from '@jest/globals';
import { someHelper } from '@test/helpers';
import { functionUnderTest } from '../../../sources/Module/path/to/file';

// Mock dependencies
jest.mock('../../../sources/Module/path/to/dependency', () => ({
  dependencyFunction: jest.fn(),
}));

describe('ModuleName', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  describe('functionUnderTest', () => {
    it('should do something specific when condition is met', () => {
      // Arrange
      const testData = { prop: 'value' };
      jest.mocked(dependencyFunction).mockReturnValue('mocked value');

      // Act
      const result = functionUnderTest(testData);

      // Assert
      expect(result).toBe(expectedValue);
      expect(dependencyFunction).toHaveBeenCalledWith(testData);
    });

    it('should handle errors properly', async () => {
      // Mock error case
      jest.mocked(dependencyFunction).mockRejectedValue(new Error('Test error'));

      // Assert rejection
      await expect(functionUnderTest(testData)).rejects.toThrow('Test error');
    });
  });
});
```
