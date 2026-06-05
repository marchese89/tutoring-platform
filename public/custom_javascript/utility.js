function toggleNewPassword() {
    const field = document.getElementById("password");

    if (field.type === "password") {
        field.type = "text";
    } else {
        field.type = "password";
    }
}

function togglePasswordConfirmation() {
    const field = document.getElementById("password_confirmation");

    if (field.type === "password") {
        field.type = "text";
    } else {
        field.type = "password";
    }
}

function toggleCurrentPassword() {
    const field = document.getElementById("current_password");

    if (field.type === "password") {
        field.type = "text";
    } else {
        field.type = "password";
    }
}
