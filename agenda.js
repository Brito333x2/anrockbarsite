// Carrega os shows do arquivo JSON
fetch('admin/shows.json')
    .then(response => response.json())
    .then(shows => {
        const showsContainer = document.getElementById('showsContainer');

        shows.filter(show => show.geral === show.geral).forEach((show, index) => {
            const showCard = document.createElement('div');
            showCard.className = 'col-md-6 mb-4';
            showCard.innerHTML = `
                <div class="card">
                    <img src="${show.imagem}" class="card-img-top" alt="${show.nome}" onclick="abrirModalImagem('${show.imagem}')">
                    <div class="card-body">
                        <h5 class="card-title">${show.nome}</h5>
                        <p><strong>Data:</strong> ${show.data} | <strong>Horário:</strong> ${show.horario}</p>
                        <p><strong>Preço:</strong> ${show.preco}</p>
                        <button class="btn btn-primary show-details" data-show-id="${index}">Ver detalhes</button>
                    </div>
                </div>
            `;
            showsContainer.appendChild(showCard);

            showCard.querySelector('.show-details').addEventListener('click', () => {
                const selectedShow = shows[index];
                document.getElementById('showModalLabel').textContent = selectedShow.nome;
                document.getElementById('modalContent').innerHTML = `
                    <p><strong>Data:</strong> ${selectedShow.data} | <strong>Horário:</strong> ${selectedShow.horario}</p>
                    <p><strong>Descrição:</strong> ${selectedShow.descricao}</p>
                    <p><strong>Preço:</strong> ${selectedShow.preco}</p>
                    <div class="mt-3">
                        <label for="quantidadeIngressos"><strong>Quantidade de ingressos:</strong></label>
                        <input type="number" id="quantidadeIngressos" class="form-control" min="1" required>
                        <small class="text-danger d-none" id="erroQuantidade"></small>
                    </div>
                `;
                abrirModalShow();
            });
        });
    });

// Modal de imagem
function abrirModalImagem(src) {
    const modal = document.getElementById("modalImagem");
    const img = document.getElementById("imagemExpandida");
    img.src = src;
    modal.classList.add("show");
}

function fecharModalImagem() {
    document.getElementById("modalImagem").classList.remove("show");
}

// Modal de show
function abrirModalShow() {
    document.getElementById("showModal").classList.add("show");
}

function fecharModalShow() {
    document.getElementById("showModal").classList.remove("show");
}

// Reservar ingresso via WhatsApp
document.getElementById('btnReservar').addEventListener('click', () => {
    const showSelecionado = document.getElementById('showModalLabel').textContent;
    const inputQtd = document.getElementById('quantidadeIngressos');
    const erroQtd = document.getElementById('erroQuantidade');

    const qtd = parseInt(inputQtd.value);

    if (!qtd || qtd < 1) {
        erroQtd.classList.remove('d-none');
        inputQtd.focus();
        return;
    } else {
        erroQtd.classList.add('d-none');
    }

    const numeroWhatsApp = "5511961201454";
    const mensagem = `Olá! Gostaria de reservar ${qtd} ingresso(s) para o show: ${showSelecionado}.`;
    const url = `https://wa.me/${numeroWhatsApp}?text=${encodeURIComponent(mensagem)}`;
    window.open(url, '_blank');
});
