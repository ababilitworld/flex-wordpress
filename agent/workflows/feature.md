# Feature Development Workflow

## Phase 1 — Understand

1. Read project instructions.
2. Read relevant architecture documentation.
3. Search the repository.
4. Find related implementations.
5. Find tests.
6. Identify dependencies.

## Phase 2 — Plan

Determine:

- affected packages
- affected classes
- contracts
- dependencies
- tests
- backward compatibility concerns

## Phase 3 — Implement

Implement the smallest correct change.

Do not modify unrelated files.

## Phase 4 — Verify

Run:

1. PHP syntax validation
2. Composer validation
3. PHPUnit
4. Static analysis when configured
5. JavaScript tests when applicable
6. WordPress-specific checks when applicable

## Phase 5 — Self Correction

If a test fails:

1. Read the failure.
2. Identify the root cause.
3. Modify the implementation.
4. Re-run the failed test.
5. Run the broader test suite.
6. Continue until verification succeeds or a genuine blocker remains.

## Phase 6 — Review

Review:

- architecture
- security
- compatibility
- duplication
- naming
- unnecessary complexity
- unrelated modifications