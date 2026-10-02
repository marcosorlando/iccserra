# Spec visual — MetroBank - HTML 5 Template Preview

> URL: https://azim.hostlin.com/Metrobank/ · capturado em 2026-09-17 · larguras: 390, 1440px
> Extração automática (za-web-design). **Leia como evidência, não como decisão.** Valide nos prints em `shots/`.

## 1. Leitura rápida
- **Stack detectada:** bootstrap, jquery, owlCarousel
- **Tokens já existentes no :root:** 76 (2 folhas cross-origin não lidas)
- **Cor primária provável:** `#eb292c` com texto `#ffffff` → contraste **4.30:1** ⚠️ abaixo de AA
- **Tipografia:** display `Manrope` · corpo `Inter` · razão média da escala ≈ **1.259**
- **Grade de espaço:** sem base clara (16% múltiplos de 8 · 20% de 4)
- **Container:** 1320px · **Header:** 160px, relative, bg #00000000
- **Overflow horizontal no mobile (390px):** não

## 2. Cor
### Fundos (peso = área)
| cor | peso |
|---|---|
| `#f6f6f6` | 4755 |
| `#ffffff` | 2688.9 |
| `#0a0f20` | 960 |
| `#eb292c` | 658.8 |
| `#212226` | 86.4 |
| `#ffffff26` | 29.7 |
| `#eb292c1a` | 8.1 |
| `#ffffff12` | 8.1 |
| `#ffffff33` | 7.5 |

### Texto (peso = caracteres)
| cor | peso |
|---|---|
| `#676767` | 2784 |
| `#ffffff` | 1067 |
| `#141417` | 651 |
| `#eb292c` | 156 |
| `#222222` | 72 |
| `#ffffff80` | 6 |
| `#0a0f20` | 5 |

### Bordas
| cor | peso |
|---|---|
| `#e6e6e6` | 194 |
| `#ffffff1a` | 144 |
| `#ffffff33` | 60 |
| `#e9e9e9` | 47.3 |
| `#ffffff80` | 43 |
| `#eb292c` | 15.7 |

## 3. Tipografia
- Famílias (por caracteres): `Inter` (3395), `Manrope` (1346)
- Fontes carregadas: Manrope 500 normal; Manrope 600 normal; Manrope 700 normal; Manrope 800 normal; Inter 400 normal; Inter 500 normal; Inter 600 normal; Inter 700 normal; Font Awesome 5 Brands normal normal; Font Awesome 5 Pro 400 normal; Font Awesome 5 Pro 900 normal; icomoon normal normal
- Google Fonts: https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap
- Pesos: 400, 700, 800, 500, 600 · line-heights: 1.75, 2.00, 1.30, 1.25, 1.40, 1.50

| tag | família · tamanho · peso · line-height · tracking · transform |
|---|---|
| button | `Manrope | 16px | 700 | 1.50 | normal | none` |
| a | `Manrope | 16px | 700 | 1.50 | normal | none` |
| li | `Inter | 16px | 400 | 2.00 | normal | none` |
| h2 | `Manrope | 40px | 800 | 1.30 | normal | none` |
| p | `Inter | 16px | 400 | 1.75 | normal | none` |
| h6 | `Manrope | 16px | 700 | 1.38 | normal | uppercase` |
| h3 | `Manrope | 24px | 700 | 1.25 | normal | none` |
| h4 | `Manrope | 20px | 700 | 1.40 | normal | none` |

Escala de tamanhos: 12px → 16px → 18px → 20px → 22px → 24px → 40px → 54px → 70px

## 4. Espaço, forma, profundidade, movimento
- Espaçamentos mais usados: 15px, 30px, 10px, 35px, 40px, 16px, 18px, 20px, 25px, 50px, 37px, 120px
- Gaps (flex/grid): —
- Raios: 50%×38, 30px×3
- Sombras:
  - `rgba(0, 0, 0, 0.1) 0px 0px 10px 0px` ×2
  - `rgba(0, 0, 0, 0.07) 0px 20px 60px 10px` ×1
  - `rgba(0, 0, 0, 0.1) 0px 10px 50px 0px` ×1
- Transições: 0.5s, 0.7s, 8s, 0.3s, 0.2s · easing: ease, ease-in-out, linear
- Breakpoints (media queries): min 1200px, max 767px, max 1200px, max 991px, max 599px, max 499px, min 576px, max 575.98px, max 767.98px, max 991.98px, max 1199.98px, max 1399.98px
- z-index em uso: 1, 5, 2, -1, 99, 999, 9999

## 5. Componentes
### Botões (assinaturas mais comuns)
- ×9 — `bg #eb292c · color #ffffff · radius 0px · pad 18px 35px · fs 16px · fw 700 · tt none · border 0px #ffffff · h 60px`
- ×3 — `bg #ffffff · color #eb292c · radius 0px · pad 13px 40px · fs 16px · fw 700 · tt none · border 0px #eb292c · h 50px`
- ×1 — `bg #eb292c · color #ffffff · radius 50% · pad 0px 0px · fs 12px · fw 700 · tt none · border 0px #ffffff · h 40px`
- ×1 — `bg #eb292c · color #ffffff · radius 50% · pad 0px 0px · fs 20px · fw 400 · tt none · border 0px #ffffff · h 40px`
- ×1 — `bg #00000000 · color #0a0f20 · radius 0px · pad 12px 30px · fs 16px · fw 700 · tt none · border 1px #0a0f20 · h 50px`
- ×1 — `bg #eb292c · color #ffffff · radius 0px · pad 13px 30px · fs 16px · fw 700 · tt none · border 0px #ffffff · h 50px`
- ×1 — `bg #eb292c · color #ffffff · radius 0px · pad 18px 50px · fs 16px · fw 700 · tt none · border 0px #ffffff · h 60px`
- ×1 — `bg #00000000 · color #eb292c · radius 0px · pad 15px 35px · fs 16px · fw 700 · tt none · border 1px #eb292c · h 60px`

### Campos de formulário
- ×4 — `bg #ffffff · border 1px #e6e6e6 · radius 0px · pad 10px 30px · h 60px · fs 16px`
- ×1 — `bg #ffffff26 · border 1px #00000000 · radius 0px · pad 10px 30px · h 60px · fs 16px`
- ×1 — `bg #00000000 · border 1px #ffffff33 · radius 0px · pad 0px 20px · h 55px · fs 16px`

### Links no texto
- `#ffffff · none · fw 400`
- `#141417 · none · fw 600`
- `#eb292c · none · fw 400`
- `#eb292c · none · fw 600`

## 6. Estrutura e acessibilidade (alertas rápidos)
- h1 na página: **0** · landmarks: header×1, nav×1, footer×1
- imagens sem alt: **0** · botões sem nome: **1** · campos sem label: **6**
- textos com contraste abaixo de AA: **20**
  - `h2` "Open our Account Online" #ffffff sobre #ffffff = 1:1 (70px)
  - `p` "This statistic is based on our average p" #ffffff sobre #ffffff = 1:1 (18px)
  - `h2` "The 3rd Generation Private Commercial Ba" #ffffff sobre #ffffff = 1:1 (40px)
  - `h6` "Our Services" #eb292c sobre #f6f6f6 = 3.98:1 (16px)
  - `h6` "Calculate Loan" #eb292c sobre #f6f6f6 = 3.98:1 (16px)
  - `h6` "Mobile App" #eb292c sobre #f6f6f6 = 3.98:1 (16px)
  - `h6` "Testimonials" #eb292c sobre #f6f6f6 = 3.98:1 (16px)
  - `button.rtl` "RTL" #ffffff sobre #eb292c = 4.3:1 (12px)
  - `a` "Home" #eb292c sobre #ffffff = 4.3:1 (18px)
  - `a.theme-btn.btn-one` "Open Account" #ffffff sobre #eb292c = 4.3:1 (16px)

<details><summary>Outline de headings</summary>

- h2: Open our Current Account Online
- h4: Secure International Transaction
- h4: 24/7 Support from the Expert Team
- h4: Lowest Processing Fee than Other Banks
- h4: Less Time in any Loans Approval
- h6: 5 Star Rating Bank
- h2: 40
- h6: Years of Experiense
- h6: ABOUT US
- h2: Financial Guidance for Every Stage of Life.
- h3: Solution Focused
- h3: 99.99% Success
- h6: OUR SERVICES
- h2: Online Banking at Your
Fingertips
- h4: Digital Banking
- h4: Mobile & Web Banking
- h4: Insurance Policies
- h4: Home & Property Loan
- h4: All Bank Account
- h4: Borrowing Accounts
- h4: Private Banking
- h4: Fixed Term Accounts
- h6: CALCULATE LOAN
- h2: Online EMI Calculator
- h2: The 3rd Generation Private Commercial Bank
- h6: MOBILE APP
- h2: Get the Fastest and Most Secure Banking
- h6: TESTIMONIALS
- h2: Love from Our Clients
- h4: Sandra Bullock
- h4: Julien Anthor
- h4: Rolier Demonil
- h4: Sandra Bullock
- h4: Julien Anthor
- h4: Rolier Demonil
- h4: Sandra Bullock
- h4: Julien Anthor
- h4: Rolier Demonil
- h6: LATEST NEWS
- h2: Our Latest Media Update
</details>

<details><summary>Custom properties existentes no :root</summary>

```css
--bs-blue: #0d6efd;
--bs-indigo: #6610f2;
--bs-purple: #6f42c1;
--bs-pink: #d63384;
--bs-red: #dc3545;
--bs-orange: #fd7e14;
--bs-yellow: #ffc107;
--bs-green: #198754;
--bs-teal: #20c997;
--bs-cyan: #0dcaf0;
--bs-black: #000;
--bs-white: #fff;
--bs-gray: #6c757d;
--bs-gray-dark: #343a40;
--bs-gray-100: #f8f9fa;
--bs-gray-200: #e9ecef;
--bs-gray-300: #dee2e6;
--bs-gray-400: #ced4da;
--bs-gray-500: #adb5bd;
--bs-gray-600: #6c757d;
--bs-gray-700: #495057;
--bs-gray-800: #343a40;
--bs-gray-900: #212529;
--bs-primary: #0d6efd;
--bs-secondary: #6c757d;
--bs-success: #198754;
--bs-info: #0dcaf0;
--bs-warning: #ffc107;
--bs-danger: #dc3545;
--bs-light: #f8f9fa;
--bs-dark: #212529;
--bs-primary-rgb: 13, 110, 253;
--bs-secondary-rgb: 108, 117, 125;
--bs-success-rgb: 25, 135, 84;
--bs-info-rgb: 13, 202, 240;
--bs-warning-rgb: 255, 193, 7;
--bs-danger-rgb: 220, 53, 69;
--bs-light-rgb: 248, 249, 250;
--bs-dark-rgb: 33, 37, 41;
--bs-white-rgb: 255, 255, 255;
--bs-black-rgb: 0, 0, 0;
--bs-body-color-rgb: 33, 37, 41;
--bs-body-bg-rgb: 255, 255, 255;
--bs-font-sans-serif: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
--bs-font-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
--bs-gradient: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0));
--bs-body-font-family: var(--bs-font-sans-serif);
--bs-body-font-size: 1rem;
--bs-body-font-weight: 400;
--bs-body-line-height: 1.5;
--bs-body-color: #212529;
--bs-body-bg: #fff;
--bs-border-width: 1px;
--bs-border-style: solid;
--bs-border-color: #dee2e6;
--bs-border-color-translucent: rgba(0, 0, 0, 0.175);
--bs-border-radius: 0.375rem;
--bs-border-radius-sm: 0.25rem;
--bs-border-radius-lg: 0.5rem;
--bs-border-radius-xl: 1rem;
--bs-border-radius-2xl: 2rem;
--bs-border-radius-pill: 50rem;
--bs-link-color: #0d6efd;
--bs-link-hover-color: #0a58ca;
--bs-code-color: #d63384;
--bs-highlight-bg: #fff3cd;
--theme-color: #EB292C;
--secondary-color: #0A0F20;
--pink-color: #FFC0CB;
--violet-color: #7F00FF;
--crimson-color: #DC143C;
--orange-color: #FFA500;
--text-color: #676767;
--title-color: #141417;
--text-font: 'Inter', sans-serif;
--title-font: 'Manrope', sans-serif;
```
</details>

## 7. Uso ético da referência
Tokens (cor, escala, ritmo, raio, sombra) e **padrões** de layout são inspiração legítima.
**Não copiar:** logotipo, fotos, ilustrações, ícones proprietários, textos e marca registrada. Ver `references/extracao-referencia.md`.
