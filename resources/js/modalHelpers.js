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
    
    // Загружаем контент
    const content = await loadModalContent(gameId);
    modalContent.innerHTML = content;
    
    // Показываем модалку
    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
    modal.show();
}

function test(a) {
  alert(a);
}