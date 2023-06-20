import { ALERT_TYPE, appendAlertMessage, convertUTCToLocal } from "./helper.js";

export function ajaxPostForm(
    form,
    url,
    idAppendContainer,
    appendBefore = false
) {
    const formData = new FormData(form);

    fetch(url, {
        method: "POST",
        body: formData,
    })
        .then(async (response) => {
            if (!response.ok) throw new Error("Something went wrong");

            const text = await response.text();
            const appendContainer = document.querySelector(idAppendContainer);
            const el = document.createElement("div");
            el.classList.add("ajax-append");
            el.innerHTML = text;

            if (appendBefore)
                appendContainer.insertBefore(el, appendContainer.firstChild);
            else appendContainer.appendChild(el);

            convertUTCToLocal();

            setTimeout(() => {
                el.classList.add("entered");
            }, 500);
        })
        .catch((error) => {
            appendAlertMessage(error.message, ALERT_TYPE.danger);
        });
}

export function ajaxPostFormCustom(
    form,
    url,
    customCallback = () => {},
    method = "POST"
) {
    const formData = new FormData(form);

    fetch(url, {
        method: method,
        body: formData,
    })
        .then(async (response) => {
            const responseClose = response.clone();
            const message = await response.text();
            if (!response.ok) throw new Error("Something went wrong");

            customCallback(responseClose);
        })
        .catch((error) => {
            appendAlertMessage(error.message, ALERT_TYPE.danger);
        });
}

export function ajaxPostFormDataCustom(
    formData,
    url,
    customCallback = () => {},
    method = "POST"
) {
    fetch(url, {
        method: method,
        body: formData,
    })
        .then(async (response) => {
            const responseClose = response.clone();
            const message = await response.text();
            if (!response.ok) throw new Error("Something went wrong");

            customCallback(responseClose);
        })
        .catch((error) => {
            appendAlertMessage(error.message, ALERT_TYPE.danger);
        });
}

// function redirectConfirm(text, url) {
//     if (confirm(text)) window.location.assign(url);
//   }
