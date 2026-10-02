# Spec visual — Simulador - ICC SERRA

> URL: https://localhost/iccserra/simulador · capturado em 2026-09-17 · larguras: 390, 1440px
> Extração automática (za-web-design). **Leia como evidência, não como decisão.** Valide nos prints em `shots/`.

## 1. Leitura rápida
- **Stack detectada:** jquery, workcontrol
- **Tokens já existentes no :root:** 0 (2 folhas cross-origin não lidas)
- **Cor primária provável:** `#06173a` com texto `#ffffff` → contraste **17.63:1** 
- **Tipografia:** display `Archivo Black` · corpo `Open Sans` · razão média da escala ≈ **1.215**
- **Grade de espaço:** sem base clara (8% múltiplos de 8 · 48% de 4)
- **Container:** 1123px · **Header:** 100px, static, bg #ffffff
- **Overflow horizontal no mobile (390px):** não

## 2. Cor
### Fundos (peso = área)
| cor | peso |
|---|---|
| `#ffffff` | 3980.3 |
| `#152024` | 2426.2 |
| `#f4f4f1` | 1906.3 |
| `#eeeeee` | 1296 |
| `#e6e6e6` | 973.5 |
| `#84171d` | 624.5 |
| `#3f0425` | 610.3 |
| `#fbfbfb` | 67.8 |
| `#d12421` | 60.7 |
| `#fc0000` | 37.7 |

### Texto (peso = caracteres)
| cor | peso |
|---|---|
| `#ffffff` | 1910 |
| `#555555` | 1461 |
| `#006699` | 645 |
| `#84171d` | 514 |
| `#cccccc` | 478 |
| `#b81f1c` | 426 |
| `#152024` | 205 |
| `#000000` | 157 |

### Bordas
| cor | peso |
|---|---|
| `#0000001a` | 801.5 |
| `#858585` | 410.5 |
| `#ffffff29` | 373.3 |
| `#f1f1f1` | 353.2 |
| `#eeeeee` | 235.6 |
| `#d12421` | 144 |

### Gradientes
- `radial-gradient(circle at 100% 50%, rgba(209, 36, 33, 0.14), rgba(0, 0, 0, 0) 60%), none`
- `linear-gradient(90deg, rgb(209, 36, 33), rgb(21, 32, 36))`

## 3. Tipografia
- Famílias (por caracteres): `Open Sans` (6230), `Archivo Black` (163)
- Fontes carregadas: Open Sans 300 normal; Open Sans 400 normal; Open Sans 600 normal; Open Sans 700 normal; Archivo Black 400 normal; icomoon 400 normal; icc-icons 400 normal
- Google Fonts: https://fonts.googleapis.com/css?family=Open%20Sans:300,400,600,700 https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap
- Pesos: 400, 700, 300, 600, 800, 900 · line-heights: normal, 1.20, 1.15, 1.50, 1.00, 1.40

| tag | família · tamanho · peso · line-height · tracking · transform |
|---|---|
| a | `Open Sans | 16px | 400 | normal | normal | none` |
| h1 | `Archivo Black | 32px | 400 | 1.40 | -0.64px | none` |
| p | `Open Sans | 12.8px | 400 | normal | normal | none` |
| small | `Open Sans | 12px | 700 | normal | 0.864px | uppercase` |
| button | `Open Sans | 19.2px | 800 | 1.20 | 1.152px | uppercase` |
| h5 | `Open Sans | 15.2px | 300 | normal | normal | none` |
| h6 | `Open Sans | 15.2px | 700 | normal | normal | none` |
| h3 | `Open Sans | 18.72px | 700 | normal | normal | none` |
| h2 | `Open Sans | 19.2px | 700 | normal | normal | uppercase` |

Escala de tamanhos: 10px → 13px → 16px → 19px → 22px → 26px → 32px

## 4. Espaço, forma, profundidade, movimento
- Espaçamentos mais usados: 20px, 12px, 10px, 8px, 30px, 11.0625px, 15px, 6.125px, 11.2px, 14.4px, 5px, 50px
- Gaps (flex/grid): 9.6px, 8px
- Raios: 4px×85, 999px×11, 5px×5
- Sombras:
  - `rgba(0, 0, 0, 0.2) 0px 0px 5px 1px inset` ×8
  - `rgba(0, 0, 0, 0.16) 0px 24px 64px -19.2px` ×7
  - `rgba(209, 36, 33, 0.22) 0px 0px 38.4px 0px` ×4
  - `rgba(0, 0, 0, 0.1) 0px 1px 0px 0px` ×1
  - `rgb(209, 36, 33) 0px -3px 0px 0px inset` ×1
  - `rgba(0, 0, 0, 0.3) 0px 2px 6px 0px, rgba(0, 0, 0, 0.2) 0px 6px 16px 0px` ×1
- Transições: 0.3s, 0.15s · easing: cubic-bezier(0.16, linear, ease
- Breakpoints (media queries): max 768px, max 544px, max 1280px, min 544px, min 768px, max 1024px, max 960px, min 1024px, max 1056px, max 928px, max 375px
- z-index em uso: 97, 1000, 9999

## 5. Componentes
### Botões (assinaturas mais comuns)
- ×5 — `bg #06173a · color #ffffff · radius 0px · pad 6px 10px · fs 19.2px · fw 600 · tt uppercase · border 2px #06173a · h 16px`
- ×1 — `bg #00000000 · color #84171d · radius 0px · pad 8px 8px · fs 19.52px · fw 400 · tt uppercase · border 0px #84171d · h 44px`
- ×1 — `bg #00000000 · color #ffffff · radius 4px · pad 15px 30px · fs 19.2px · fw 800 · tt uppercase · border 1px #00000000 · h 57px`
- ×1 — `bg #00000000 · color #ffffff · radius 4px · pad 8px 8px · fs 17.6px · fw 800 · tt uppercase · border 1px #00000000 · h 46px`
- ×1 — `bg #d12421 · color #ffffff · radius 4px · pad 13.25px 53.0312px · fs 22.4px · fw 800 · tt uppercase · border 1px #00000000 · h 55px`

### Campos de formulário
- ×6 — `bg #ffffff · border 1px #858585 · radius 4px · pad 11.2px 14.4px · h 46px · fs 16px`
- ×5 — `bg #ffffff · border 1px #858585 · radius 4px · pad 11.2px 14.4px · h 44px · fs 16px`
- ×1 — `bg #ffffff · border 1px #858585 · radius 4px · pad 11.2px 14.4px · h 50px · fs 20.8px`

### Links no texto
- `#006699 · none · fw 400`
- `#cccccc · none · fw 300`
- `#555555 · none · fw 600`
- `#fbfbfb · none · fw 300`

## 6. Estrutura e acessibilidade (alertas rápidos)
- h1 na página: **10** · landmarks: header×13, nav×2, main×1, aside×1, footer×1
- imagens sem alt: **0** · botões sem nome: **1** · campos sem label: **2**
- textos com contraste abaixo de AA: **9**
  - `p.site_title` "Banco do Povo" #000000 sobre #152024 = 1.26:1 (16px)
  - `a.btn_docs` "BAIXAR" #ffffff sobre #fc0000 = 4.09:1 (16px)
  - `a.btn_docs` "BAIXAR" #ffffff sobre #fc0000 = 4.09:1 (16px)
  - `a.btn_docs` "BAIXAR" #ffffff sobre #fc0000 = 4.09:1 (16px)
  - `a.btn_docs` "BAIXAR" #ffffff sobre #fc0000 = 4.09:1 (16px)
  - `a.btn_docs` "BAIXAR" #ffffff sobre #fc0000 = 4.09:1 (16px)
  - `a.btn_docs` "BAIXAR" #ffffff sobre #fc0000 = 4.09:1 (16px)
  - `a.btn_docs` "BAIXAR" #ffffff sobre #fc0000 = 4.09:1 (16px)
  - `a.btn_docs` "BAIXAR" #ffffff sobre #fc0000 = 4.09:1 (16px)

<details><summary>Outline de headings</summary>

- h1: ICC SERRA - Banco do Povo
- h1: Simulador de Crédito ICC Serra - Banco do Povo
- h5: CASO TENHA ALGUMA DÚVIDA SOBRE O SIMULADOR LIGUE (54) 3217-8787 QUE AJ
- h6: HORÁRIO DE ATENDIMENTO: DE SEGUNDA À SEXTA-FEIRA DAS 9:00H AS 17:00H
- h3: Microcrédito Produtivo
- h3: Microcrédito Mulher
- h3: Reforma e Ampliação
- h3: Crédito Agrícola
- h1: Empréstimo
- h1: LINHAS DE CRÉDITO
- h1: Microcrédito Produtivo
- h1: Troca de cheques
- h1: Crédito Agrícola
- h1: Crédito para Reforma ou ampliação
- h1: Microcrédito Mulher
- h1: ICC SERRA - BANCO DO POVO
NO FACEBOOK
- h2: E-BOOK METAS
- h2: Postos de Atendimento BANCO DO POVO
Instituição Comunitária de Crédito
- h3: CAXIAS DO SUL
- h3: FLORES DA CUNHA
- h3: FARROUPILHA
- h3: BENTO GONÇALVES
- h3: GARIBALDI
- h3: CARLOS BARBOSA
- h3: COTIPORÃ
- h3: SERAFINA CORRÊA
- h3: GUAPORÉ
- h3: NOVA PRATA
- h3: NOVA ARAÇA
- h3: VERANÓPOLIS
- h3: IBIRUBÁ E REGIÃO
- h3: LAJEADO
- h3: ESTRELA
- h3: VALE DO TAQUARI
- h3: CANDIOTA
- h3: CHARQUEADAS
- h3: SAPIRANGA
- h3: CRUZEIRO DO SUL
- h3: SÃO JERÔNIMO
- h3: NOVA PETRÓPOLIS
</details>


## 7. Uso ético da referência
Tokens (cor, escala, ritmo, raio, sombra) e **padrões** de layout são inspiração legítima.
**Não copiar:** logotipo, fotos, ilustrações, ícones proprietários, textos e marca registrada. Ver `references/extracao-referencia.md`.
