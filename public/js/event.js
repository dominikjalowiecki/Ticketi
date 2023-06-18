import { ALERT_TYPE, appendAlertMessage, convertUTCToLocal } from "./helper.js";
import {
    ajaxPostForm,
    ajaxPostFormCustom,
    ajaxPostFormDataCustom,
} from "./ajax.js";
import { tooltipList } from "./scripts.js";

document.addEventListener("DOMContentLoaded", function () {
    // Update URL to match event
    const eventLink = document.querySelector("#eventLink");
    if (history.replaceState) history.replaceState({}, "", eventLink.href);

    // Handle add comment form
    const commentForm = document.querySelector("#commentForm");
    const commentTextarea = document.querySelector("#commentTextarea");
    if (!!commentForm) {
        commentForm.addEventListener("submit", function (e) {
            const target = e.target;

            ajaxPostForm(target, target.action, "#commentsContainer", true);

            commentTextarea.value = "";
        });
    }

    // Handle add like and delete comment button
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const commentsContainer = document.querySelector("#commentsContainer");
    commentsContainer.addEventListener("click", function (e) {
        const target = e.target;

        if (target.classList.contains("remove-comment-btn")) {
            if (!confirm("Are you sure you want to delete this comment?"))
                return;

            const idComment = target.dataset.idComment;
            const action = target.dataset.action;

            const formData = new FormData();
            formData.append("idComment", idComment);
            formData.append("_token", csrfToken);
            formData.append("_method", "DELETE");

            ajaxPostFormDataCustom(formData, action, () => {
                // From scripts.js
                tooltipList.forEach((el) => {
                    el.hide();
                });

                target.closest(".feature-card").classList.add("deleted");
                setTimeout(() => {
                    target.closest(".feature-card").remove();
                }, 500);
            });

            return;
        }

        const likeButton = target.closest(".like-comment-btn");
        if (!!likeButton) {
            const idComment = likeButton.dataset.idComment;
            const action = likeButton.dataset.action;

            const formData = new FormData();
            formData.append("idComment", idComment);
            formData.append("_token", csrfToken);

            ajaxPostFormDataCustom(formData, action, async (response) => {
                const responseText = await response.text();

                // From scripts.js
                tooltipList.forEach((el) => {
                    el.hide();
                });

                likeButton.disabled = true;
                const likeCount = target
                    .closest(".feature-card")
                    .querySelector(".comment-likes-count");

                likeCount.textContent = responseText;
            });
        }
    });

    // Handle load comments button
    const loadCommentsButton = document.querySelector("#loadCommentsBtn");
    const commentsSpinner = document.querySelector("#commentsSpinner");
    let page = 2;
    if (!!loadCommentsButton) {
        loadCommentsButton.addEventListener("click", function (e) {
            const target = e.target;
            target.disabled = true;
            commentsSpinner.classList.remove("d-none");

            const idEvent = loadCommentsButton.dataset.idEvent;
            const action = loadCommentsButton.dataset.action;

            const formData = new FormData();
            formData.append("idEvent", idEvent);

            fetch(
                action +
                    "?" +
                    new URLSearchParams({
                        idEvent: idEvent,
                        page: page,
                    })
            )
                .then(async (response) => {
                    commentsSpinner.classList.add("d-none");
                    target.disabled = false;

                    if (!response.ok) throw new Error("Something went wrong");

                    ++page;
                    const text = await response.text();
                    if (text == "") {
                        target.remove();
                        return;
                    }
                    const el = document.createElement("div");
                    el.classList.add("ajax-append");
                    el.innerHTML = text;

                    commentsContainer.appendChild(el);

                    convertUTCToLocal();

                    setTimeout(() => {
                        el.classList.add("entered");
                    }, 500);
                })
                .catch((error) => {
                    appendAlertMessage(error.message, ALERT_TYPE.danger);
                });
        });
    }

    // Handle follow event button
    const followEventForm = document.querySelector("#followEventForm");
    const followEventButton = document.querySelector("#followEventButton");
    if (!!followEventForm) {
        followEventForm.addEventListener("submit", function (e) {
            const target = e.target;

            ajaxPostFormCustom(target, target.action, (response) => {
                const icon = followEventButton.querySelector(".bi");
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
        });
    }

    // Handle like event button
    const likeEventForm = document.querySelector("#likeEventForm");
    const likeEventButton = document.querySelector("#likeEventButton");
    if (!!likeEventForm) {
        likeEventForm.addEventListener("submit", function (e) {
            const target = e.target;

            ajaxPostFormCustom(target, target.action, async (response) => {
                const responseText = await response.text();

                // From scripts.js
                tooltipList.forEach((el) => {
                    el.hide();
                });

                likeEventButton.disabled = true;
                const likeCount = document.querySelector("#likesCount");
                likeCount.textContent = responseText;
            });
        });
    }
});
