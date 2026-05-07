import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// ==========================================================
// LÓGICA FINAL E SEGURA DO MODO ESCURO
// ==========================================================

// Primeiro, adicionamos um script "bloqueador" no <head> dos seus layouts 
// para prevenir a "piscada" de tela.
// (Já fizemos isso, apenas como lembrete).

// Agora, a lógica para o botão de toggle.
const themeToggleBtn = document.getElementById('theme-toggle');

// O "Guarda de Segurança": Só executa o código se o botão existir na página.
if (themeToggleBtn) {
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    // Função para definir o ícone correto na carga da página
    const applyIconOnLoad = () => {
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }
    };

    // Aplica o ícone correto assim que o script é carregado
    applyIconOnLoad();

    // Adiciona o listener para o clique no botão
    themeToggleBtn.addEventListener('click', function () {

        // troca os ícones
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');

        // Lógica para salvar a preferência (esta parte já estava correta)
        if (localStorage.getItem('color-theme')) {
            if (localStorage.getItem('color-theme') === 'light') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }
        } else {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        }
    });
}