function formatarData(dataStr) {
  const [ano, mes, dia] = dataStr.split('-');
  return `${dia}/${mes}/${ano}`;
}

let todosShows = []; // Armazena os shows carregados

fetch('admin/shows.json')
  .then(response => response.json())
  .then(shows => {
    todosShows = shows;
    todosShows.sort((a, b) => new Date(a.data) - new Date(b.data));
    preencherFiltroMes(todosShows);
    renderizarShows(todosShows);
  });

function preencherFiltroMes(shows) {
  const select = document.getElementById('monthFilter');
  const mesesUnicos = new Set();

  shows.forEach(show => {
    const [ano, mes] = show.data.split('-');
    mesesUnicos.add(`${ano}-${mes}`);
  });

  const mesesOrdenados = Array.from(mesesUnicos).sort();
  mesesOrdenados.forEach(mes => {
    const [ano, m] = mes.split('-');
    const nomeMes = new Date(parseInt(ano), parseInt(m) - 1, 1).toLocaleString('pt-BR', { month: 'long' });
    const option = document.createElement('option');
    option.value = mes;
    option.textContent = `${nomeMes.charAt(0).toUpperCase() + nomeMes.slice(1)} de ${ano}`;
    select.appendChild(option);
  });
}

function renderizarShows(shows) {
  const container = document.getElementById('showsContainer');
  container.innerHTML = '';

  shows.forEach((show, index) => {
    const card = document.createElement('div');
    card.className = 'col-md-6 mb-4';
    card.innerHTML = `
      <div class="card">
        <img src="admin/${show.imagem}" class="card-img-top" alt="${show.nome}" onclick="abrirModalImagem('admin/${show.imagem}')">
        <div class="card-body">
          <h5 class="card-title">${show.nome}</h5>
          <p><strong>Data:</strong> ${formatarData(show.data)} | <strong>Horário:</strong> ${show.horario_inicio}</p>
          <p><strong>Preço:</strong> R$${show.preco},00</p>
          <button class="btn btn-primary show-details" data-show-id="${index}">Ver detalhes</button>
        </div>
      </div>
    `;
    container.appendChild(card);

    card.querySelector('.show-details').addEventListener('click', () => {
      const selectedShow = todosShows[index];
      document.getElementById('showModalLabel').textContent = selectedShow.nome;
      document.getElementById('modalContent').innerHTML = `
        <p><strong>Data:</strong> ${formatarData(selectedShow.data)} | <strong>Horário:</strong> ${selectedShow.horario_inicio}</p>
        <p><strong>Descrição:</strong> ${selectedShow.descricao}</p>
        <p><strong>Preço:</strong> R$${selectedShow.preco},00</p>
        <div style="display: flex; justify-content: center; gap: 15px; margin-top: 20px;">
          <a href="${selectedShow.url_compra}" target="_blank" style="
            display: inline-block;
            background: linear-gradient(45deg, #28a745, #218838);
            color: white;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(33, 136, 56, 0.3);
            text-decoration: none;
            transition: background 0.3s ease;
            user-select: none;
            flex: 1;
            text-align: center;
          ">
            🎟️ Comprar Ingressos
          </a>
          <button onclick="fecharModalShow()" style="
            flex: 1;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 25px;
            border: 2px solid #dc3545;
            background: white;
            color: #dc3545;
            cursor: pointer;
            transition: background 0.3s ease, color 0.3s ease;
          " 
          onmouseenter="this.style.background='#dc3545'; this.style.color='white';" 
          onmouseleave="this.style.background='white'; this.style.color='#dc3545';"
          >
            ✖ Fechar
          </button>
        </div>
      `;

      // Aplicar efeito hover no botão comprar ingressos
      const comprarBtn = document.querySelector('#modalContent a');
      comprarBtn.addEventListener('mouseenter', () => {
        comprarBtn.style.background = 'linear-gradient(45deg, #218838, #1e7e34)';
      });
      comprarBtn.addEventListener('mouseleave', () => {
        comprarBtn.style.background = 'linear-gradient(45deg, #28a745, #218838)';
      });

      abrirModalShow();
    });
  });
}

// Filtros combinados: busca + mês
function aplicarFiltros() {
  const termo = document.getElementById('searchInput').value.toLowerCase();
  const mesSelecionado = document.getElementById('monthFilter').value;

  const filtrados = todosShows.filter(show => {
    const nomeInclui = show.nome.toLowerCase().includes(termo);
    const [ano, mes] = show.data.split('-');
    const mesDoShow = `${ano}-${mes}`;
    const mesBate = !mesSelecionado || mesSelecionado === mesDoShow;
    return nomeInclui && mesBate;
  });

  renderizarShows(filtrados);
}

document.getElementById('searchInput').addEventListener('input', aplicarFiltros);
document.getElementById('monthFilter').addEventListener('change', aplicarFiltros);

// Modais
function abrirModalImagem(src) {
  const modal = document.getElementById("modalImagem");
  const img = document.getElementById("imagemExpandida");
  img.src = src;
  modal.classList.add("show");
}
function fecharModalImagem() {
  document.getElementById("modalImagem").classList.remove("show");
}
function abrirModalShow() {
  document.getElementById("showModal").classList.add("show");
}
function fecharModalShow() {
  document.getElementById("showModal").classList.remove("show");
}
