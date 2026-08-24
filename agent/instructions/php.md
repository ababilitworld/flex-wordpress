
PHP STANDARD

Use:

- PHP OOP
- PSR-4
- PSR-12-compatible formatting
- SOLID
- DRY
- dependency injection
- interfaces where abstraction is required
- typed properties
- return types
- parameter types
- strict comparisons
- meaningful exceptions
- Composer autoloading


PREFER

final classes when inheritance is not required.

readonly/value objects where appropriate.

small cohesive classes.

constructor dependency injection.

explicit dependencies.

immutable data where practical.


AVOID

global state.

procedural business logic.

static calls for replaceable dependencies.

God classes.

God methods.

duplicate logic.

hidden dependencies.

untyped public APIs.

unnecessary inheritance.