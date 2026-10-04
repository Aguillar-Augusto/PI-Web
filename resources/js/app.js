console.log("O script novo carregou com sucesso!");

// 1. Verifica quando o usuário SELECIONA o arquivo
document.addEventListener('change', function(e) {
    if (e.target && e.target.name === 'pdf' && e.target.type === 'file') {
        verificarTamanhoArquivo(e.target);
    }
});

// 2. Trava de segurança: Verifica quando o usuário CLICA EM POSTAR
document.addEventListener('submit', function(e) {
    const form = e.target;
    const inputPdf = form.querySelector('input[name="pdf"][type="file"]');
    
    if (inputPdf && inputPdf.files.length > 0) {
        const limiteBytes = 10 * 1024 * 1024; // 10MB
        if (inputPdf.files[0].size > limiteBytes) {
            e.preventDefault(); // Impede o envio do formulário
            verificarTamanhoArquivo(inputPdf);
        }
    }
});

// Função principal que exibe o erro
function verificarTamanhoArquivo(input) {
    const file = input.files[0];
    const limiteMB = 10;
    const limiteBytes = limiteMB * 1024 * 1024;

    if (file && file.size > limiteBytes) {
        const modalContent = input.closest('.modal-content');
        if (!modalContent) return;

        const modalBody = modalContent.querySelector('.modal-body');
        const modalFooter = modalContent.querySelector('.modal-footer');
        const formElement = modalBody.querySelector('form');

        // Cria a div de erro se ela ainda não existir
        let errorDiv = modalContent.querySelector('.pdf-error-message');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'pdf-error-message text-center py-4';
            modalBody.appendChild(errorDiv);
        }

        const tamanhoTentativa = (file.size / (1024 * 1024)).toFixed(2);
        
        // Preenche a div com a mensagem de erro
        errorDiv.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#dc3545" class="bi bi-exclamation-circle mb-3" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
            </svg>
            <h4 class="fw-bold text-danger mb-3">Arquivo muito grande!</h4>
            <p class="text-muted px-4">
                Infelizmente, por limitações de armazenamento, não podemos aceitar obras maiores que <strong>${limiteMB}MB</strong>.
                <br><br>
                O arquivo selecionado possui <strong>${tamanhoTentativa}MB</strong>.
            </p>
            <button type="button" class="btn btn-outline-secondary fw-bold mt-3 btn-voltar-form">Escolher outro arquivo</button>
        `;

        // Esconde o formulário e o rodapé originais, e mostra o erro
        if (formElement) formElement.style.display = 'none';
        if (modalFooter) modalFooter.style.display = 'none';
        errorDiv.style.display = 'block';

        // Lógica do botão "Escolher outro arquivo"
        errorDiv.querySelector('.btn-voltar-form').addEventListener('click', function() {
            errorDiv.style.display = 'none'; // Esconde o erro
            if (formElement) formElement.style.display = 'block'; // Mostra o form
            if (modalFooter) modalFooter.style.display = 'flex'; // Mostra o footer
            input.value = ''; // Limpa o PDF pesado que estava selecionado
        });
    }
}