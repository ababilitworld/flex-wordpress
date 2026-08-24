# Create Template Skill

## Purpose

Create presentation templates without introducing business logic.

## Responsibilities

Template is responsible for:

- presentation
- display preparation
- markup rendering
- display formatting

## Template Must Not

- execute database queries
- contain business rules
- create repositories
- create services
- perform pagination calculations
- modify domain state

## Procedure

1. Inspect Base Template.
2. Inspect existing Concrete Templates.
3. Inspect Template Factory.
4. Inspect Manager integration.
5. Identify prepared display data.
6. Create presentation markup.
7. Escape output appropriately.
8. Verify responsive behavior.
9. Run tests.