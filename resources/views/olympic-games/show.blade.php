<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $game->title }}, {{ $game->city }}</title>
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
            <a href="/" class="btn bg-accent-1 text-white border-0 custom-btn">
                Назад к списку
            </a>
        </div>
    </nav>

    <!-- Детальная информация -->
    <div class="container my-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h1 class="h3 mb-0">{{ $game->title }}</h1>
                        <p class="text-muted mb-0">{{ $game->city }}, {{ $game->year }} год</p>
                    </div>
                    
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ asset('img/' . $game->image_filename) }}" 
                                 alt="{{ $game->title }}" 
                                 class="img-fluid rounded"
                                 style="max-height: 400px; object-fit: cover;">
                        </div>
                        
                        <div class="mb-4">
                            <h5>Краткое описание</h5>
                            <p class="lead">{{ $game->short_description }}</p>
                        </div>

                        <div class="mb-4">
                            <h5>Подробное описание</h5>
                            <p>{{ $game->detailed_description }}</p>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6 class="alert-heading">📌 Интересный факт</h6>
                            <p class="mb-0">{{ $game->fun_fact }}</p>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">ID #{{ $game->id }}</small>
                            <div>
                                <a href="{{ route('olympic-games.edit', $game->id) }}" class="btn btn-primary">
                                    Редактировать
                                </a>
                                <form action="{{ route('olympic-games.destroy', $game->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Вы уверены, что хотите удалить {{ $game->title }}?')">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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