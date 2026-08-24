# Create Query Skill

## Purpose

Create or modify query functionality using the project's Query architecture.

## Architecture

Contract
    ↓
Base
    ↓
Factory
    ↓
Concrete
    ↓
Manager

## Responsibilities

Query is responsible for:

- query configuration
- query execution
- retrieving data
- filtering
- ordering
- pagination integration
- result preparation

## Rules

Query must not:

- render HTML
- contain presentation logic
- directly control templates
- duplicate pagination implementation
- bypass WordPress query APIs where appropriate

## Procedure

1. Inspect existing Query contracts.
2. Inspect Base classes.
3. Inspect Factory.
4. Inspect Concrete implementations.
5. Inspect Manager.
6. Find similar queries.
7. Reuse existing abstractions.
8. Implement.
9. Run tests.
10. Verify compatibility.