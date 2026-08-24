# Architecture Decisions

## ADR-001

Date: 2026-08-23

Decision:

Pagination is a reusable package rather than being implemented directly
inside individual List components.

Reason:

Multiple List components require pagination.

Result:

Transaction List, User List, Account List, etc. can reuse the same
pagination abstraction.


## ADR-002

Date: 2026-08-23

Decision:

Templates receive prepared display data.

Templates must not execute database queries.

Reason:

Separation of concerns and testability.