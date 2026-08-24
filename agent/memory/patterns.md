# Project Patterns

## Pattern: List Component

Typical flow:

Shortcode
    ↓
Manager
    ↓
Query
    ↓
Pagination
    ↓
Prepared Items
    ↓
Template
    ↓
HTML


## Pattern: Factory

Factory selects the appropriate Concrete implementation.


## Pattern: Manager

Manager coordinates components but should not become a God class.


## Pattern: Template

Template receives prepared data and renders presentation.