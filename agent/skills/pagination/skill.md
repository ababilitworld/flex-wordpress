# Create Pagination Skill

## Purpose

Create or modify pagination functionality while preserving the project's
existing pagination architecture.

## Required Architecture

Use:

Contract
    ↓
Base
    ↓
Factory
    ↓
Concrete
    ↓
Manager

## Procedure

1. Inspect the existing Pagination package.
2. Locate the Pagination Contract.
3. Locate the Pagination Base class.
4. Locate the Pagination Factory.
5. Locate existing Concrete implementations.
6. Locate the Pagination Manager.
7. Inspect existing Query integration.
8. Inspect existing Template integration.
9. Inspect existing tests.
10. Reuse existing abstractions whenever possible.
11. Implement the smallest required change.
12. Run relevant tests.
13. Fix failures.
14. Verify backward compatibility.

## Restrictions

Do not:

- create duplicate pagination logic
- calculate pagination inside Template
- put database logic inside Template
- bypass the Pagination abstraction
- modify unrelated components
- introduce unnecessary dependencies