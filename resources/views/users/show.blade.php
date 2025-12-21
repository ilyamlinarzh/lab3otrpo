<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Профиль</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
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
        <!-- Профиль пользователя -->
        <div class="card mb-4">
            <div class="card-body">
                <div style="width: 100%;" class="row">
                    <div class="col-md-12">
                        <h1 class="h2 mb-1">{{ $user->name }}</h1>
                        <p class="text-muted mb-3">
                            Зарегистрирован: {{ $user->created_at->format('d.m.Y') }}
                        </p>
                        
                        <!-- Статистика -->
                        <div class="row mb-4">
                            <div class="col-4 text-center">
                                <div class="h3 mb-1">{{ $user->olympic_games_count }}</div>
                                <small class="text-muted">Игр</small>
                            </div>
                            <div class="col-4 text-center">
                                <div class="h3 mb-1">{{ $user->followers_count }}</div>
                                <small class="text-muted">Подписчиков</small>
                            </div>
                            <div class="col-4 text-center">
                                <div class="h3 mb-1">{{ $user->following_count }}</div>
                                <small class="text-muted">Подписок</small>
                            </div>
                        </div>
                    </div>
                    @if(!$isCurrentUser)
                      <div class="d-grid gap-2">
                          @if($isFollowing)
                              <form action="{{ route('follow.unsubscribe') }}" method="POST">
                                  @csrf
                                  <input type="hidden" name="author_id" value="{{ $user->id }}">
                                  <button type="submit" class="btn btn-danger w-100">
                                      Отписаться
                                  </button>
                              </form>
                          @else
                              <form action="{{ route('follow.subscribe') }}" method="POST">
                                  @csrf
                                  <input type="hidden" name="author_id" value="{{ $user->id }}">
                                  <button type="submit" class="btn btn-success w-100">
                                      Подписаться
                                  </button>
                              </form>
                          @endif
                      </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Последние игры пользователя -->
        @if($recentGames->isNotEmpty())
            <div class="card">
                <div class="card-header bg-bg-2">
                    <h2 class="h5 mb-0">🎮 Последние игры</h2>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($recentGames as $game)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="h6 mb-1">
                                        <a href="{{ route('olympic-games.show', $game->id) }}" class="text-decoration-none">
                                            {{ $game->title }}
                                        </a>
                                    </h5>
                                    <small class="text-muted">{{ $game->city }}, {{ $game->year }} год</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">{{ $game->created_at->format('d.m.Y') }}</small>
                                    <a href="{{ route('games.comments', $game->id) }}" class="small text-decoration-none">
                                        💬 {{ $game->comments()->count() }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($recentGames->count() == 5)
                    <div class="card-footer text-center">
                        <a href="{{ route('users.games', $user->id) }}" class="btn btn-sm bg-accent-1 text-white">
                            Показать все игры
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-4">
                    <p class="text-muted mb-0">Пользователь еще не добавил ни одной игры</p>
                </div>
            </div>
        @endif
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

    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>