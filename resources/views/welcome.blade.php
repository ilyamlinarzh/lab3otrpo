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
      <span class="navbar-brand mb-0 text-accent-1 fs-6 fw-normal sitename">
        Хронология Олимпийских игр
      </span>
    </div>
    <a id="loadBtn" href="{{ route('olympic-games.create') }}" class="btn bg-accent-1 text-white border-0 custom-btn">
      Добавить
    </a>
    <!-- <button id="loadBtn" class="btn bg-accent-1 text-white border-0 custom-btn">
      Добавить
    </button> -->
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
                <a href="{{ route('olympic-games.show', $game->id) }}" class="details-link text-accent-1" data-bs-toggle="modal" data-bs-target="#detailsModal" data-index="{{ $game->id }}" data-game-id="{{ $game->id }}">
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
      <div class="social-media">
        <ul class="list-unstyled d-flex align-items-center gap-3 mb-0">
          <li class="d-flex align-items-center justify-content-center rounded-circle border border-black" style="width: 36px; height: 36px;">
            <a href="https://vk.com/" class="d-flex align-items-center justify-content-center">
              <!-- Исправленный путь для SVG -->
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
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>