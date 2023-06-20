import { setValid, setInvalid } from "./helper.js";

document.addEventListener("DOMContentLoaded", function () {
    const startDatetimeInput = document.querySelector("#startDatetimeInput");
    const startDatetimeContentInput = document.querySelector(
        "#startDatetimeContentInput"
    );
    if (!!startDatetimeContentInput.value) {
        const date = new Date(startDatetimeContentInput.value);
        startDatetimeInput.value = new Date(
            date.getTime() - date.getTimezoneOffset() * 60000
        )
            .toISOString()
            .slice(0, 16);
    }

    startDatetimeInput.addEventListener("change", function (e) {
        const target = e.target;

        // Check if start datetime is not in past
        const datetime = target.value;
        const date = new Date(datetime);
        const time = date.getTime();
        const currentTime = new Date().getTime();

        if (time <= currentTime) {
            setInvalid(target);
        } else {
            setValid(target);

            // Conversion of startDatetimeInput value to UTC
            const startUTCDatetime = date.toISOString().slice(0, 16) + "Z";
            startDatetimeContentInput.value = startUTCDatetime;
        }
    });

    // Initialize quill rich textarea
    const quill = new Quill("#descriptionInput", {
        modules: {
            toolbar: [
                ["bold", "italic"],
                ["blockquote", "code-block"],
                [{ list: "ordered" }, { list: "bullet" }],
            ],
        },
        placeholder: "Compose an epic...",
        theme: "snow",
    });

    // Limit characters in quill rich textarea
    const characterLimit = 400;
    quill.on("text-change", function (delta, old, source) {
        if (quill.getLength() > characterLimit) {
            quill.deleteText(characterLimit, quill.getLength());
        }
    });

    const createEventForm = document.querySelector("#createEventForm");
    const descriptionContentInput = document.querySelector(
        "#descriptionContentInput"
    );

    createEventForm.addEventListener("submit", function (e) {
        // const target = e.target;

        // Handle quill rich textarea input
        // const descriptionContent = {};
        // descriptionContent.value = JSON.stringify(quill.getContents());
        // descriptionContent.html = JSON.stringify(quill.root.innerHTML);

        descriptionContentInput.value = quill.root.innerHTML;
    });

    // Handling tags input
    const tagsInput = document.querySelector("#tagsInput");
    const tagsContentInput = document.querySelector("#tagsContentInput");
    const tagsList = document.querySelector("#tagsList");
    const tags = JSON.parse(tagsContentInput.value);
    const KEY_CODES = {
        ENTER: 13,
        SPACE: 32,
    };

    for (const tag of tags) {
        const tagBadge = document.createElement("span");
        tagBadge.classList.add("badge", "bg-secondary", "tag-badge");
        tagBadge.innerHTML =
            tag + '<i class="bi bi-x tag-delete" aria-label="Delete tag"></i>';
        tagsList.appendChild(tagBadge);
    }

    tagsInput.addEventListener("keydown", function (e) {
        const target = e.target;
        const keyCode = e.keyCode;

        if (keyCode === KEY_CODES.ENTER || keyCode == KEY_CODES.SPACE) {
            e.preventDefault();
            const tagValue = target.value.trim().toLowerCase();

            if (tagValue != "" && !tags.includes(tagValue)) {
                tags.push(tagValue);
                const tagBadge = document.createElement("span");
                tagBadge.classList.add("badge", "bg-secondary", "tag-badge");
                tagBadge.innerHTML =
                    tagValue +
                    '<i class="bi bi-x tag-delete" aria-label="Delete tag"></i>';
                tagsList.appendChild(tagBadge);
            }
            target.value = "";
        }

        tagsContentInput.value = JSON.stringify(tags);
    });

    // Handle deleting of tags
    tagsList.addEventListener("click", function (e) {
        const target = e.target;

        if (target.classList.contains("tag-delete") && target.tagName === "I") {
            const index = tags.indexOf(target.parentNode.textContent);

            if (index !== -1) {
                tags.splice(index, 1);
            }

            target.closest(".tag-badge").remove();

            tagsContentInput.value = JSON.stringify(tags);
        }
    });

    // Handling city input
    const cityInput = document.querySelector("#cityInput");
    const cityDatalist = document.querySelector("#cityDatalist");
    const cityUrl = cityInput.dataset.url;
    let cityDatalistTimeout;

    cityInput.addEventListener("keydown", function (e) {
        const target = e.target;

        clearTimeout(cityDatalistTimeout);

        if (target.value.length < 3) return;

        cityDatalistTimeout = setTimeout(async () => {
            let res;

            await fetch(
                cityUrl +
                    "?" +
                    new URLSearchParams({
                        s: e.target.value,
                    })
            )
                .then(async (response) => {
                    if (!response.ok) throw new Error("Something went wrong");

                    res = await response.json();
                })
                .catch((error) => {
                    appendAlertMessage(error.message, ALERT_TYPE.danger);
                });

            cityDatalist.innerHTML = "";
            const datalistFragment = document.createDocumentFragment();
            res.forEach((el) => {
                let option = document.createElement("option");
                option.value = el;

                datalistFragment.appendChild(option);
            });

            cityDatalist.appendChild(datalistFragment);
        }, 1000);
    });

    // Block if city doesn't exist
    cityInput.addEventListener("change", function (e) {
        const target = e.target;

        if (target.value == "") return;

        const options = target.list.options;
        for (const option of options) {
            if (target.value === option.value) return;
        }

        target.value = "";
    });

    // Handle images input
    const imagesUploadInput = document.querySelector("#imagesUploadInput");
    const imagesThumbnailsContainer = document.querySelector(
        "#imagesThumbnailsContainer"
    );

    imagesUploadInput.addEventListener("change", function (e) {
        const target = e.target;

        imagesThumbnailsContainer.innerHTML = "";

        const imageMaxSize = 1048576; // 1MB

        if (target.files.length > 4) {
            alert("Max 4 images for upload!");
            target.value = "";

            e.preventDefault();
            return;
        }

        const allowedMIMETypes = [
            "image/jpeg",
            "image/jpg",
            "image/png",
            "image/gif",
        ];

        for (const file of target.files) {
            if (file.size > imageMaxSize) {
                setInvalid(target);

                target.value = "";

                e.preventDefault();
                return;
            } else if (!allowedMIMETypes.includes(file.type)) {
                setInvalid(target);

                target.value = "";

                e.preventDefault();
                return;
            } else {
                setValid(target);
            }
        }

        for (const file of target.files) {
            const imageThumbnail = document.createElement("img");
            imageThumbnail.classList.add(
                "img-thumbnail",
                "event-image-thumbnail",
                "me-2",
                "mb-3"
            );

            imageThumbnail.src = URL.createObjectURL(file);
            imageThumbnail.onload = () => {
                URL.revokeObjectURL(imageThumbnail.src);
            };

            imagesThumbnailsContainer.appendChild(imageThumbnail);
        }
    });

    // Handle video input
    const videoUploadInput = document.querySelector("#videoUploadInput");
    videoUploadInput.addEventListener("change", function (e) {
        const target = e.target;

        const videoMaxSize = 10485760; // 10MB

        const allowedMIMETypes = ["video/mp4", "video/mov"];

        if (target.files[0].size > videoMaxSize) {
            setInvalid(target);
            target.value = "";

            e.preventDefault();
            return;
        } else if (!allowedMIMETypes.includes(target.files[0].type)) {
            setInvalid(target);

            target.value = "";

            e.preventDefault();
            return;
        } else {
            setValid(target);
        }
    });
});
