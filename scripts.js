let showSelecionado = null;

fetch('admin/shows.json')
  .then(response => response.json())
  .then(shows => {
    const carouselInner = document.getElementById('carouselShows');

    // Filtrar apenas shows com "geral": false
    const showsFiltrados = shows.filter(show => show.geral === false);

    showsFiltrados.forEach((show, index) => {
      const item = document.createElement('div');
      item.className = `carousel-item${index === 0 ? ' active' : ''}`;
      item.innerHTML = `
        <img src="${show.imagem}" class="d-block w-100" alt="${show.nome}" style="cursor: pointer;">
      `;
      item.querySelector('img').addEventListener('click', () => {
        showSelecionado = show;
        const nome = document.getElementById('showNome');
        const data = document.getElementById('showData');
        const horario = document.getElementById('showHorario');
        nome.textContent = show.nome;
        data.textContent = show.data;
        horario.textContent = show.horario;
        const modal = new bootstrap.Modal(document.getElementById('modalReserva'));
        modal.show();
      });
      carouselInner.appendChild(item);
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
