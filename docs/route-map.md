# Route Map

Updated on 2026-05-29 from `php artisan route:list`.

The application routes now use English URLs and dotted route names. Legacy Italian URLs are no longer defined in the route files; internal links should use `route(...)` and the route names below.

## Public Site

| Method | URI | Name | Handler |
| --- | --- | --- | --- |
| GET | `/` | `home` | `HomeController@index` |
| GET | `/about` | `about` | view |
| GET | `/privacy-policy` | `privacy-policy` | view |
| GET | `/cookie-policy` | `cookie-policy` | view |
| GET | `/registration/success` | `registration.success` | view |
| GET | `/registration/error` | `registration.error` | view |
| GET | `/theme-areas` | `theme-areas.index` | `ThemeAreaController@publicIndex` |
| GET | `/theme-areas/{id_at}/subjects` | `subjects.index` | `MatterController@publicIndex` |
| GET | `/subjects/{id_materia}/courses` | `courses.index` | `CourseController@publicIndex` |
| GET | `/courses/{id}` | `courses.show` | `CourseController@show` |
| GET | `/courses/{id_corso}/lessons/{id_lezione}` | `lessons.show` | `LessonController@view` |
| GET | `/courses/{id_corso}/lessons/{id_lezione}/presentation` | `lessons.presentation` | `LessonController@viewPresentation` |
| GET | `/courses/{id_corso}/exercises/{id_esercizio}/trace` | `exercises.trace` | `ExerciseController@viewTrace` |
| GET | `/lesson-requests/create` | `lesson-requests.create` | view |
| POST | `/lesson-requests/files` | `lesson-requests.files.store` | `LessonOnRequestController@add_file_su_richiesta` |
| DELETE | `/lesson-requests/files` | `lesson-requests.files.destroy` | `LessonOnRequestController@elimina_lez_rich` |
| POST | `/lesson-requests` | `lesson-requests.store` | `LessonOnRequestController@carica_lez_rich` |
| GET | `/lesson-requests/success` | `lesson-requests.success` | view |
| GET | `/protected-files/{path}` | `protected-files.show` | `FileAccessController` |

## Auth

| Method | URI | Name | Handler |
| --- | --- | --- | --- |
| GET | `/login` | `login` | view |
| POST | `/login` | none | `LoginController@login` |
| GET | `/logout` | `logout` | `LogoutController@logout` |
| GET | `/register` | `register` | view |
| POST | `/register` | `register.store` | `RegistrazioneController@carica_utente` |
| GET | `/password/forgot` | `password.request` | view |
| POST | `/password/email` | `password.email` | `LoginController@recupera_password` |
| GET | `/password/reset/{token}` | `password.reset` | `PasswordResetController@edit` |
| POST | `/password/reset` | `password.update` | `PasswordResetController@update` |

## Student And Commerce

Middleware: `auth`, `role:student`.

| Method | URI | Name | Handler |
| --- | --- | --- | --- |
| GET | `/student/dashboard` | `student.dashboard` | view |
| GET | `/student/account` | `student.account` | view |
| GET | `/student/account/profile` | `student.account.profile` | view |
| GET | `/student/account/credentials` | `student.account.credentials` | view |
| POST | `/student/account/address` | `student.account.address.update` | `StudenteController@mod_indirizzo_stud` |
| POST | `/student/account/email` | `student.account.email.update` | `StudenteController@mod_email_stud` |
| POST | `/student/account/password` | `student.account.password.update` | `StudenteController@mod_pass_stud` |
| GET | `/student/courses` | `student.courses.index` | `CourseController@mieiCorsi` |
| GET | `/student/courses/{id}` | `student.courses.show` | `RouteController@show` |
| GET | `/student/courses/{id_corso}/lessons/{id_lezione}` | `student.lessons.show` | `StudenteController@lezione` |
| GET | `/student/courses/{id_corso}/exercises/{id_esercizio}` | `student.exercises.show` | `StudenteController@esercizio` |
| GET | `/cart` | `cart.show` | view |
| GET | `/cart/items/{id}/{type}` | `cart.items.store` | `AcquistiController@aggiungi_al_carrello` |
| DELETE | `/cart/items/{id}/{type}` | `cart.items.destroy` | `AcquistiController@rimuovi_dal_carrello` |
| GET | `/checkout` | `checkout.show` | view |
| POST | `/checkout/payment` | `checkout.payment.prepare` | `AcquistiController@prepara_pagamento` |
| POST | `/payment/process` | `payment.process` | `AcquistiController@process_payment` |
| GET | `/payment/process` | `payment.process.legacy` | `AcquistiController@processa_pagamento_individuale` |
| GET | `/payment/success` | `payment.success` | `AcquistiController@processa_acquisto` |
| GET | `/payment/complete` | `payment.complete` | view |
| GET | `/payment/pay` | `payment.pay` | view |
| GET | `/payment/ok` | `payment.ok` | view |
| GET | `/payment/extra` | `payment.extra` | view |
| GET | `/student/orders` | `student.orders.index` | view |
| GET | `/student/orders-table` | `student.orders.table` | `AjaxController@getOrdini` |
| GET | `/student/orders/{id}` | `student.orders.show` | view |
| GET | `/student/invoices` | `student.invoices.index` | view |
| GET | `/student/invoices/{id}` | `student.invoices.show` | view |
| GET | `/student/invoice-sheets/{id}` | `student.invoice-sheets.show` | view |
| GET | `/student/direct-requests` | `student.direct-requests.index` | view |
| GET | `/student/direct-requests/purchased` | `student.direct-requests.purchased` | view |
| GET | `/student/direct-requests/{id}` | `student.direct-requests.show` | `StudenteController@showDirectRequest` |
| POST | `/student/chat/messages` | `student.chat.messages.store` | `AjaxController@invia_messaggio` |
| GET | `/student/review` | `student.review` | view |
| POST | `/student/review` | `student.review.store` | `AjaxController@invia_recensione` |
| POST | `/student/feedback` | `student.feedback.store` | `AjaxController@invia_feedback` |

## Admin

Middleware: `auth`, `role:admin`.

| Method | URI | Name | Handler |
| --- | --- | --- | --- |
| GET | `/admin/dashboard` | `admin.dashboard` | view |
| GET | `/admin/account` | `admin.account` | view |
| GET | `/admin/account/profile` | `admin.account.profile` | view |
| GET | `/admin/account/credentials` | `admin.account.credentials` | view |
| POST | `/admin/account/email` | `admin.account.email.update` | `ModDatiAdminController@mod_email_admin` |
| POST | `/admin/account/password` | `admin.account.password.update` | `ModDatiAdminController@mod_pass_admin` |
| GET | `/admin/account/photo` | `admin.account.photo` | view |
| POST | `/admin/account/photo` | `admin.account.photo.update` | `ModDatiAdminController@upload_foto` |
| GET | `/admin/account/address` | `admin.account.address` | view |
| POST | `/admin/account/address` | `admin.account.address.update` | `ModDatiAdminController@mod_ind` |
| GET | `/admin/account/vat-number` | `admin.account.vat-number` | view |
| POST | `/admin/account/vat-number` | `admin.account.vat-number.update` | `ModDatiAdminController@mod_piva` |
| GET | `/admin/account/certificates` | `admin.account.certificates.index` | view |
| POST | `/admin/account/certificates` | `admin.account.certificates.store` | `ModDatiAdminController@add_cert_admin` |
| DELETE | `/admin/account/certificates` | `admin.account.certificates.destroy` | `ModDatiAdminController@elimina_cert` |
| GET | `/admin/account/certificates/create` | `admin.account.certificates.create` | view |
| POST | `/admin/account/certificates/uploads` | `admin.account.certificates.uploads.store` | `ModDatiAdminController@upload_cert_session` |
| DELETE | `/admin/account/certificates/uploads` | `admin.account.certificates.uploads.destroy` | `ModDatiAdminController@elimina_cert_session` |
| POST | `/admin/account/certificates/name` | `admin.account.certificates.name.update` | `ModDatiAdminController@modifica_nome_cert` |
| POST | `/admin/account/certificates/photo` | `admin.account.certificates.photo.update` | `ModDatiAdminController@upload_cert` |
| GET | `/admin/teaching` | `admin.teaching.index` | view |
| GET | `/admin/theme-areas` | `admin.theme-areas.index` | `ThemeAreaController@index` |
| POST | `/admin/theme-areas` | `admin.theme-areas.store` | `ThemeAreaController@store` |
| PUT | `/admin/theme-areas/{id}` | `admin.theme-areas.update` | `ThemeAreaController@update` |
| DELETE | `/admin/theme-areas/{id}` | `admin.theme-areas.destroy` | `ThemeAreaController@destroy` |
| GET | `/admin/subjects` | `admin.subjects.index` | `MatterController@index` |
| POST | `/admin/subjects` | `admin.subjects.store` | `MatterController@store` |
| PUT | `/admin/subjects/{id}` | `admin.subjects.update` | `MatterController@update` |
| DELETE | `/admin/subjects/{id}` | `admin.subjects.destroy` | `MatterController@destroy` |
| GET | `/admin/courses` | `admin.courses.index` | `CourseController@list` |
| GET | `/admin/courses/create` | `admin.courses.create` | `CourseController@index` |
| POST | `/admin/courses` | `admin.courses.store` | `CourseController@store` |
| GET | `/admin/courses/{id}/edit` | `admin.courses.edit` | `CourseController@edit` |
| PUT | `/admin/courses/{id}` | `admin.courses.update` | `CourseController@update` |
| DELETE | `/admin/courses/{id}` | `admin.courses.destroy` | `CourseController@destroy` |
| GET | `/admin/courses/{id}/lessons/create` | `admin.lessons.create` | `LessonController@create` |
| GET | `/admin/courses/{id_corso}/lessons/{id_lezione}/edit` | `admin.lessons.edit` | view |
| POST | `/admin/lessons` | `admin.lessons.store` | `LessonController@store` |
| PUT | `/admin/lessons/{id}` | `admin.lessons.update` | `LessonController@update` |
| DELETE | `/admin/lessons/{id}` | `admin.lessons.destroy` | `LessonController@destroy` |
| POST | `/admin/lessons/upload-presentation` | `admin.lessons.upload-presentation.store` | `LessonController@uploadPresentation` |
| DELETE | `/admin/lessons/upload-presentation` | `admin.lessons.upload-presentation.destroy` | `LessonController@deletePresentationSession` |
| POST | `/admin/lessons/upload-file` | `admin.lessons.upload-file.store` | `LessonController@uploadLessonFile` |
| DELETE | `/admin/lessons/upload-file` | `admin.lessons.upload-file.destroy` | `LessonController@deleteLessonSession` |
| POST | `/admin/lessons/{id}/presentation` | `admin.lessons.presentation.update` | `LessonController@updatePresentation` |
| POST | `/admin/lessons/{id}/file` | `admin.lessons.file.update` | `LessonController@updateLessonFile` |
| GET | `/admin/courses/{course}/exercises/create` | `admin.exercises.create` | view |
| GET | `/admin/courses/{course}/exercises/{exercise}/edit` | `admin.exercises.edit` | view |
| POST | `/admin/exercises` | `admin.exercises.store` | `ExerciseController@store` |
| PUT | `/admin/exercises/{id}` | `admin.exercises.update` | `ExerciseController@update` |
| DELETE | `/admin/exercises/{id}` | `admin.exercises.destroy` | `ExerciseController@destroy` |
| POST | `/admin/exercises/trace/upload` | `admin.exercises.trace.upload.store` | `ExerciseController@uploadTrace` |
| DELETE | `/admin/exercises/trace/session` | `admin.exercises.trace.session.destroy` | `ExerciseController@clearTraceSession` |
| POST | `/admin/exercises/execution/upload` | `admin.exercises.execution.upload.store` | `ExerciseController@uploadExecution` |
| DELETE | `/admin/exercises/execution/session` | `admin.exercises.execution.session.destroy` | `ExerciseController@clearExecutionSession` |
| POST | `/admin/exercises/{id}/trace` | `admin.exercises.trace.update` | `ExerciseController@updateTrace` |
| POST | `/admin/exercises/{id}/execution` | `admin.exercises.execution.update` | `ExerciseController@updateExecution` |
| GET | `/admin/students` | `admin.students.index` | view |
| GET | `/admin/lesson-requests` | `admin.lesson-requests.index` | `LessonOnRequestController@index` |
| GET | `/admin/lesson-requests/{id}` | `admin.lesson-requests.show` | `LessonOnRequestController@visualizzaRichiesta` |
| POST | `/admin/lesson-requests/{id}/solution` | `admin.lesson-requests.solution.store` | `LessonOnRequestController@sol_rich_upload` |
| DELETE | `/admin/lesson-requests/{id}/solution` | `admin.lesson-requests.solution.destroy` | `LessonOnRequestController@lez_rich_rem_exec` |
| POST | `/admin/lesson-requests/{id}/price` | `admin.lesson-requests.price.store` | `LessonOnRequestController@carica_prezzo_lez_rich` |
| GET | `/admin/chats` | `admin.chats.index` | `LessonOnRequestController@chatStudenti` |
| GET | `/admin/chats/{id}` | `admin.chats.show` | `LessonOnRequestController@visualizzaChat` |
| GET | `/admin/chats/{id_chat}/messages` | `admin.chats.messages.index` | `AjaxController@leggi_messaggi` |
| POST | `/admin/chat/messages` | `admin.chat.messages.store` | `AjaxController@invia_messaggio` |
| GET | `/admin/sales` | `admin.sales.index` | `BillingController@vendite` |
| GET | `/admin/orders-table` | `admin.orders.table` | `BillingController@cambiaTabellaOrdini` |
| GET | `/admin/orders/{id}` | `admin.orders.show` | `BillingController@showOrder` |
| GET | `/admin/orders/{id}/invoice` | `admin.orders.invoice` | `BillingController@showInvoice` |
| GET | `/admin/invoices` | `admin.invoices.index` | `InvoiceController@showAll` |
| GET | `/admin/invoices/extra` | `admin.invoices.extra` | view |
| POST | `/admin/invoices/extra` | `admin.invoices.extra.store` | `AcquistiController@crea_fattura` |
| GET | `/admin/invoices/created` | `admin.invoices.created` | view |
| GET | `/admin/invoices/{id}` | `admin.invoices.show` | `InvoiceController@show` |

## Framework And Package Routes

These routes are registered by Laravel packages or the default API file, not by the site route refactor.

| Method | URI | Name | Source |
| --- | --- | --- | --- |
| GET | `/api/user` | none | default Sanctum API route |
| GET | `/sanctum/csrf-cookie` | `sanctum.csrf-cookie` | Sanctum |
| GET | `/stripe/payment/{id}` | `cashier.payment` | Cashier |
| POST | `/stripe/webhook` | `cashier.webhook` | Cashier |
| GET | `/_ignition/health-check` | `ignition.healthCheck` | Laravel Ignition |
| POST | `/_ignition/execute-solution` | `ignition.executeSolution` | Laravel Ignition |
| POST | `/_ignition/update-config` | `ignition.updateConfig` | Laravel Ignition |
