// var source = new EventSource('.php');
// source.onmessage = function (event) {
//   document.getElementById('result').innerHTML += event.data + '<br>';
// };

// Disabling form submissions if there are invalid fields
(function () {
    "use strict";

    const forms = document.querySelectorAll(".needs-validation");

    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener(
            "submit",
            function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add("was-validated");
            },
            false
        );
    });
})();

// ====================
// Enabling cart popover
const myDefaultAllowList = {
    ...bootstrap.Tooltip.Default.allowList,
    table: [],
    thead: [],
    tbody: [],
    tfoot: [],
    th: [],
    tr: [],
    td: [],
};

const popoverElement = document.querySelector("#cart-popover");
if (!!popoverElement) {
    const popoverContent = document.querySelector("#cart-popover-content");
    const popover = new bootstrap.Popover(popoverElement, {
        allowList: myDefaultAllowList,
        html: true,
        title: "Shopping cart",
        placement: "bottom",
        container: "body",
        trigger: "hover click",
        content: function () {
            return popoverContent.innerHTML;
        },
    });

    // Defining popover disable logic
    const popupMediaQuery = window.matchMedia("screen and (max-width: 992px)");

    function handleMobileChange(mediaQuery) {
        if (mediaQuery.matches) popover.disable();
        else popover.enable();
    }

    popupMediaQuery.addEventListener("change", (e) => {
        handleMobileChange(e);
    });

    // Initial check
    handleMobileChange(popupMediaQuery);
}

// ==================
// Enabling tooltips everywhere
var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
);
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

// ==================
// Converting time from UTC to local time
const timeComponents = document.querySelectorAll(".time-component");
for (const timeComponent of timeComponents) {
    const date = new Date(timeComponent.textContent);
    timeComponent.textContent = date.toLocaleString();
}
