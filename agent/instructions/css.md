
CSS STANDARD

Use:

- CSS3
- responsive design
- component-oriented naming
- scoped/prefixed class names
- reusable variables
- logical layout systems
- accessibility-conscious states
- mobile-first responsive behavior


Every component should have a unique namespace.

Example:

.abf-component-table
.abf-component-table__header
.abf-component-table__body
.abf-component-table__row
.abf-component-table--responsive


Avoid:

- generic global classes
- !important unless justified
- styling unrelated components
- excessive selector nesting
- duplicate declarations
- leaking component styles globally                                             