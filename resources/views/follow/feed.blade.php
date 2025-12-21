<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Моя лента - Хронология Олимпийских игр</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        .game-card {
            transition: transform 0.2s;
            border-left: 4px solid transparent;
        }
        .game-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-left-color: #4a6fa5;
        }
        .author-avatar {
            width: 40px;
            height: 40px;
            font-size: 16px;
            background-color: #4a6fa5;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        .empty-feed-icon {
            width: 80px;
            height: 80px;
            background-color: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .badge-custom {
            background-color: #e9ecef;
            color: #495057;
            font-weight: 500;
            padding: 0.35em 0.65em;
        }
        .user-suggestion-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
        }
        .user-suggestion-card:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <nav class="bg-light navbar navbar-expand-lg bg-bg-2 border border-border py-2">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="bg-accent-1 text-white d-flex align-items-center justify-content-center me-4 custom-logo">P</div>
                <a href="/" class="navbar-brand mb-0 text-accent-1 fs-6 fw-normal sitename">
                    Хронология Олимпийских игр
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body text-center py-4">
                        <h1 class="h3 mb-2">📰 Моя лента</h1>
                        <p class="text-muted mb-0">
                            @if($emptyFeed)
                                Подпишитесь на других пользователей, чтобы видеть их игры здесь
                            @else
                                Новые игры от пользователей, на которых вы подписаны
                            @endif
                        </p>
                    </div>
                </div>

                @if($emptyFeed)
                    <!-- Пустая лента -->
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <h3 class="h5 mb-3">Лента пуста</h3>
                            <p class="text-muted mb-4">
                                Вы пока ни на кого не подписаны. Найдите интересных пользователей и подпишитесь на них, 
                                чтобы видеть их игры в вашей ленте.
                            </p>
                        </div>
                    </div>
                @else
                    @forelse($games as $game)
                        <div class="card game-card mb-3">
                            <div class="card-body">
                                <!-- Заголовок и автор -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="author-avatar me-3">
                                            {{ strtoupper(substr($game->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h5 class="mb-1">
                                                <a href="{{ route('users.show', $game->user->id) }}" class="text-decoration-none text-accent-1">
                                                    {{ $game->user->name }}
                                                </a>
                                            </h5>
                                            <small class="text-muted">
                                                {{ $game->created_at->format('d.m.Y H:i') }} • 
                                                {{ $game->city }}, {{ $game->year }} год
                                            </small>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-border" type="button" data-bs-toggle="dropdown">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                            </svg>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('olympic-games.show', $game->id) }}">
                                                    <svg width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                    </svg>
                                                    Просмотр игры
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('games.comments', $game->id) }}">
                                                    <svg width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                                        <path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"/>
                                                    </svg>
                                                    Комментарии ({{ $game->comments()->count() }})
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Заголовок игры -->
                                <h4 class="mb-3">
                                    <a href="{{ route('olympic-games.show', $game->id) }}" class="text-decoration-none text-dark">
                                        {{ $game->title }}
                                    </a>
                                </h4>

                                <!-- Краткое описание -->
                                <p class="mb-3">{{ Str::limit($game->short_description, 200) }}</p>

                                <!-- Изображение -->
                                @if($game->image_filename)
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('img/' . $game->image_filename) }}" 
                                             alt="{{ $game->title }}" 
                                             class="img-fluid rounded"
                                             style="max-height: 300px; object-fit: cover;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <h3 class="h5 mb-3">Нет новых игр</h3>
                                <p class="text-muted">
                                    Пользователи, на которых вы подписаны, еще не добавили новых игр.
                                </p>
                            </div>
                        </div>
                    @endforelse
                @endif
            </div>
        </div>
    </div>

    <!-- Футер -->
    <footer class="bg-body-bg text-black border-top border-2 py-3 mt-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <span class="siteAuthor">Прудников Илья</span>
                <div class="social-media">
                    <ul class="list-unstyled d-flex align-items-center gap-3 mb-0">
                        <li class="d-flex align-items-center justify-content-center rounded-circle border border-black" style="width: 36px; height: 36px;">
                            <a href="https://vk.com/" class="d-flex align-items-center justify-content-center">
                                <img src="{{ asset('svg/vk.svg') }}" alt="VK" width="20" height="20">
                            </a>
                        </li>
                        <li class="d-flex align-items-center justify-content-center rounded-circle border border-black" style="width: 36px; height: 36px;">
                            <a href="https://telegram.org/" class="d-flex align-items-center justify-content-center">
                                <img src="{{ asset('svg/tg.svg') }}" alt="TG" width="20" height="20">
                            </a>
                        </li>
                        <li class="d-flex align-items-center justify-content-center rounded-circle border border-black" style="width: 36px; height: 36px;">
                            <a href="https://youtube.com/" class="d-flex align-items-center justify-content-center">
                                <img src="{{ asset('svg/yt.svg') }}" alt="YT" width="20" height="20">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS для dropdown -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>