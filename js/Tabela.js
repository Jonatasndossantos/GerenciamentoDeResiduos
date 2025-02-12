function submitDeleteSingle(codigo) {
    document.getElementById('selectedCodigos').value = codigo;
    const modal = new bootstrap.Modal(document.getElementById('deleteEmployeeModal'));
    modal.show();
}

function submitDelete() {
    const checkboxes = document.querySelectorAll('.checkbox-item:checked');
    if (checkboxes.length === 0) {
        alert('Por favor, selecione pelo menos um registro para excluir.');
        return;
    }
    
    const codigos = Array.from(checkboxes).map(cb => cb.value);
    document.getElementById('selectedCodigos').value = codigos.join(',');
    const modal = new bootstrap.Modal(document.getElementById('deleteEmployeeModal'));
    modal.show();
}

function handleDelete() {
    const selectedCodigos = document.getElementById('selectedCodigos').value;
    if (!selectedCodigos) {
        alert('Nenhum item selecionado para exclusão.');
        return false;
    }

    const form = document.getElementById('deleteForm');
    const formData = new FormData(form);

    fetch('../DAO/Excluir.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.text();
    })
    .then(data => {
        // Fecha o modal de exclusão
        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteEmployeeModal'));
        deleteModal.hide();

        // Mostra o modal de sucesso
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao excluir os registros.');
    });

    return false; // Previne o envio tradicional do formulário
}

// Limpa os códigos quando o modal for fechado
document.getElementById('deleteEmployeeModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('selectedCodigos').value = '';
});

function preencherModal(codigo, dt, categoria, peso) {
    document.querySelector('#editEmployeeModal input[name="codigo"]').value = codigo;
    document.querySelector('#editEmployeeModal input[name="dt"]').value = dt;
    document.querySelector('#editEmployeeModal select[name="categoria"]').value = categoria;
    document.querySelector('#editEmployeeModal input[name="peso"]').value = peso;
}

function realizarPesquisa() {
    const searchTerm = document.getElementById('searchInput').value;
    let currentUrl = new URL(window.location.href);
    
    // Atualiza ou adiciona o parâmetro de pesquisa
    if (searchTerm) {
        currentUrl.searchParams.set('search', searchTerm);
    } else {
        currentUrl.searchParams.delete('search');
    }
    
    // Mantém os outros filtros existentes
    window.location.href = currentUrl.toString();
}

// Permite pesquisar ao pressionar Enter
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        realizarPesquisa();
    }
});

function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.checkbox-item:checked');
    const count = checkboxes.length;
    const countDisplay = document.getElementById('selectedCount');
    countDisplay.textContent = count + (count === 1 ? ' selecionado' : ' selecionados');
}

// Adicionar listener para o checkbox "selecionar todos"
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.checkbox-item');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    updateSelectedCount();
});

// Adicionar listeners para todos os checkboxes individuais
document.querySelectorAll('.checkbox-item').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

// Atualizar contagem inicial
document.addEventListener('DOMContentLoaded', updateSelectedCount);