# Manager Architecture Evaluation

## Task

Create a Manager for a new List feature.

## Expected Behavior

The agent must:

- inspect existing Manager implementations
- inspect the Manager Contract
- reuse the existing Base class where applicable
- use Factory where required
- coordinate feature components
- avoid putting business logic into the Manager
- follow existing namespace conventions

## Failure Conditions

Fail if the agent:

- creates procedural code
- bypasses existing abstractions
- duplicates existing functionality
- creates unrelated classes
- places rendering logic inside Manager
- places database implementation inside Manager