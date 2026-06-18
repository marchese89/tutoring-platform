<?php

return [
    'pdf_max_kilobytes' => (int) env('UPLOAD_PDF_MAX_KB', 51200),
    'image_max_kilobytes' => (int) env('UPLOAD_IMAGE_MAX_KB', 5120),
];
