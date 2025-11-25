import * as bootstrap from 'bootstrap';
import * from "./modalHelpers";
import $ from 'jquery';
window.$ = window.jQuery = $;

import '../sass/styles.scss';


// создание popover
function createAutoClosePopover(element) {
  return new bootstrap.Popover(element, {
    trigger: 'focus',
    placement: 'bottom',
    container: '#detailsModal'
  });
}

// Загрузка контента модалки
async function loadModalContent(gameId) {
  try {
      const response = await fetch(`/olympic-games/${gameId}`);
      const html = await response.text();
      return html;
  } catch (error) {
      console.error('Ошибка загрузки данных:', error);
      return '<div class="alert alert-danger">Ошибка загрузки данных</div>';
  }
}
 
$('.details-link').on('click', function () {
	alert('Сработал клик на блок с классом "ic-block"');
});

// Функция для открытия модалки
async function openGameModal(gameId, index) {
  currentIndex = index;
  
  const modalElement = document.getElementById('detailsModal');
  const modalContent = modalElement.querySelector('.modal-content');
  
  // Показываем индикатор загрузки
  modalContent.innerHTML = `
      <div class="modal-header">
          <h5 class="modal-title">Загрузка...</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
          <div class="spinner-border" role="status">
              <span class="visually-hidden">Загрузка...</span>
          </div>
      </div>
  `;
  
  const content = await loadModalContent(gameId);
  modalContent.innerHTML = content;
  
  const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
  modal.show();
}

document.addEventListener('DOMContentLoaded', () => {


  // Toast
  const loadBtn = document.getElementById('loadBtn');
  const toastEl = document.getElementById('loadToast');
  const toast = new bootstrap.Toast(toastEl);

  loadBtn.addEventListener('click', () => toast.show());

  // Popovers
  const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
  [...popoverTriggerList].map(el => createAutoClosePopover(el));

  // Модальное окно
  const modal = document.getElementById('detailsModal');
  const modalText = document.getElementById('modalText');
  let currentIndex = 0;

  document.addEventListener('click', (e) => {
    if (e.target.classList.contains('details-link')) {
      e.preventDefault();
      const index = parseInt(e.target.dataset.index, 10);
      currentIndex = index;
      updateModalContent(index);
    }
  });

  function updateModalContent(index) {
    const game = olympicGames[index];
    modalText.innerHTML = `
      <h6>${game.title}</h6>
      <p>${game.detailedDescription}</p>
      <small class="text-muted">
        <a href="#" class="popover-link text-accent-1" 
           data-bs-toggle="popover" 
           data-bs-title="Интересный факт"
           data-bs-content="${game.funFact}"
           tabindex="0">
          <i class="fas fa-lightbulb me-1"></i>Интересный факт
        </a>
      </small>
    `;
    
    // Инициализируем popover для новой ссылки с авто-закрытием
    const newPopover = modalText.querySelector('.popover-link');
    if (newPopover) {
      createAutoClosePopover(newPopover);
    }
  }

  // Переключение стрелками 
  document.addEventListener('keydown', (e) => {
    if (!modal.classList.contains('show')) return;
    
    if (e.key === 'ArrowRight') {
      currentIndex = (currentIndex + 1) % olympicGames.length;
      updateModalContent(currentIndex);
    }
    
    if (e.key === 'ArrowLeft') {
      currentIndex = (currentIndex - 1 + olympicGames.length) % olympicGames.length;
      updateModalContent(currentIndex);
    }
  });

  // Инициализация popover при открытии модального окна
  modal.addEventListener('show.bs.modal', () => {
    const modalPopovers = modal.querySelectorAll('[data-bs-toggle="popover"]');
    [...modalPopovers].map(el => createAutoClosePopover(el));
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('[data-bs-toggle="popover"]') && 
        !e.target.closest('.popover')) {
      const openPopovers = document.querySelectorAll('.popover');
      openPopovers.forEach(popover => {
        const popoverInstance = bootstrap.Popover.getInstance(
          document.querySelector(`[aria-describedby="${popover.id}"]`)
        );
        if (popoverInstance) {
          popoverInstance.hide();
        }
      });
    }
  });
});