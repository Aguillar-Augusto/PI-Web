//
document.addEventListener('change', function(e) {
    // Verifica se o elemento alterado foi o input de PDF
    if (e.target && e.target.name === 'pdf' && e.target.type === 'file') {
        const file = e.target.files[0];
        
        // Defina o limite aqui (10 para Cloudinary, ou 20 se for salvar localmente)
        const limiteMB = 10; 
        const limiteBytes = limiteMB * 1024 * 1024; 

        if (file && file.size > limiteBytes) {
            // Pega os containers do modal
            const modalContent = e.target.closest('.modal-content');
            if (!modalContent) return;

            const modalBody = modalContent.querySelector('.modal-body');
            const modalFooter = modalContent.querySelector('.modal-footer');

            // Salva o formulário original na memória do navegador (se ainda não tiver salvo)
            if (!modalContent.dataset.originalBody) {
                modalContent.dataset.originalBody = modalBody.innerHTML;
                modalContent.dataset.originalFooter = modalFooter.innerHTML;
            }

            // Calcula o tamanho do arquivo que o usuário tentou mandar
            const tamanhoTentativa = (file.size / (1024 * 1024)).toFixed(2);

            // Substitui o corpo do modal com a mensagem de erro
            modalBody.innerHTML = `
                <div class="text-center py-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#dc3545" class="bi bi-exclamation-circle mb-3" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                    </svg>
                    <h4 class="fw-bold text-danger mb-3">Arquivo muito grande!</h4>
                    <p class="text-muted px-4">
                        Infelizmente, por limitações de custos dos nossos servidores na nuvem, não podemos aceitar obras maiores que <strong>${limiteMB}MB</strong>.
                        <br><br>
                        O arquivo selecionado possui <strong>${tamanhoTentativa}MB</strong>. Por favor, comprima o seu PDF e tente novamente.
                    </p>
                </div>
            `;

            // Substitui os botões do rodapé por um botão de voltar
            modalFooter.innerHTML = `
                <button type="button" class="btn btn-outline-secondary fw-bold w-100 btn-voltar-form">Entendi, vou escolher outro arquivo</button>
            `;

            // Lógica para quando o usuário clicar em "Entendi, vou escolher outro arquivo"
            modalFooter.querySelector('.btn-voltar-form').addEventListener('click', function() {
                // Restaura o HTML do formulário original
                modalBody.innerHTML = modalContent.dataset.originalBody;
                modalFooter.innerHTML = modalContent.dataset.originalFooter;
                
                // Limpa o input de arquivo para impedir que o PDF gigante seja enviado por engano
                const restoredInput = modalBody.querySelector('input[name="pdf"]');
                if (restoredInput) restoredInput.value = '';
            });
        }
    }
});