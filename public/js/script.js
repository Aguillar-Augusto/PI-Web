// Ativa o scroll horizontal com a roda do rato na lista de livros
const scrollWrappers = document.querySelectorAll('.scrolling-wrapper');

scrollWrappers.forEach(wrapper => {
    wrapper.addEventListener('wheel', function(e) {
        if (e.deltaY !== 0) {
            e.preventDefault();
            wrapper.scrollLeft += e.deltaY;
        }
    });
});