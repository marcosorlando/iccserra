/**
 * ICC Serra — Banco do Povo
 * main.js — JS mínimo (sem dependências externas)
 * Versão: 1.0 | Mai/2026
 */

(function () {
  'use strict';

  /* -------------------------------------------------------------------------
     UTILITÁRIOS
     ----------------------------------------------------------------------- */

  /**
   * Dispara evento no dataLayer do Google Tag Manager.
   * @param {string} eventName — nome do evento GA4
   * @param {object} params — parâmetros adicionais
   */
  function pushEvent(eventName, params) {
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push(
      Object.assign({ event: eventName }, params || {})
    );
  }

  /* -------------------------------------------------------------------------
     MENU MOBILE
     ----------------------------------------------------------------------- */
  function initMobileMenu() {
    var hamburger = document.querySelector('.hamburger');
    var nav = document.querySelector('.header-nav');

    if (!hamburger || !nav) return;

    hamburger.addEventListener('click', function () {
      var isOpen = hamburger.classList.toggle('is-open');
      nav.classList.toggle('is-open', isOpen);
      hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      hamburger.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
    });

    // Fechar ao clicar em link do nav
    nav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        hamburger.classList.remove('is-open');
        nav.classList.remove('is-open');
        hamburger.setAttribute('aria-expanded', 'false');
        hamburger.setAttribute('aria-label', 'Abrir menu');
      });
    });
  }

  /* -------------------------------------------------------------------------
     SCROLL SUAVE PARA ÂNCORAS INTERNAS
     ----------------------------------------------------------------------- */
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
      anchor.addEventListener('click', function (e) {
        var target = document.querySelector(this.getAttribute('href'));
        if (target) {
          e.preventDefault();
          var headerH = document.querySelector('.site-header')
            ? document.querySelector('.site-header').offsetHeight
            : 0;
          var top = target.getBoundingClientRect().top + window.scrollY - headerH - 16;
          window.scrollTo({ top: top, behavior: 'smooth' });
        }
      });
    });
  }

  /* -------------------------------------------------------------------------
     SCROLL 75% — evento de engajamento
     ----------------------------------------------------------------------- */
  function initScroll75() {
    var fired = false;

    function check() {
      if (fired) return;
      var scrolled = window.scrollY + window.innerHeight;
      var total = document.body.scrollHeight;
      if (scrolled / total >= 0.75) {
        fired = true;
        pushEvent('scroll_75', {
          page_path: window.location.pathname
        });
        window.removeEventListener('scroll', check);
      }
    }

    window.addEventListener('scroll', check, { passive: true });
  }

  /* -------------------------------------------------------------------------
     CTA CLICKS — lead_cta_click
     ----------------------------------------------------------------------- */
  function initCtaEvents() {
    document.querySelectorAll('[data-cta]').forEach(function (el) {
      el.addEventListener('click', function () {
        pushEvent('lead_cta_click', {
          cta_location: el.getAttribute('data-cta') || 'unknown',
          cta_text: el.textContent.trim().substring(0, 100),
          page_path: window.location.pathname
        });
      });
    });
  }

  /* -------------------------------------------------------------------------
     WHATSAPP CLICK
     ----------------------------------------------------------------------- */
  function initWaEvent() {
    var waBtn = document.querySelector('.wa-float');
    if (!waBtn) return;

    waBtn.addEventListener('click', function () {
      pushEvent('whatsapp_click', {
        page_path: window.location.pathname
      });
    });
  }

  /* -------------------------------------------------------------------------
     PHONE CLICK
     ----------------------------------------------------------------------- */
  function initPhoneEvent() {
    document.querySelectorAll('a[href^="tel:"]').forEach(function (el) {
      el.addEventListener('click', function () {
        pushEvent('phone_click', {
          phone_number: el.getAttribute('href').replace('tel:', ''),
          page_path: window.location.pathname
        });
      });
    });
  }

  /* -------------------------------------------------------------------------
     INICIALIZAÇÃO
     ----------------------------------------------------------------------- */
  function init() {
    initMobileMenu();
    initSmoothScroll();
    initScroll75();
    initCtaEvents();
    initWaEvent();
    initPhoneEvent();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
