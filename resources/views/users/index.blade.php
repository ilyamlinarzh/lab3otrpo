<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пользователи - Хронология Олимпийских игр</title>
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
        <!-- Заголовок и поиск -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="h3 mb-0">👥 Пользователи сайта</h1>
                        <p class="text-muted mb-0 mt-1">Все зарегистрированные пользователи</p>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('users.index') }}" method="GET" class="d-flex">
                            <input type="text" 
                                   name="search" 
                                   class="form-control me-2" 
                                   placeholder="Поиск по имени или email..."
                                   value="{{ $search }}">
                            <button type="submit" class="btn bg-accent-1 text-white">Найти</button>
                        </form>
                    </div>
                </div>
                
                <!-- Фильтры сортировки -->
                <div class="mt-3">
                    <div class="btn-group" role="group">
                        <a href="{{ route('users.index', ['sort' => 'newest'] + request()->except('sort')) }}" 
                           class="btn btn-outline-accent-2 {{ $sort == 'newest' ? 'active' : '' }}">
                            Новые
                        </a>
                        <a href="{{ route('users.index', ['sort' => 'oldest'] + request()->except('sort')) }}" 
                           class="btn btn-outline-accent-2 {{ $sort == 'oldest' ? 'active' : '' }}">
                            Старые
                        </a>
                        <a href="{{ route('users.index', ['sort' => 'games'] + request()->except('sort')) }}" 
                           class="btn btn-outline-accent-2 {{ $sort == 'games' ? 'active' : '' }}">
                            По играм
                        </a>
                        <a href="{{ route('users.index', ['sort' => 'followers'] + request()->except('sort')) }}" 
                           class="btn btn-outline-accent-2 {{ $sort == 'followers' ? 'active' : '' }}">
                            По подписчикам
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Сетка пользователей -->
        @if($users->isEmpty())
            <div class="card">
                <div class="card-body text-center py-5">
                    <h3 class="h5 mb-3">Пользователи не найдены</h3>
                    <p class="text-muted">Попробуйте изменить параметры поиска</p>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($users as $user)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div>
                                        <h5 class="mb-1">
                                            <a href="{{ route('users.show', $user->id) }}" class="text-decoration-none text-accent-1">
                                                {{ $user->name }}
                                            </a>
                                        </h5>
                                        <small class="text-muted">
                                            Зарегистрирован: {{ $user->created_at->format('d.m.Y') }}
                                        </small> 
                                    </div>
                                </div>
                                
                                <div class="user-stats mb-3">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="h5 mb-1">{{ $user->olympic_games_count }}</div>
                                            <small class="text-muted">Игр</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="h5 mb-1">{{ $user->followers_count }}</div>
                                            <small class="text-muted">Подписчиков</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="h5 mb-1">{{ $user->following_count }}</div>
                                            <small class="text-muted">Подписок</small>
                                        </div>
                                    </div>
                                </div>
                                
                                @auth
                                    @if($currentUser->id != $user->id)
                                        <div class="d-grid gap-2">
                                            @if(in_array($user->id, $followingIds))
                                                <small class="text-success text-center">
                                                    ✓ Вы подписаны
                                                </small>
                                            @endif
                                        </div>
                                    @else
                                      <div class="d-grid gap-2">
                                          <small class="text-success text-center">
                                            Это вы
                                          </small>
                                        </div>
                                    @endif
                                @else
                                    <!-- Не авторизован -->
                                    <a href="{{ route('login') }}" class="btn btn-outline-accent-1 w-100">
                                        Войдите, чтобы подписаться
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Пагинация -->
            <div class="mt-4">
                {{ $users->links() }}
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