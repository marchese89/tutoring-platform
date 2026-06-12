@props([
    'label' => 'Upload in corso',
])

<div class="upload-progress d-none mb-3" data-upload-progress>
    <div class="d-flex justify-content-between align-items-center small text-muted mb-1">
        <span>{{ $label }}</span>
        <span data-upload-progress-percent>0%</span>
    </div>

    <div class="progress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar progress-bar-striped progress-bar-animated" data-upload-progress-bar style="width: 0%;">
            0%
        </div>
    </div>
</div>

@once
    @push('styles')
        <style>
            .upload-progress .progress {
                height: 1.25rem;
            }
        </style>
    @endpush
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("form[data-upload-progress-form]").forEach(function(form) {
                if (form.dataset.uploadProgressBound === "true") {
                    return;
                }

                form.dataset.uploadProgressBound = "true";

                form.addEventListener("submit", function(event) {
                    const fileInput = form.querySelector('input[type="file"]');

                    if (!form.checkValidity() || !fileInput || fileInput.files.length === 0) {
                        return;
                    }

                    const request = new XMLHttpRequest();

                    if (!request.upload) {
                        return;
                    }

                    const progress = form.querySelector("[data-upload-progress]");
                    const progressBar = form.querySelector("[data-upload-progress-bar]");
                    const progressPercent = form.querySelector("[data-upload-progress-percent]");
                    const submitButton = form.querySelector('[type="submit"]');

                    if (!progress || !progressBar || !progressPercent) {
                        return;
                    }

                    event.preventDefault();

                    progress.classList.remove("d-none");
                    progressBar.style.width = "0%";
                    progressBar.textContent = "0%";
                    progressBar.setAttribute("aria-valuenow", "0");
                    progressPercent.textContent = "0%";

                    if (submitButton) {
                        submitButton.disabled = true;
                    }

                    request.upload.addEventListener("progress", function(uploadEvent) {
                        if (!uploadEvent.lengthComputable) {
                            return;
                        }

                        const percent = Math.round((uploadEvent.loaded / uploadEvent
                            .total) * 100);

                        progressBar.style.width = percent + "%";
                        progressBar.textContent = percent + "%";
                        progressBar.setAttribute("aria-valuenow", String(percent));
                        progressPercent.textContent = percent + "%";
                    });

                    request.addEventListener("load", function() {
                        window.location.href = request.responseURL || form.action;
                    });

                    request.addEventListener("error", function() {
                        progress.classList.add("d-none");

                        if (submitButton) {
                            submitButton.disabled = false;
                        }
                    });

                    request.open(form.method || "POST", form.action);
                    request.send(new FormData(form));
                });
            });
        });
    </script>
@endonce
