<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Хронология Олимпийских игр</title>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body>
  <!-- Навигация -->
  <nav class="bg-light navbar navbar-expand-lg bg-bg-2 border border-border py-2">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="bg-accent-1 text-white d-flex align-items-center justify-content-center 
                        me-4 custom-logo">P</div>
            <a href="/" class="navbar-brand mb-0 text-accent-1 fs-6 fw-normal sitename">
                    Хронология Олимпийских игр
                </a>
        </div>
        
        <div>
            @guest
                <!-- Для неавторизованных - просто кнопка Войти -->
                <a id="loadBtn" href="{{ route('login') }}" class="btn text-accent-1 border-0">
                    Войти
                </a>
            @endguest
            
            @auth
                <!-- Выпадающее меню для авторизованных -->
                <div class="dropdown d-inline-block">
                    <button class="btn text-accent-1 border-0 dropdown-toggle" 
                            type="button" 
                            id="userDropdown" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('follow.feed') }}">
                                Моя лента
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                Профиль
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('olympic-games.create') }}">
                                Добавить игру
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right me-2" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                    </svg>
                                    Выйти
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>
  </nav>

  <!-- Контент -->
  <div class="container my-4">
    <h1 class="mb-4 text-center">Список Олимпийских игр</h1>

    
      <div class="d-flex justify-content-center mb-4">
        <div class="btn-group" role="group">
          <a href="/" 
              class="btn btn-outline-primary {{ !request()->has('sort') || request('sort') == 'desc' ? 'active' : '' }}">
            Сначала новые
          </a>
          <a href="/?sort=asc" 
              class="btn btn-outline-primary {{ request('sort') == 'asc' ? 'active' : '' }}">
            Сначала старые
          </a>
        </div>
      </div>
    
    <div class="row g-4 cardContainer">
      @foreach($games as $index => $game)
        <div class="col-sm-6 col-lg-4 mb-4 cardCol">
          <div class="card h-100 position-relative">
            <span class="badge position-absolute top-0 start-0 m-3 tag-badge card__Tag">
              {{ $game->city }}
            </span>

            <div class="card-img-top img-container overflow-hidden" style="aspect-ratio:1;">
              <img src="{{ asset('img/' . $game->image_filename) }}"
                  alt="{{ $game->city }} {{ $game->year }}" 
                  class="w-100 h-100 object-fit-cover position-absolute top-50 start-50 translate-middle">
            </div>

            <div class="card-body d-flex flex-column">
              <h5 class="card-title">{{ $game->title }}</h5>
              <p class="card-text flex-grow-1">
                {{ $game->short_description }}
                <a href="{{ route('olympic-games.show', $game->id) }}" class="text-accent-1"  data-index="{{ $game->id }}" data-game-id="{{ $game->id }}">
                  Подробнее
                </a>
              </p>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- Модалка -->
  <div class="modal fade" id="detailsModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Подробнее об играх</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="modalText">
            <!-- контент из js -->
          </div>
        </div>
        <div class="modal-footer">
          <small class="text-muted me-auto">Используйте стрелки ← → для навигации</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast -->
  <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="loadToast" class="toast" role="alert">
      <div class="toast-header">
        <i class="fa-solid fa-rotate fa-spin me-2"></i>
        <strong class="me-auto">Информация</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        Функционал загрузки временно недоступен.
      </div>
    </div>
  </div>

  <footer class="bg-body-bg text-black border-top border-2 py-3 mt-4">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <span class="siteAuthor">Прудников Илья</span>
      <a href="{{ route('users.index') }}" class="btn text-accent-1">
        👤 Пользователи сайта
      </a>
      <div class="social-media">
        <ul class="list-unstyled d-flex align-items-center gap-3 mb-0">
          <li class="d-flex align-items-center justify-content-center rounded-circle border border-black" style="width: 36px; height: 36px;">
            <a href="https://vk.com/" class="d-flex align-items-center justify-content-center">
              <img src="{{ asset('svg/vk.svg') }}" alt="VK" width="20" height="20" />
            </a>
          </li>
          <li class="d-flex align-items-center justify-content-center rounded-circle border border-black" style="width: 36px; height: 36px;">
            <a href="https://telegram.org/" class="d-flex align-items-center justify-content-center">
              <img src="{{ asset('svg/tg.svg') }}" alt="TG" width="20" height="20" />
            </a>
          </li>
          <li class="d-flex align-items-center justify-content-center rounded-circle border border-black" style="width: 36px; height: 36px;">
            <a href="https://youtube.com/" class="d-flex align-items-center justify-content-center">
              <img src="{{ asset('svg/yt.svg') }}" alt="YT" width="20" height="20" />
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>
<!-- <script src="{{ asset('js/main.js') }}"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>