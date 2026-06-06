# Refactoring roadmap

This document is the persistent handoff point for the repository refactoring.
Update it in every branch before moving to the next work package.

## Working rules

- Each work package uses a dedicated branch based on the last verified branch.
- Keep commits small and focused on one behavior or concern.
- Do not mix visual changes with database, security, or payment changes.
- Add or update tests with every behavior change.
- Run the checks listed in the work package before publishing the branch.
- Record the current branch, completed commits, checks, and next action below.

## Work packages

### 1. Payment and HTTP safety

Branch: `security/payment-and-access-foundation`

- Replace state-changing GET routes for cart, logout, and payment creation.
- Separate cart checkout from extra payments.
- Verify Stripe PaymentIntent status on the server.
- Add payment identifiers and idempotent order completion.
- Move email delivery outside the database transaction.
- Protect invoice numbering against concurrent writes.
- Add focused payment and route tests.

### 2. Authorization

Branch: `security/authorization-policies`

- Authorize chat reads, writes, and broadcast subscriptions.
- Use private chat channels.
- Verify ownership of direct lesson requests before cart and purchase actions.
- Protect direct lesson and exercise URLs with purchase checks.
- Require student authentication for lesson-request creation and uploads.
- Add authorization feature tests.

### 3. File handling

Branch: `security/file-uploads`

- Define accepted MIME types and upload size limits.
- Store certificates and other private documents outside the public directory.
- Replace direct file operations with Laravel Storage.
- Centralize file authorization and deletion.
- Add upload and file-access tests.

### 4. Billing schema

Planned branch: `refactor/billing-schema`

- Decide and document the single invoice model.
- Remove the duplicate invoice-sheet workflow.
- Add unique invoice numbering scoped by year.
- Store monetary values with an explicit, consistent representation.
- Replace numeric product and sender types with enums.
- Add missing constraints, indexes, casts, and relationships.
- Rebuild realistic seed data against the final schema.

### 5. Queries and controllers

Planned branch: `refactor/query-and-controller-cleanup`

- Replace purchase N+1 queries with `exists` or eager-loaded queries.
- Remove the remaining database query from `public/about.blade.php`.
- Move display preparation and remaining business logic out of Blade files.
- Introduce Form Requests where validation is repeated or substantial.
- Remove duplicated invoice and payment preparation code.

### 6. Automated tests

Planned branch: `test/application-coverage`

- Configure an isolated testing database.
- Cover authentication, roles, purchases, files, chat, invoices, and routes.
- Add route smoke tests for public, student, and admin areas.
- Add factories for all core models.

### 7. Internal code quality

Planned branch: `refactor/internal-code-quality`

- Complete English naming for internal identifiers and comments.
- Remove legacy routes and `public/custom_javascript/utility.js`.
- Run Pint over the application and keep it enforced.
- Remove unused imports, dead code, magic values, and duplicated helpers.
- Introduce a maintained frontend dependency workflow.

### 8. Localization

Planned branch: `feature/localization-foundation`

- Add language files and locale configuration.
- Move user-facing Italian text out of controllers, Blade files, mail, and breadcrumbs.
- Keep Italian as the initial default locale.
- Add English translations after all keys are centralized.

### 9. Visual consistency

Planned branch: `ui/global-consistency`

- Audit all public, student, admin, authentication, email, and invoice views.
- Complete adoption of shared page, table, form, feedback, and upload components.
- Remove page-specific inline styling where a shared pattern applies.
- Consolidate duplicated checkout and payment JavaScript.
- Verify responsive behavior and primary workflows in the browser.

### 10. Dependencies and release verification

Planned branch: `maintenance/dependency-upgrade`

- Upgrade Laravel and Symfony packages to supported, non-vulnerable versions.
- Run Composer audit with no known advisories.
- Run the complete automated suite and formatting checks.
- Perform a seeded installation and end-to-end browser verification.
- Update setup and testing documentation.

## Current progress

- Current branch: `security/file-uploads`
- Base commit: `515ee41 Record authorization progress`
- Completed commits:
  - `975c66b Restrict uploaded file types and sizes`
  - `8e229eb Store public uploads with generated names`
  - `a5f2a9f Serve protected files from private disk`
  - `e3fb414 Preserve files during upload replacement`
  - `fb550d1 Add upload input accept hints`
  - `6cfacd9 Prune abandoned upload files`
- Verification: the full suite passes with 44 tests and 119 assertions; Pint
  passes on the new upload support, tests, protected-file controller, and
  pruning command.
- File-handling coverage:
  - PDFs are limited to 50 MB and profile images to 5 MB
  - accepted upload types are centralized and configurable
  - public photo and certificate names are generated instead of user-controlled
  - legacy `/files/...` deletion remains supported
  - protected files are read explicitly from the private disk
  - guest, purchase, and student ownership checks have feature coverage
  - replacement uploads persist the new path before deleting the old file
  - every file input has a matching `accept` hint
  - old unreferenced uploads are pruned daily after a 24-hour grace window
- Payment migrations remain applied locally:
  - `2026_06_05_000000_create_payment_transactions_table`
  - `2026_06_05_010000_add_payment_completion_constraints`
  - `2026_06_05_020000_create_invoice_sequences_table`
- Existing demo-data issue: invoice number `2` is duplicated three times in
  2026. Existing invoices were intentionally not renumbered.
- Known baseline issue: Pint fails across about 50 untouched files.
- Next action: publish the completed upload branch, then create
  `refactor/billing-schema` from this branch.
