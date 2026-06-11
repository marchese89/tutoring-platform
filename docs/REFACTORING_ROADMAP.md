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

Branch: `refactor/billing-schema`

- Decide and document the single invoice model.
- Remove the duplicate invoice-sheet workflow.
- Add unique invoice numbering scoped by year.
- Store monetary values with an explicit, consistent representation.
- Replace numeric product and sender types with enums.
- Add missing constraints, indexes, casts, and relationships.
- Rebuild realistic seed data against the final schema.

### 5. Queries and controllers

Branch: `refactor/query-controller-cleanup`

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

- Current branch: `refactor/query-controller-cleanup`
- Base commit: `e787967 Cover upload success flows`
- Completed query/controller work:
  - moved certificate loading for the public about page from Blade to
    `HomeController`
  - added feature coverage for the public about page
  - prepared admin order detail data in `BillingController` instead of using
    duplicate queries and `request('id')` in Blade
  - added feature coverage for the admin order detail page
  - replaced per-item purchase checks in course listings with batched purchased
    product ID lookups
  - added feature coverage for purchased student course listings
  - moved manual extra-invoice PDF generation out of `PurchaseController` and
    into `InvoiceService`
  - consolidated month label formatting through `DateHelper` and removed the
    duplicate `PurchaseService::monthName`
  - moved cart and checkout session summary preparation into `CartController`
  - added feature coverage for cart and checkout page preparation
  - converted the navbar to a class component so cart session inspection no
    longer occurs in Blade
  - converted the shared support chat to a class component that normalizes
    messages for both student and admin contexts
  - removed duplicate chat message display mapping from `StudentController`
  - removed request access from the admin invoice Blade and made missing order
    invoices return 404 from `BillingController`
  - prepared lesson-request dates, statuses, and detail URLs in the controller
    instead of the admin list Blade
  - moved admin chat student-name and Echo preparation out of Blade
  - added end-to-end rendering coverage for the admin chat page
  - replaced the lesson-request `Route::view` with a controller action that
    prepares temporary upload state and its protected preview URL
  - moved temporary lesson and exercise upload state and preview URL
    preparation into their controllers
  - added rendering coverage for both admin teaching create pages
  - replaced the certificate create `Route::view` with a controller action and
    removed direct upload-session access from its Blade
  - moved extra-payment total and Stripe-key preparation into a dedicated
    student payment controller
  - prevented rejected extra-payment details from remaining in session
- Verification: the full suite passes with 73 tests and 231 assertions.
- Previous billing-schema branch is published through
  `e787967 Cover upload success flows`.
- Completed billing-schema work:
  - student invoice listing now reads from the real `invoices` table through
    owned orders
  - the old `student/invoice-sheets/{id}` runtime route, breadcrumb, controller
    action, and Blade view were removed
  - order-to-invoice relationship added to the `Order` model
  - feature coverage verifies that students only see and open their own order
    invoices
  - invoices can now be linked directly to a student and to the payment
    transaction that generated them
  - extra student payments now generate idempotent invoices after verified
    Stripe completion
  - protected invoice PDFs authorize both order-backed and direct
    student-linked invoices
  - legacy `invoice_sheets` and `student_invoices` are migrated into
    `invoices` and removed from the fresh schema
  - invoices now persist source, total amount in cents, currency, customer
    snapshot, line items, and note metadata
  - admin upload session routes are constrained so they are not captured by
    generic numeric resource routes
  - admin photo uploads and lesson-request draft previews have feature coverage
- Billing-schema verification: the full suite passes with 55 tests and 167
  assertions.
- Payment migrations remain applied locally:
  - `2026_06_05_000000_create_payment_transactions_table`
  - `2026_06_05_010000_add_payment_completion_constraints`
  - `2026_06_05_020000_create_invoice_sequences_table`
  - `2026_06_06_000000_link_invoices_to_students_and_payments`
  - `2026_06_06_010000_migrate_legacy_student_invoice_sheets`
  - `2026_06_06_020000_add_metadata_to_invoices_table`
- Existing demo-data issue: invoice number `2` is duplicated three times in
  2026. Existing invoices were intentionally not renumbered.
- Known baseline issue: Pint fails across about 50 untouched files.
- Next action: continue moving display preparation out of Blade files and
  reduce duplicated query code in controllers.
