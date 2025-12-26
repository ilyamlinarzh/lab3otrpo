<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - API Профиль</title>
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
        <!-- Информация о пользователе -->
        <div class="card mb-4">
            <div class="card-body">
                <h1 class="h2 mb-3">{{ $user->name }}</h1>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                        <p class="mb-1 text-muted">
                            Зарегистрирован: {{ $user->created_at->format('d.m.Y') }}
                        </p>
                    </div>
                </div>
                
                <!-- Сообщения об успехе/ошибке -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(session('plainTextToken'))
                    <div class="alert alert-warning">
                        <strong>Новый токен:</strong><br>
                        <code class="d-block mt-2 p-2 bg-light">{{ session('plainTextToken') }}</code>
                        <small class="text-muted">Сохраните этот токен! Он будет показан только один раз.</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Создание нового токена -->
        <div class="card mb-4">
            <div class="card-header bg-bg-2">
                <h2 class="h5 mb-0">Создать API токен</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.create-token') }}">
                    @csrf
                    <div class="mb-3">
                        <input type="text" class="form-control" name="token_name" 
                               placeholder="Название токена (например: Мобильное приложение)" required>
                    </div>
                    <button type="submit" class="btn bg-accent-1">
                        Создать токен
                    </button>
                </form>
            </div>
        </div>

        <!-- Мои токены -->
        <div class="card">
            <div class="card-header bg-bg-2 d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0">Мои токены</h2>
                @if($tokens->count() > 0)
                    <form method="POST" action="{{ route('profile.revoke-all-tokens') }}" class="mb-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Отозвать все токены?')">
                            Отозвать все
                        </button>
                    </form>
                @endif
            </div>
            <div class="card-body">
                @if($tokens->count() > 0)
                    <div class="list-group">
                        @foreach($tokens as $token)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $token->name }}</strong><br>
                                        <small class="text-muted">
                                            Создан: {{ $token->created_at }}
                                            @if($token->last_used_at)
                                                <br>Использован: {{ $token->last_used_at }}
                                            @endif
                                        </small>
                                    </div>
                                    <form method="POST" action="{{ route('profile.revoke-token', $token->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Отозвать токен?')">
                                            Отозвать
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">Нет активных токенов</p>
                @endif
            </div>
        </div>
        
        <!-- Информация об API -->
        <div class="card mt-4">
            <div class="card-header bg-bg-2">
                <h2 class="h5 mb-0">Информация для API</h2>
            </div>
            <div class="card-body">
                <p><strong>Базовый URL API:</strong> <code>{{ url('/api') }}</code></p>
                <p><strong>Заголовок для аутентификации:</strong></p>
                <code class="d-block mb-3 p-2 bg-light">Authorization: Bearer ваш_токен</code>
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

    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>