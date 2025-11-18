<div class="modal-header">
    <h5 class="modal-title">Подробнее об играх</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <h6>{{ $game->title }}</h6>
    <p>{{ $game->detailed_description }}</p>
    <small class="text-muted">
        <a href="#" class="popover-link text-accent-1" 
           data-bs-toggle="popover" 
           data-bs-title="Интересный факт"
           data-bs-content="{{ e($game->fun_fact) }}"
           tabindex="0">
            <i class="fas fa-lightbulb me-1"></i>Интересный факт
        </a>
    </small>
</div>
<div class="modal-footer">
    <small class="text-muted me-auto">Используйте стрелки ← → для навигации</small>
</div>