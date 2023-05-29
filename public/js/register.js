import { setValid, setInvalid } from "./helper.js";

document.addEventListener("DOMContentLoaded", function () {
    // Check if birthdate is not in future
    const birthdateInput = document.querySelector("#birthdateInput");

    birthdateInput.addEventListener("change", function (e) {
        const target = e.target;

        const date = target.value;
        const time = new Date(date).getTime();
        const currentTime = new Date().getTime();

        if (time >= currentTime) {
            setInvalid(target);
        } else {
            setValid(target);
        }
    });
});
