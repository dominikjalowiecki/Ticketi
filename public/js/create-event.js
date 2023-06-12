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
        // console.log(quill.getLength());
        // console.log(quill.getContents));
        // console.group("Test");
        // console.log(quill.root.innerHTML.length);
        // console.log(quill.root.innerHTML);
        // console.groupEnd();

        if (quill.getLength() > characterLimit) {
            quill.deleteText(characterLimit, quill.getLength());
        }

        // if (quill.root.innerHTML.length > characterLimit) {
        //     quill.deleteText(characterLimit, quill.root.innerHTML.length);
        //     quill.root.innerHTML = quill.root.innerHTML.substring(
        //         0,
        //         characterLimit
        //     );
        // }
    });

    const createEventForm = document.querySelector("#createEventForm");
    const descriptionContentInput = document.querySelector(
        "#descriptionContentInput"
    );

    createEventForm.addEventListener("submit", function (e) {
        const target = e.target;

        // Handle quill rich textarea input
        const descriptionContent = {};
        descriptionContent.value = JSON.stringify(quill.getContents());
        descriptionContent.html = JSON.stringify(quill.root.innerHTML);

        console.log(descriptionContent);
        descriptionContentInput.value = descriptionContent.html;
        e.preventDefault();
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
                    '<i class="bi bi-x tag-delete" aria-hidden="true"></i><span class="visually-hidden">Delete tag</span></span>';
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
        }

        e.stopPropagation();
        // e.stopImmediatePropagation();
        e.preventDefault();
    });

    // Handling city input
    const cityInput = document.querySelector("#cityInput");
    const cityDatalist = document.querySelector("#cityDatalist");
    let cityDatalistTimeout;

    cityInput.addEventListener("input", function (e) {
        const target = e.target;

        clearTimeout(cityDatalistTimeout);

        if (target.value.length < 3) return;

        cityDatalistTimeout = setTimeout(() => {
            let res;

            // fetch(
            // 'https://example.com?' +
            // new URLSearchParams({
            //   s: e.target.value,
            // }))
            //   .then((res) => {
            //     if (!res.ok) throw new Error('Bad response from server');
            //     return res.json();
            //   })
            //   .then((data) => (res = data))
            //   .catch((err) => {});
            res = ["Drożdże", "Dżem", "Bułka"];

            cityDatalist.innerHTML = "";
            const datalistFragment = document.createDocumentFragment();
            res.forEach((el) => {
                let option = document.createElement("option");
                option.value = el;
                option.textContent = el;

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

                // alert('Image size max 1MB!');
                target.value = "";

                e.preventDefault();
                return;
            } else if (!allowedMIMETypes.includes(file.type)) {
                setInvalid(target);

                // alert('Invalid file extension!');
                target.value = "";

                e.preventDefault();
                return;
            } else {
                setValid(target);
            }
        }

        // types = /(\.|\/)(mp3|mp4)$/i;
        // //file is the file, that the user wants to upload
        // file = data.files[0];

        // if (types.test(file.type) || types.test(file.name)) {
        //     alert("file is valid");
        // else{
        //     alert("file is invalid");
        // }

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
