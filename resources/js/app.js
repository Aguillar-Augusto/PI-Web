// Ativa o scroll horizontal com a roda do rato na lista de livros
const scrollWrappers = document.querySelectorAll('.scrolling-wrapper');

scrollWrappers.forEach(wrapper => {
    wrapper.addEventListener('wheel', function(e) {
        // Se a roda do rato se mover na vertical, transforma em movimento horizontal
        if (e.deltaY !== 0) {
            e.preventDefault(); // Evita que a página inteira desça
            wrapper.scrollLeft += e.deltaY;
        }
    });
});