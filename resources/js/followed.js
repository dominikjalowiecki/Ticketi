import { ajaxPostFormDataCustom } from "./ajax.js";
import { tooltipList } from "./scripts.js";

document.addEventListener("DOMContentLoaded", function () {
    const entries = performance.getEntriesByType("navigation");
    entries.forEach((entry) => {
        if (entry.type === "back_forward") {
            location.reload();
        }
    });

    // Handle delete favourite button
    const favouritesContainer = document.querySelector("#favouritesContainer");
    favouritesContainer.addEventListener("click", function (e) {
        const target = e.target;

        if (target.classList.contains("remove-favourite-btn")) {
            const idEvent = target.dataset.idEvent;
            const action = target.dataset.action;
            const csrfToken = document.querySelector(
                'meta[name="csrf-token"]'
            ).content;

            const formData = new FormData();
            formData.append("idEvent", idEvent);
            formData.append("_token", csrfToken);

            ajaxPostFormDataCustom(formData, action, () => {
                tooltipList.forEach((el) => {
                    el.hide();
                });

                target.closest(".feature-card").classList.add("deleted");
                setTimeout(() => {
                    target.closest(".feature-card").remove();
                }, 500);
            });
        }
    });
});
