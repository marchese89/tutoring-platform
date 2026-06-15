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

Branches: `test/application-coverage`, `test/core-model-factories`,
`test/factory-based-test-cleanup`

Status: completed for the current application surface.

- Configure an isolated testing database.
- Cover authentication, roles, purchases, files, chat, invoices, and routes.
- Add route smoke tests for public, student, and admin areas.
- Add factories for all core models.

### 7. Internal code quality

Status: in progress across focused branches.

- Complete English naming for internal identifiers and comments.
- Remove legacy routes and `public/custom_javascript/utility.js`.
- Run Pint over the application and keep it enforced.
- Remove unused imports, dead code, magic values, and duplicated helpers.
- Introduce a maintained frontend dependency workflow.

Completed:

- Repository-wide Pint formatting is clean.
- Product types and chat sender roles use enums instead of numeric magic values.
- Password visibility controls use one shared Blade component.
- `public/custom_javascript/utility.js` and unused public stylesheets were removed.

### 8. Localization

Planned branch: `feature/localization-foundation`

- Add language files and locale configuration.
- Move user-facing Italian text out of controllers, Blade files, mail, and breadcrumbs.
- Keep Italian as the initial default locale.
- Add English translations after all keys are centralized.

### 9. Visual consistency

Status: in progress across focused UI branches.

- Audit all public, student, admin, authentication, email, and invoice views.
- Complete adoption of shared page, table, form, feedback, and upload components.
- Remove page-specific inline styling where a shared pattern applies.
- Consolidate duplicated checkout and payment JavaScript.
- Verify responsive behavior and primary workflows in the browser.
- Audit PDF, image, and iframe viewers and consolidate repeated markup into shared media-viewer components.

Completed so far:

- Bootstrap primary colors now use a coherent, accessible palette.
- Page and component styles are emitted through the layout style stack.
- Lesson and exercise document previews use a shared component.
- Cart and checkout use shared Bootstrap components, and cart and extra
  payments use one shared Stripe payment form and script.
- Public, authentication, student, and admin pages already use several shared
  card, form, table, upload, chat, and page-header components.
- Lesson and exercise create/edit pages share a reusable document upload
  component and consistent form layouts.
- Theme-area, subject, and course management pages share consistent form and
  table components, use prepared relationship counts, and prevent deletion of
  records that still contain child content.
- Course listing and detail pages use the shared table system, responsive
  action layouts, explicit empty states, and focused rendering tests.
- The public home page uses a full-width, responsive presentation focused on
  the real course, lesson-request, tutoring, pricing, and review workflows.
- Protected invoices, lessons, exercises, lesson requests, and admin chat
  documents use one responsive PDF viewer component.
- Certificate pages and temporary upload previews use the compact variant of
  the shared PDF viewer; the admin photo page has a responsive empty state and
  image preview without inline sizing.
- The admin extra-invoice form uses the shared form system, including a
  reusable textarea field, and its result page uses the shared feedback card.
- Admin and student order histories use one shared period-filtered table with
  safe DOM rendering, consistent loading and empty states, and no inline event
  handlers.
- Admin and student order details and invoice PDF pages use shared billing
  components; the obsolete AJAX order method and server-rendered row partial
  were removed.
- Admin and student credential pages use one shared component and controller;
  password strength rules are shared with registration through the framework
  password rule.
- Admin and student address forms use one shared component, and account data
  is prepared by controllers instead of lazy-loading relationships in Blade.
- Public, admin, and student lesson-request pages use shared request tables,
  request summaries, file fields, PDF viewers, and upload-progress controls.
- The public lesson-request form no longer uses nested cards, page-specific
  styles, inline navigation handlers, or authentication checks inside Blade.

### 10. Dependencies and release verification

Status: dependency upgrade completed; release verification remains open.

- Upgrade Laravel and Symfony packages to supported, non-vulnerable versions.
- Run Composer audit with no known advisories.
- Run the complete automated suite and formatting checks.
- Perform a seeded installation and end-to-end browser verification.
- Update setup and testing documentation.

## Current status

Last verified: 2026-06-15.

- Current branch: `refactor/remaining-view-patterns`.
- Latest published commit: `e08b834 Unify certificate and upload previews`.
- Automated verification: 107 tests and 430 assertions pass.
- Laravel version: 12.62.0.
- `composer audit --locked`: no known security advisories.
- Repository-wide Pint verification passes.
- Core model factories: 18 factories available.
- Blade query audit: no remaining direct model queries found.
- Payment completion verifies the stored Stripe PaymentIntent, amount,
  currency, ownership, and idempotency before fulfillment.
- Chat writes and private broadcast subscriptions are authorized through the
  chat policy.
- Lesson-request writes and uploads require an authenticated student.
- Protected uploads use centralized MIME and size rules and private storage
  where appropriate.
- Invoice workflows use the unified `invoices` table and concurrent invoice
  numbering is protected by `invoice_sequences` and row locking.
- Seeder credentials are documented in `.env.example`.
- Product and chat sender types use enums.
- The legacy password utility JavaScript and unused public CSS were removed.
- Blade styles use the layout stack, and repeated lesson/exercise document
  preview markup uses a shared component.
- Cart and extra-payment pages share the same Stripe form and script.
- Result pages share a reusable feedback-card component.
- Theme-area, subject, lesson, exercise, and course management pages use the
  shared form and table system.
- The public home page was redesigned and visually verified at 1440 px desktop
  and 390 px mobile widths without horizontal overflow.
- The admin lesson-request detail page uses the shared page, card, form,
  upload-progress, and PDF-viewer components without inline layout styles.
- The media-viewer audit is complete: raw PDF iframes remain only inside the
  two intentional shared viewer components.
- The admin extra-invoice form and confirmation page use the shared form and
  feedback components without page-specific inline styling.
- The billing view audit is complete: order histories, order details, invoice
  documents, and the manual invoice flow use shared responsive components.
- The account-settings audit is complete: credential and address forms are
  shared, account relations are resolved before rendering, and both roles use
  the same email and password validation workflow.
- The structural visual audit is complete for billing, account settings, and
  lesson requests. Seeded browser verification remains part of release work.
- All four critical findings from the original security audit are resolved:
  server-side payment verification, chat authorization, lesson-request
  ownership protection, and vulnerable framework dependencies.

### Remaining work

1. Complete English naming for internal identifiers and comments. Keep
   user-facing Italian text unchanged until localization keys are introduced.
2. Add the localization foundation with Italian as the default locale and
   English as the second supported language.
3. Review monetary fields, model relationships, and database constraints, then
   decide whether transitional billing migrations should be squashed before
   the first stable release.
4. Split the monolithic `DatabaseSeeder` into focused seeders and verify a
   fresh seeded installation in a disposable database. The current PHP CLI
   does not have PDO SQLite, so the in-memory installation check cannot run
   locally yet.
5. Introduce a maintained frontend dependency workflow and consolidate global
   CSS and reusable JavaScript after the view audit is stable.
6. Add continuous integration for tests, Pint, and Composer security audits.
7. Perform seeded end-to-end browser verification and update installation,
   testing, and demo-account documentation before release.

### Next action

Start the internal English cleanup, followed by the localization foundation.

## Historical progress

- Current branch: `test/application-coverage`
- Base commit: `ea350b0 Prepare login return intent`
- Completed automated-test work:
  - added `StudentFactory` and `AdminFactory` with role-correct user records
  - added the missing `Admin::user()` relationship
  - added feature coverage for both account factories
  - added smoke coverage for public, student, and admin top-level pages
  - made the home page render safely before an admin account is seeded
- Verification: the full suite passes with 81 tests and 289 assertions.
- Previous query/controller branch is published through
  `ea350b0 Prepare login return intent`.
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
  - replaced the login `Route::view` with a controller action and removed
    request access from the login Blade
  - added coverage for student login redirects with and without return intent
- Query/controller verification: the full suite passes with 76 tests and 237
  assertions.
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
- Next action: continue core model factories and use them to simplify feature
  test setup.
