import { setValid, setInvalid } from "./helper.js";

document.addEventListener("DOMContentLoaded", function () {
    // Check if passwords are equal
    const passwordInput = document.querySelector("#passwordInput");
    const confirmPasswordInput = document.querySelector(
        "#confirmPasswordInput"
    );

    passwordInput.addEventListener("input", function () {
        checkPasswordsEquality();
    });
    confirmPasswordInput.addEventListener("input", function () {
        checkPasswordsEquality();
    });

    function checkPasswordsEquality() {
        if (passwordInput.value !== confirmPasswordInput.value) {
            setInvalid(confirmPasswordInput);
        } else {
            setValid(confirmPasswordInput);
        }
    }
});
