<div class="card feature-card mb-3">
    <div class="card-body">
    <h5 class="card-title">{{ $comment->user_name . ' ' . $comment->user_surname[0] . '.' }}</h5>
    <p class="card-text">
        {{ $comment->content }}
    </p>
    </div>
    <div class="card-footer bg-transparent border-top-0">
    <div
        class="d-flex align-items-end justify-content-between w-100"
    >
        <div class="text-muted">
        Created
        <span class="time-component"
            >{{ $comment->created_datetime }} UTC</span
        >
        ·
        <span class="badge bg-primary"
            ><span class="comment-likes-count">{{ $comment->likes_count }}</span>
            <i class="bi bi-hand-thumbs-up-fill"></i
        ></span>
        </div>
        <div>
        @if (!Auth::user() || Auth::user()->hasRole('USER'))
        <button
            class="btn btn-outline-primary like-comment-btn"
            data-id-comment="{{ $comment->id_comment }}"
            data-action="{{ route('event.likeComment') }}"
            aria-label="Like comment"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="Like comment"
        >
            <i class="bi bi-hand-thumbs-up-fill"></i>
        </button>
        @elseif (Auth::user()->hasRole('MODERATOR'))
        <button
            class="btn-close btn-warning remove-comment-btn"
            data-id-comment="{{ $comment->id_comment }}"
            data-action="{{ route('event.removeComment') }}"
            aria-label="Remove comment"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="Remove comment"
        ></button>
        @endif
        </div>
    </div>
    </div>
</div>