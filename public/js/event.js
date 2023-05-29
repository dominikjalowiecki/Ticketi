import { ALERT_TYPE, appendAlertMessage } from "./helper.js";
import { ajaxPostForm } from "./ajax.js";

document.addEventListener("DOMContentLoaded", function () {
    // Update URL to match event

    // Handle add comment form
    const commentForm = document.querySelector("#commentForm");
    const commentTextarea = document.querySelector("#commentTextarea");

    commentForm.addEventListener("submit", function (e) {
        const target = e.target;
        e.preventDefault();

        ajaxPostForm(target, "", "#commentsContainer");

        commentTextarea.value = "";
    });

    // Handle add like and delete comment button
    const commentsContainer = document.querySelector("#commentsContainer");
    commentsContainer.addEventListener("click", function (e) {
        const target = e.target;

        if (target.classList.contains("remove-comment-btn")) {
            if (!confirm("Are you sure you want to delete this comment?"))
                return;
            const commentId = target.value;

            const formData = new FormData();
            formData.append("commentId", commentId);

            fetch("", {
                method: "DELETE",
                body: formData,
            })
                .then(() => {
                    // From scripts.js
                    tooltipList.forEach((el) => {
                        el.hide();
                    });

                    target.closest(".feature-card").classList.add("deleted");
                    setTimeout(() => {
                        target.closest(".feature-card").remove();
                    }, 500);
                })
                .catch((err) => {});
            return;
        }

        const likeButton = target.closest(".like-comment-btn");
        if (!!likeButton) {
            const commentId = likeButton.value;

            const formData = new FormData();
            formData.append("commentId", commentId);

            fetch("", {
                method: "POST",
                body: formData,
            })
                .then(() => {
                    // From scripts.js
                    tooltipList.forEach((el) => {
                        el.hide();
                    });

                    const likesCount = target
                        .closest(".feature-card")
                        .querySelector(".likes-counter");

                    ++likesCount.textContent;
                    likeButton.disabled = true;
                })
                .catch((err) => {});
        }
    });

    // Handle load comments button
    const loadCommentsButton = document.querySelector("#loadCommentsBtn");
    const commentsSpinner = document.querySelector("#commentsSpinner");
    let page = 2;
    loadCommentsButton.addEventListener("click", function (e) {
        const target = e.target;
        commentsSpinner.classList.remove("d-none");

        fetch(
            "https://example.com?" +
                new URLSearchParams({
                    p: page,
                })
        )
            .then((res) => {
                if (!res.ok) {
                    target.remove();
                    if (res.status != 404)
                        throw new Error("Bad response from server");
                } else {
                    ++page;
                }
                commentsSpinner.classList.add("d-none");
            })
            .catch((err) => {
                appendAlertMessage(err.message, ALERT_TYPE.danger);
                target.remove();
                commentsSpinner.classList.add("d-none");
            });
    });
});
