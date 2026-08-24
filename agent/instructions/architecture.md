
ARCHITECTURE

Every feature should follow the project's established layered architecture.

Preferred structure:

Contract
    ↓
Base
    ↓
Factory
    ↓
Concrete
    ↓
Manager


CONTRACT

Defines the public behavior and dependencies required by an abstraction.

BASE

Contains reusable implementation shared by multiple concrete implementations.

FACTORY

Responsible for object creation and concrete implementation selection.

CONCRETE

Contains feature-specific implementations.

MANAGER

Coordinates the feature and exposes the appropriate high-level operations.


RULE

Do not create a new architectural layer unless there is a clear architectural
reason.

Do not bypass an existing abstraction merely because directly implementing the
feature appears shorter.