<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Комментарии: {{ $game->title }}</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Навигация -->
    <nav class="bg-light navbar navbar-expand-lg bg-bg-2 border border-border py-2">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="bg-accent-1 text-white d-flex align-items-center justify-content-center me-4 custom-logo">P</div>
                <a href="/" class="navbar-brand mb-0 text-accent-1 fs-6 fw-normal sitename">
                    Хронология Олимпийских игр
                </a>
            </div>
            <div>
                <a href="{{ route('olympic-games.show', $game->id) }}" class="btn bg-accent-1 text-white border-0 custom-btn me-2">
                    К игре
                </a>
                <a href="/" class="btn bg-accent-2 text-white border-0 custom-btn">
                    К списку
                </a>
            </div>
        </div>
    </nav>

    <!-- Заголовок игры -->
    <div class="container my-4">
        <div class="card">
            <div class="card-header">
                <h1 class="h3 mb-0">Комментарии</h1>
                <div class="d-flex align-items-center mt-2">
                    <h2 class="h5 mb-0 text-accent-1">{{ $game->title }}</h2>
                    <span class="text-muted ms-2">{{ $game->city }}</span>
                    <span style="color: black" class="badge bg-accent-2 ms-3">{{ $comments->count() }} комментариев</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-4">
        @auth
            <div class="card mb-4">
                <div class="card-header bg-bg-2">
                    <h3 class="h5 mb-0">Добавить комментарий</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('comments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $game->id }}">
                        
                        <div class="mb-3">
                            <textarea 
                                name="text" 
                                class="form-control @error('text') is-invalid @enderror" 
                                rows="4" 
                                placeholder="Напишите ваш комментарий здесь..."
                                maxlength="500"
                                required
                            >{{ old('text') }}</textarea>
                            
                            @error('text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div class="mt-2 d-flex justify-content-between">
                                <small class="text-muted">Максимум 500 символов</small>
                                <small class="text-muted">Вы вошли как: <strong>{{ auth()->user()->name }}</strong></small>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn bg-accent-1 text-white border-0 custom-btn">
                            Отправить комментарий
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="card mb-4">
                <div class="card-body text-center">
                    <p class="mb-2">Чтобы оставлять комментарии, пожалуйста, войдите в систему</p>
                    <a href="{{ route('login') }}" class="btn bg-accent-1 text-white border-0 custom-btn me-2">
                        Войти
                    </a>
                    <a href="{{ route('register') }}" class="btn bg-accent-2 text-white border-0 custom-btn">
                        Зарегистрироваться
                    </a>
                </div>
            </div>
        @endauth
    </div>

    <!-- Список комментариев -->
    <div class="container my-4">
        @if($comments->isEmpty())
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="text-muted">
                            <path d="M7.5 8.25h9m-9 3H12m-7.5 3h9m-3-6.75v6.75" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12 21a9 9 0 1 1 0-18 9 9 0 0 1 0 18Z" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h4 class="h5 mb-2">Пока нет комментариев</h4>
                    <p class="text-muted mb-0">Будьте первым, кто оставит комментарий к этой игре!</p>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-header bg-bg-2 d-flex justify-content-between align-items-center">
                    <h3 class="h5 mb-0">Все комментарии ({{ $comments->count() }})</h3>
                </div>
                
                <div class="list-group list-group-flush">
                    @foreach($comments as $comment)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div style="width: 100%;" class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <strong class="d-block">{{ $comment->user->name }}</strong>
                                        <small class="text-muted">{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                    @can('delete-comment', $comment)
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                </svg>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('comments.destroy', $comment->comment_id) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" 
                                                                class="dropdown-item text-danger" 
                                                                onclick="return confirm('Удалить комментарий?')">
                                                            <svg width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                            </svg>
                                                            Удалить
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                @endcan
                                </div>

                          
                            </div>
                            
                            <div class="comment-text">
                                <p class="mb-0">{{ $comment->text }}</p>
                            </div>
                        </div>
                    @endforeach
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
    <!-- Bootstrap JS для dropdown -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>