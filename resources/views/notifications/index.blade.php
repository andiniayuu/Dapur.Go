@extends(
    auth()->user()->role === 'admin'
        ? 'layouts.admin'
        : 'layouts.app'
)

@section('title', 'Notifikasi - Dapur.Go')

@section('content')

<div class="notification-page">

    <div class="page-heading">

        <div>
            <h1>Notifikasi</h1>
            <p>Informasi terbaru dari aktivitas Dapur.Go.</p>
        </div>

        @if (auth()->user()->unreadNotifications()->exists())

            <form action="{{ route('notifications.read-all') }}"
                  method="POST">

                @csrf

                <button type="submit" class="btn btn-outline">
                    Tandai Semua Dibaca
                </button>

            </form>

        @endif

    </div>

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="notification-list">

        @forelse ($notifications as $notification)

            <div class="notification-item
                {{ $notification->read_at ? 'is-read' : 'is-unread' }}">

                <div class="notification-icon">
                    🔔
                </div>

                <div class="notification-info">

                    <h3>
                        {{ $notification->data['title'] ?? 'Notifikasi Dapur.Go' }}
                    </h3>

                    <p>
                        {{ $notification->data['message'] ?? '' }}
                    </p>

                    <small>
                        {{ $notification->created_at->format('d/m/Y H:i') }}
                    </small>

                    <form
                        action="{{ route('notifications.read', $notification->id) }}"
                        method="POST">

                        @csrf

                        <button type="submit" class="notification-link">
                            Lihat Detail →
                        </button>

                    </form>

                </div>

                @if (!$notification->read_at)
                    <span class="unread-indicator"></span>
                @endif

            </div>

        @empty

            <div class="empty-state">
                <div class="category-icon">🔔</div>

                <h3>Belum Ada Notifikasi</h3>

                <p>Informasi aktivitas Anda akan muncul di sini.</p>
            </div>

        @endforelse

    </div>

    <div class="pagination">
        {{ $notifications->links() }}
    </div>

</div>

@endsection