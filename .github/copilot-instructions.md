# Project Coding Standards

# JavaScript Unit Tests
- Use spec files for unit tests.
- Always import jest, describe, it, expect and so on at the top of the file.
- Use `describe` to group related tests.
- Use `it` to define individual test cases.
- Mock all dependencies using `jest.mock` and `jest.fn()`.
- Use `jest.mocked` to mock modules and functions.

## Examples

### Basic test structure
```typescript
// File: ComponentName.spec.ts
import { describe, it, expect, jest } from '@jest/globals';
import { ComponentName } from '../ComponentName';

// Mock dependencies
jest.mock('../path/to/dependency', () => ({
  someFunction: jest.fn(),
}));

describe('ComponentName', () => {
  describe('methodName', () => {
    it('should do something when condition is met', () => {
      // Arrange
      const component = new ComponentName();

      // Act
      const result = component.methodName();

      // Assert
      expect(result).toBe(expectedValue);
    });

    it('should handle errors properly', () => {
      // Test implementation
    });
  });
});
```
