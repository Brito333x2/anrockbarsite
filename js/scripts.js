let showSelecionado = null;

// Função para formatar data no formato brasileiro
function formatarData(dataStr) {
  const data = new Date(dataStr);
  return data.toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  });
}

// Função para fechar o modal do show
function fecharModalShow() {
  const modal = document.getElementById('showModal');
  if (modal) {
    const bsModal = bootstrap.Modal.getInstance(modal);
    if (bsModal) bsModal.hide();
  }
}

fetch('admin/shows.json')
  .then(response => response.json())
  .then(shows => {
    const carouselInner = document.getElementById('carouselShows');

    const hoje = new Date();
    hoje.setHours(0,0,0,0); // Zera hora para comparar só datas

    // Filtra apenas os shows marcados como "melhores" e que ainda não passaram
    const showsFiltrados = shows.filter(show => {
      const dataShow = new Date(show.data);
      dataShow.setHours(0,0,0,0);
      return show.melhores === true && dataShow >= hoje;
    });

    // Divide o array em grupos de N itens
    function chunkArray(arr, n) {
      const chunks = [];
      for (let i = 0; i < arr.length; i += n) {
        chunks.push(arr.slice(i, i + n));
      }
      return chunks;
    }

    // Monta o carrossel com base na quantidade de cards por slide
    function montarCarrossel(cardsPorSlide) {
      carouselInner.innerHTML = ''; // Limpa antes de montar

      const groupedShows = chunkArray(showsFiltrados, cardsPorSlide);

      groupedShows.forEach((group, index) => {
        const item = document.createElement('div');
        item.className = `carousel-item${index === 0 ? ' active' : ''}`;

        const row = document.createElement('div');
        row.className = 'row';

        group.forEach(show => {
          const card = document.createElement('div');
          card.className = 'show-card';
          card.style.cursor = 'pointer'; // Indica que é clicável
          card.innerHTML = `
            <img src="admin/${show.imagem}" alt="${show.nome}" style="width: 100%; border-radius: 10px;">
            <h5>${show.nome}</h5>
          `;

          card.addEventListener('click', () => {
            const selectedShow = show;
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

            // Abre o modal via Bootstrap 5
            const showModal = new bootstrap.Modal(document.getElementById('showModal'));
            showModal.show();
          });

          row.appendChild(card);
        });

        item.appendChild(row);
        carouselInner.appendChild(item);
      });
    }

    function iniciarCarrossel() {
      const largura = window.innerWidth;
      const cardsPorSlide = largura < 993 ? 1 : 3;
      montarCarrossel(cardsPorSlide);
    }

    iniciarCarrossel();
    window.addEventListener('resize', iniciarCarrossel);
  });

// Animação da navbar e interações
window.addEventListener('DOMContentLoaded', () => {
  const navbar = document.getElementById('mainNav');

  const navbarShrink = () => {
    if (!navbar) return;
    navbar.classList.toggle('navbar-shrink', window.scrollY > 0);
  };

  navbarShrink();
  document.addEventListener('scroll', navbarShrink);

  // ScrollSpy
  if (navbar) {
    new bootstrap.ScrollSpy(document.body, {
      target: '#mainNav',
      rootMargin: '0px 0px -40%',
    });
  }

  // Fechar menu mobile ao clicar em link
  const navbarToggler = document.querySelector('.navbar-toggler');
  const navLinks = document.querySelectorAll('#navbarResponsive .nav-link');
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      if (getComputedStyle(navbarToggler).display !== 'none') {
        navbarToggler.click();
      }
    });
  });
});

// Tela de carregamento
window.addEventListener('load', () => {
  const loadingScreen = document.getElementById('loading-screen');
  if (!loadingScreen) return;

  setTimeout(() => {
    loadingScreen.classList.add('fade-out');
    setTimeout(() => {
      loadingScreen.style.display = 'none';
    }, 1000);
  }, 2000);
});
