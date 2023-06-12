import { ajaxPostFormDataCustom } from "./ajax.js";

document.addEventListener("DOMContentLoaded", function () {
    // Manage `Sort` input
    const sortForm = document.querySelector("#sortForm");
    const sortInput = document.querySelector("#sortInput");
    sortInput.addEventListener("change", function (e) {
        const target = e.target;
        const sortName = target.name;
        const sortValue = target.value;
        const action = sortForm.action;

        // let newAction = sortForm.action;

        // if (newAction.indexOf("?") === -1) newAction += "?";
        // else newAction += "&";

        // newAction += new URLSearchParams({
        //     sort: target.value,
        // });

        // sortForm.action = newAction;

        // window.location.replace(newAction);
        // sortForm.submit();

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);

        if (urlParams.has("page")) urlParams.delete("page");

        urlParams.set(sortName, sortValue);
        window.location.replace(action + "?" + urlParams);
    });

    const filtersForm = document.querySelector("#filtersForm");
    filtersForm.addEventListener("submit", function (e) {
        const target = e.target;

        const formData = new FormData(target);
        const params = new URLSearchParams(formData);

        const action = target.action;

        window.location.replace(action + "?" + params);
    });

    // Manage `City` search input - clear if value not in options
    const cityInput = document.querySelector("#cityInput");

    cityInput.addEventListener("change", function (e) {
        const target = e.target;

        if (target.value == "") return;

        const options = target.list.options;
        for (const option of options) {
            if (target.value === option.value) return;
        }

        target.value = "";
    });

    // Handle favourite toggle button
    const eventsContainer = document.querySelector("#eventsContainer");
    eventsContainer.addEventListener("click", function (e) {
        const target = e.target;

        const button = target.closest(".add-favourite-btn");
        if (!!button) {
            const idEvent = button.dataset.idEvent;
            const action = button.dataset.action;
            const csrfToken = document.querySelector(
                'meta[name="csrf-token"]'
            ).content;

            const formData = new FormData();
            formData.append("idEvent", idEvent);
            formData.append("_token", csrfToken);

            ajaxPostFormDataCustom(formData, action, () => {
                const icon = button.querySelector(".bi");
                if (!!icon) {
                    if (icon.classList.contains("bi-star")) {
                        icon.classList.remove("bi-star");
                        icon.classList.add("bi-star-fill");
                    } else if (icon.classList.contains("bi-star-fill")) {
                        icon.classList.remove("bi-star-fill");
                        icon.classList.add("bi-star");
                    }
                }
            });
            // fetch('', {
            //   method: 'POST',
            //   body: formData,
            // })
            //   .then(() => {
            //     const icon = button.querySelector('.bi');
            //     if (!!icon) {
            //       if (icon.classList.contains('bi-star')) {
            //         icon.classList.remove('bi-star');
            //         icon.classList.add('bi-star-fill');
            //       } else if (icon.classList.contains('bi-star-fill')) {
            //         icon.classList.remove('bi-star-fill');
            //         icon.classList.add('bi-star');
            //       }
            //     }
            //   })
            //   .catch((err) => {});
        }
    });
});
