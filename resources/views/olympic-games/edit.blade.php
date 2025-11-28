<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать {{ $game->title }}</title>
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

    <!-- Форма редактирования -->
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Редактировать: {{ $game->title }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('olympic-games.update', $game->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Название игр *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $game->title) }}" 
                                       required maxlength="255">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="city" class="form-label">Город *</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                               id="city" name="city" value="{{ old('city', $game->city) }}" 
                                               required maxlength="100">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="year" class="form-label">Год проведения *</label>
                                        <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                               id="year" name="year" value="{{ old('year', $game->year) }}" 
                                               min="1900" max="2060" required>
                                        @error('year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="short_description" class="form-label">Краткое описание *</label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                          id="short_description" name="short_description" 
                                          rows="3" maxlength="500" required>{{ old('short_description', $game->short_description) }}</textarea>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="detailed_description" class="form-label">Подробное описание *</label>
                                <textarea class="form-control @error('detailed_description') is-invalid @enderror" 
                                          id="detailed_description" name="detailed_description" 
                                          rows="5" required>{{ old('detailed_description', $game->detailed_description) }}</textarea>
                                @error('detailed_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="fun_fact" class="form-label">Интересный факт *</label>
                                <textarea class="form-control @error('fun_fact') is-invalid @enderror" 
                                          id="fun_fact" name="fun_fact" 
                                          rows="2" maxlength="300" required>{{ old('fun_fact', $game->fun_fact) }}</textarea>
                                @error('fun_fact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image_upload" class="form-label">Загрузить новое изображение</label>
                                <input type="file" class="form-control @error('image_upload') is-invalid @enderror" 
                                      id="image_upload" name="image_upload" 
                                      accept="image/jpeg,image/png,image/webp,image/gif">
                                @error('image_upload')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Оставьте пустым, чтобы сохранить текущее изображение. 
                                    Поддерживаемые форматы: JPEG, PNG, WebP, GIF. Максимальный размер: 2MB
                                </div>
                                
                                <!-- Текущее изображение -->
                                @if($game->image_filename)
                                    <div class="mt-2">
                                        <p class="mb-1">Текущее изображение:</p>
                                        <img src="{{ asset('img/' . $game->image_filename) }}" 
                                             alt="Текущее изображение" 
                                             class="img-thumbnail" 
                                             style="max-height: 150px;">
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                                <a href="/" class="btn btn-secondary">Отмена</a>
                                <a href="{{ route('olympic-games.show', $game->id) }}" class="btn btn-outline-info">Просмотр</a>
                            </div>
                        </form>
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