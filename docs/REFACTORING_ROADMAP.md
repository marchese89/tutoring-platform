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

Status: completed for the current application surface.

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
- Remaining public lesson, exercise, order, and shared document-preview headings
  use translation catalogs instead of hardcoded Blade strings.

### 8. Localization

Branch: `feature/localization-foundation`

Status: complete for the current application surface.

- Italian is the default locale and English is the supported fallback.
- Users can change locale from the shared navbar; the choice persists in the
  session and unsupported locales are rejected.
- Organized Italian and English catalogs cover navigation, shared UI,
  authentication, account settings, password reset, and validation messages.
- Authentication and shared account views use translation keys.
- Breadcrumb labels and the operational public surface use translation keys,
  including the catalog, cart, checkout, lesson request, and result pages.
- The public home page is fully available in Italian and English, including
  accessible image and rating labels.
- The shared student dashboard, account menu, course/request/order/invoice
  listings, order details, month labels, and support chat use localized text.
- Student detail pages for courses, lessons, exercises, direct requests,
  reviews, extra payments, payment confirmation, and localized number
  formatting are covered.
- Admin billing pages use localized text for sales, invoice lists, invoice
  documents, extra invoice creation, confirmation states, and validation labels.
- Admin teaching management pages use localized text for the teaching
  dashboard, theme areas, subjects, courses, lesson forms, exercise forms, and
  empty states.
- Admin student and settings pages use localized text for student request
  management, admin chats, account/profile cards, photo uploads, VAT settings,
  and certificate workflows.
- Email templates, email subjects, PDF invoice labels, and shared PDF viewer
  fallback text use Italian and English catalogs.
- Privacy and cookie policy pages render from Italian and English language
  catalogs.
- The final focused residual audit removed hardcoded admin dashboard, auth,
  password-reset, upload-progress, legal, mail, and PDF viewer strings from
  views/controllers.
- The first internal cleanup pass moved remaining teaching CRUD flash messages,
  validation attribute labels, order product-type labels, password-reset
  feedback, and seeded demo chat text out of Italian literals in controllers
  and seeders.
- Locale behavior and translated validation have focused feature coverage.
- Remaining strings found by broad search are either translation catalog
  entries, CSS/JavaScript implementation details, fixture data, or non-user
  exception/debug messages.

### 9. Visual consistency

Status: complete for the audited application surface; final seeded browser
verification remains part of release verification.

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

Last verified: 2026-06-16.

- Current branch: `refactor/schema-hardening`.
- Latest schema-hardening milestone: transition migrations were folded into the
  base schema, unused legacy billing/subscription schema was removed, and
  required domain fields are no longer nullable in fresh installs.
- Automated verification: 133 tests and 654 assertions pass.
- Test database: SQLite `:memory:` through `phpunit.xml`; the previous MySQL
  testing database is no longer required for automated tests.
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
- The localization foundation supports Italian and English through
  configuration, middleware, a session-backed selector, translated validation,
  and localized authentication and account-setting workflows.
- Public and student-facing operational pages are localized in Italian and
  English; the numeric formatter no longer requires the PHP `intl` extension.
- Admin billing, teaching management, student request/chat, and settings
  workflows are localized in Italian and English.
- Email notifications, reset-password email content, invoice PDF labels, and
  shared PDF viewer fallback text are localized in Italian and English.
- Privacy and cookie policy pages are localized in Italian and English.
- Admin dashboard cards, login/reset feedback, and shared upload progress
  fallback text are localized in Italian and English.
- Teaching CRUD feedback, validation labels, order product-type labels, and
  password-reset feedback no longer contain hardcoded Italian in controllers.
- Remaining public lesson, exercise, order, and shared document-preview headings
  were moved from Blade literals to Italian and English translation catalogs.

## Final completion plan

The refactoring is no longer tracked as an open-ended audit. The final work is
tracked as six packages. Package A is complete, so five packages remain before
the branch series is ready for merge/squash review.

### Package A. Internal cleanup closure

Branch: `refactor/internal-code-cleanup`

Status: completed.

Completed commits:

- `86899e1` Add UX and pagination package to roadmap.
- `061b5e2` Localize remaining Blade headings.

Definition of Done:

- Remove stale generated comments and docblocks that do not add project
  knowledge.
- Keep framework/vendor-style comments only where they clarify Laravel
  extension points.
- Run Pint and the full test suite.

Verification:

- `vendor\bin\pint --test`
- `php artisan test` - 126 passed, 634 assertions.

### Package B. Model and migration hardening

Branch: `refactor/schema-hardening`

Status: completed.

Definition of Done:

- Review every application table for missing foreign keys, indexes, nullability,
  cascade behavior, and uniqueness constraints.
- Review models for casts, relationships, fillable fields, and enum usage.
- Decide explicitly whether billing transition migrations are kept or squashed
  before a stable public release.
- Run migrations on a disposable database and run the full test suite.

Completed so far:

- `phpunit.xml` uses SQLite `:memory:` for isolated automated tests.
- Invoice sequence migration groups legacy invoice years in PHP instead of
  using MySQL `YEAR()`.
- Admin and student order filters derive available years and months through
  database-neutral date parsing instead of MySQL `YEAR()` and `MONTH()`.
- Admin and student profile tables now enforce the intended one-to-one
  relationship with `users` through unique `user_id` constraints.
- Frequently used numeric and date fields now have explicit Eloquent casts on
  the related models.
- Purchase and chat lookup paths now have supporting indexes for product and
  student/type filters.
- User, student, order, payment transaction, and invoice-adjacent relationships
  are represented explicitly in Eloquent models.
- Student reviews now reflect the intended one-review-per-student domain rule
  through a `hasOne` model relationship and a unique `reviews.student_id`
  constraint.
- User roles/statuses, invoice sources, and payment statuses now use
  application enums at write/check sites while preserving string storage.
- Teaching catalog records now enforce uniqueness for theme-area names,
  subject names inside a theme area, course names inside a subject, lesson
  numbers inside a course, and exercise titles inside a course.
- Admin teaching forms now validate those uniqueness rules before database
  writes, including safe update rules that ignore the current record.
- Profile, review, payment-completion, invoice, purchase lookup, and chat
  lookup constraints were folded into the base migrations instead of being kept
  as transition-only additive migrations.
- Final invoice columns, invoice indexes, and payment transaction linkage were
  folded into the base invoices migration; legacy invoice-sheet backfill
  migrations were removed from the fresh schema path.
- Cashier customer columns were folded into the base users migration, and
  unused subscription tables were removed because the application only handles
  one-off payments.
- The invoice sequence table now has a clean creation migration next to the
  invoice schema, without legacy backfill logic.
- Required catalog, lesson-request, and certificate fields are no longer
  nullable in the base schema.
- Required invoice fields are no longer nullable in the base schema while
  preserving nullable ownership links for direct/manual invoice variants.

Verification so far:

- `php artisan test` - 133 passed, 654 assertions.
- `vendor\bin\pint --test`

### Package C. Seeder split and demo installation

Branch: `refactor/demo-seeders`

Status: completed.

Definition of Done:

- Split `DatabaseSeeder` into focused seeders for users, catalog content,
  purchases, invoices, chats, reviews, and documents.
- Ensure `.env.example` documents usable demo credentials.
- Ensure a fresh install can seed enough realistic data to inspect dashboards,
  orders, invoices, lessons, exercises, chats, and direct requests.
- Run a fresh seeded database verification.

Completed so far:

- The monolithic `DatabaseSeeder` was split into focused seeders for demo
  users, catalog content, direct lesson requests, reviews, orders/invoices, and
  chats.
- Demo PDF generation is shared through one seeder concern.
- Fresh install verification passes with SQLite `:memory:` using
  `php artisan migrate:fresh --seed --force`.
- Demo seeding has feature coverage for generated users, catalog content,
  orders, invoices, chats, reviews, and private demo PDFs.
- `php artisan test` now passes with 134 tests and 672 assertions.

### Package D. Public UX and list pagination

Branch: `feature/public-ux-pagination`

Status: in progress.

Estimated commits: 4-5.

Definition of Done:

- Add restrained scroll-based animation to the homepage without harming
  readability, accessibility, or mobile performance.
- Improve the public about page so it presents profile/context content and uses
  certificates as supporting proof instead of only listing documents.
- Add pagination to long public, student, and admin lists where unbounded
  tables/cards can grow: orders, invoices, chats, lesson requests, catalog or
  teaching management lists where appropriate.
- Preserve existing filters and AJAX behavior when adding pagination.
- Add or update feature tests for paginated list rendering and ownership
  constraints.
- Verify the homepage and about page at desktop and mobile widths.

Completed so far:

- Admin lesson-request lists, student direct-request lists, purchased
  direct-request lists, and student invoice lists use paginated queries instead
  of unbounded collections.
- Admin invoice and chat lists use paginated queries instead of unbounded
  collections.
- Shared lesson-request tables render paginator links when the data source is
  paginated.
- Pagination links use the Bootstrap 5 renderer globally, matching the
  frontend stack used by the project.
- Feature tests cover first-page rendering, hidden overflow rows, and paginator
  links for lesson-request, invoice, and chat lists.
- The public home page has progressive scroll reveal animations that keep
  content visible without JavaScript and respect reduced-motion preferences.
- The public about page now presents profile, method, certificate counts, and
  certificate previews as a structured public page instead of only listing
  documents.
- Newly added Italian public-page strings were checked for mojibake sequences.

### Package E. Final safety pass

Branch: `refactor/final-safety-pass`

Estimated commits: 2-4.

Definition of Done:

- Split oversized controllers where they still mix public, admin, chat, upload,
  pricing, and email responsibilities. Start with the lesson-request and admin
  chat flow.
- Normalize route parameter naming and route model binding where the same
  domain entity is currently addressed with mixed `{id}` and model parameters.
- Replace risky nullable `find()` paths with `findOrFail()` or explicit
  validation/404 handling.
- Tighten remaining numeric price validation with minimum values and consistent
  attribute labels.
- Review static services such as purchase helpers and decide whether to keep
  them as lightweight support classes or convert them to injectable services.
- Add focused tests for each behavior-preserving split or safety fix.

### Package F. Release verification

Branch: `release/final-verification`

Estimated commits: 1-2.

Definition of Done:

- Add or document CI commands for tests, Pint, and Composer audit.
- Run Composer audit, Pint, and the full suite.
- Perform seeded browser verification of the main public, student, admin,
  checkout, upload, chat, invoice, pagination, homepage, about, and
  localization flows.
- Update README/setup documentation with installation, seeding, demo accounts,
  and verification commands.

### Closed packages

- Payment and HTTP safety.
- Authorization.
- File handling.
- Billing consolidation and invoice numbering.
- Query/controller cleanup.
- Automated tests and factories.
- Visual consistency for audited pages.
- Localization for public, student, admin, mail, PDF, and legal surfaces.
- Internal cleanup closure.

### Next action

Finish Package D with targeted browser verification for homepage, about page,
and paginated lists. Then start Package E on `refactor/final-safety-pass`.

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
