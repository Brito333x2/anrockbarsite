let showSelecionado = null;

fetch('admin/shows.json')
  .then(response => response.json())
  .then(shows => {
    const carouselInner = document.getElementById('carouselShows');

    // Filtrar apenas shows com "melhores": false
    const showsFiltrados = shows.filter(show => show.melhores === true);

    // Função para dividir o array em grupos de tamanho n
    function chunkArray(arr, n) {
      const chunks = [];
      for(let i = 0; i < arr.length; i += n) {
        chunks.push(arr.slice(i, i + n));
      }
      return chunks;
    }

    function montarCarrossel(cardsPorSlide) {
      carouselInner.innerHTML = ''; // Limpa o carrossel antes de montar

      const groupedShows = chunkArray(showsFiltrados, cardsPorSlide);

      groupedShows.forEach((group, groupIndex) => {
        const item = document.createElement('div');
        item.className = `carousel-item${groupIndex === 0 ? ' active' : ''}`;

        // Criar a div row que vai conter até cardsPorSlide cards
        const row = document.createElement('div');
        row.className = 'row';

        group.forEach(show => {
          const card = document.createElement('div');
          card.className = 'show-card';
          card.innerHTML = `
            <img src="admin/${show.imagem}" alt="${show.nome}" style="width: 100%; border-radius: 10px;">
            <h5>${show.nome}</h5>
          `;
          card.addEventListener('click', () => {
            showSelecionado = show; // guarda o show clicado
            const nome = document.getElementById('showNome');
            const data = document.getElementById('showData');
            const horario = document.getElementById('showHorario');
            nome.textContent = show.nome;
            data.textContent = show.data;
            horario.textContent = show.horario;
            const modal = new bootstrap.Modal(document.getElementById('modalReserva'));
            modal.show();
          });
          row.appendChild(card);
        });

        item.appendChild(row);
        carouselInner.appendChild(item);
      });
    }

    // Inicializa com base na largura da janela
    function iniciarCarrossel() {
      const largura = window.innerWidth;
      const cardsPorSlide = largura < 993 ? 1 : 3;
      montarCarrossel(cardsPorSlide);
    }

    iniciarCarrossel();

    // Atualiza carrossel ao redimensionar a janela
    window.addEventListener('resize', () => {
      iniciarCarrossel();
    });
  });

// Botão de reserva via WhatsApp
document.getElementById('btnReservar').addEventListener('click', () => {
  const qtd = parseInt(document.getElementById('quantidade').value);
  if (!showSelecionado || qtd < 1) return;

  const numeroWhatsApp = "5511961201454"; // Substitua pelo número real com DDI e DDD
  const mensagem = `Olá! Gostaria de reservar ${qtd} ingresso(s) para o show: ${showSelecionado.nome} - ${showSelecionado.data} às ${showSelecionado.horario}.`;

  const url = `https://wa.me/${numeroWhatsApp}?text=${encodeURIComponent(mensagem)}`;
  window.open(url, '_blank');
});

// Animação da navbar e outras interações
window.addEventListener('DOMContentLoaded', event => {
  // Navbar shrink function
  var navbarShrink = function () {
    const navbarCollapsible = document.body.querySelector('#mainNav');
    if (!navbarCollapsible) return;
    if (window.scrollY === 0) {
      navbarCollapsible.classList.remove('navbar-shrink');
    } else {
      navbarCollapsible.classList.add('navbar-shrink');
    }
  };

  // Shrink navbar on scroll
  navbarShrink();
  document.addEventListener('scroll', navbarShrink);

  // Bootstrap scrollspy
  const mainNav = document.body.querySelector('#mainNav');
  if (mainNav) {
    new bootstrap.ScrollSpy(document.body, {
      target: '#mainNav',
      rootMargin: '0px 0px -40%',
    });
  }

  // Collapse responsive navbar
  const navbarToggler = document.body.querySelector('.navbar-toggler');
  const responsiveNavItems = [].slice.call(
    document.querySelectorAll('#navbarResponsive .nav-link')
  );
  responsiveNavItems.map(function (responsiveNavItem) {
    responsiveNavItem.addEventListener('click', () => {
      if (window.getComputedStyle(navbarToggler).display !== 'none') {
        navbarToggler.click();
      }
    });
  });
});

// Tela de carregamento
window.addEventListener('load', function () {
  const loadingScreen = document.getElementById('loading-screen');

  // Aguarda 2 segundos após o carregamento da página
  setTimeout(function () {
    // Adiciona classe para iniciar o fade-out
    loadingScreen.classList.add('fade-out');

    // Após 1 segundo (tempo da transição), esconde a div completamente
    setTimeout(function () {
      loadingScreen.style.display = 'none';
    }, 1000); // igual ao tempo da transição CSS
  }, 2000);
});
