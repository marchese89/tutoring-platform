# Release Checklist

Run this checklist against a fresh seeded database before merging the final
refactoring branch.

## Automated Verification

```bash
composer validate --strict
vendor/bin/pint --test
php artisan test
composer audit --locked --no-interaction
```

All commands must finish successfully.

## Demo Setup

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

Use the demo accounts documented in `README.md`.

## Public Area

- Open the homepage and verify responsive layout, animations, reviews, and
  language switching.
- Open the about page and verify certificate previews.
- Browse topic areas, subjects, courses, free lessons, and free exercises.
- Add a paid item to the cart and verify cart and checkout layout.
- Open the custom lesson request form and verify PDF upload validation and
  progress feedback.

## Student Area

- Sign in as each seeded student and verify dashboard, purchased courses,
  orders, invoices, direct requests, profile, credentials, and review pages.
- Open purchased lesson and exercise files and confirm unauthorized files
  remain inaccessible to the other student.
- Verify pagination on orders, invoices, requests, and other populated lists.
- With Reverb running, exchange chat messages and confirm both message content
  and unread counters update without refreshing.

## Admin Area

- Sign in as the seeded admin and verify teaching management, students,
  lesson requests, chats, sales, orders, invoices, and account settings.
- Upload and replace a profile photo, certificate, lesson PDF, exercise PDF,
  and lesson-request solution.
- Confirm validation messages use readable localized field names.
- Verify pagination on chats, invoices, lesson requests, and other populated
  lists.

## Payments

- Use Stripe test credentials only.
- Verify cart and extra-payment flows create records only after a successful
  server-verified PaymentIntent.
- Confirm refreshing a completed callback does not duplicate orders or
  invoices.
