<div class="mb-3 d-flex flex-md-row flex-column justify-content-between align-items-center w-100">

    {{-- Left side navigation buttons --}}
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('grammar.index') }}" class="btn btn-outline-primary">
            📚 Grammar Guide
        </a>

        @auth
            <a href="{{ route('quiz.index') }}" class="btn btn-success">Quiz</a>
            <a href="{{ route('favorites') }}" class="btn btn-success">
                ★ Favorites
            </a>
        @endauth

 @auth
<div class="dropdown notification ms-3">
    <button class="btn btn-secondary position-relative" type="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
        <!-- Bell SVG -->
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
          <path d="M8 16a2 2 0 0 0 1.985-1.75H6.015A2 2 0 0 0 8 16zm.104-14.668a1 1 0 1 0-2.208 0c-.014.06-.024.12-.03.18A5.002 5.002 0 0 0 3 6c0 1.098-.482 2.209-1.28 3.172-.23.284-.364.657-.364 1.082v.166c0 .554.448 1 1 1h10c.552 0 1-.446 1-1v-.166c0-.425-.134-.798-.364-1.082A6.997 6.997 0 0 1 13 6a5.002 5.002 0 0 0-2.876-4.488c-.006-.06-.016-.12-.03-.18z"/>
        </svg>

        @if($unreadCount = auth()->user()->unreadNotifications->count())
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount }}
                <span class="visually-hidden">unread notifications</span>
            </span>
        @endif
    </button>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
        @forelse(auth()->user()->notifications->take(10) as $notification)
            <li class="dropdown-item {{ $notification->read_at ? '' : 'fw-bold' }}">
                {{ $notification->data['message'] }}
                <br>
                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
            </li>
        @empty
            <li class="dropdown-item text-center text-muted">No notifications</li>
        @endforelse
        @if(auth()->user()->notifications->count() > 10)
            <li><hr class="dropdown-divider"></li>
            <li><a href="{{ route('notifications.index') }}" class="dropdown-item text-center">View all</a></li>
        @endif
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdownBtn = document.getElementById('notificationsDropdown');
    const dropdownMenu = dropdownBtn.nextElementSibling; // the <ul> dropdown

    dropdownBtn.addEventListener('show.bs.dropdown', function () {
        // Only if there are unread notifications
        const unreadBadge = dropdownBtn.querySelector('.badge.bg-danger');
        if (!unreadBadge) return; // no unread, no need to mark as read

        // Make POST request to mark all unread as read
        fetch("{{ route('notifications.markRead') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        }).then(response => {
            if (response.ok) {
                // Optionally remove the badge immediately
                unreadBadge.remove();
                // Optionally remove bold from unread notifications in UI
                dropdownMenu.querySelectorAll('.fw-bold').forEach(el => el.classList.remove('fw-bold'));
            }
        });
    });
});
</script>
@endauth

    </div>

    {{-- Right side user/auth section --}}
    <div class="d-flex align-items-center gap-3 flex-wrap">

        @auth
            <div class="d-flex align-items-center gap-3 flex-wrap">

                @if(Auth::user()->can_manage_words)
                    <span class="text-muted small d-flex align-items-center gap-1">
                        <i class="fas fa-user-shield"></i> Admin Mode
                    </span>
                @endif

                <span class="text-muted small">
                    Welcome, {{ Auth::user()->name }}
                </span>

                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        @else
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            </div>
        @endauth

    </div>
  


  @auth

@endauth



</div>
