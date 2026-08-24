# Project Architecture

## General Architecture

The project follows:

Contract
    ↓
Base
    ↓
Factory
    ↓
Concrete
    ↓
Manager

## Package Architecture

Packages should be:

- PSR-4 compatible
- Composer compatible
- GitHub friendly
- OOP
- SOLID
- DRY
- extensible
- replaceable
- testable

## Dependency Rule

Higher-level components should depend on abstractions rather than concrete
implementations whenever practical.

## Presentation Rule

Presentation components must not contain business logic.

## Query Rule

Query components are responsible for data retrieval.

## Template Rule

Template components are responsible for presentation.

## Manager Rule

Managers coordinate feature components.