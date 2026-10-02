# Spec visual — Institucional - ICC SERRA

> URL: https://localhost/iccserra/institucional · capturado em 2026-09-17 · larguras: 390, 1440px
> Extração automática (za-web-design). **Leia como evidência, não como decisão.** Valide nos prints em `shots/`.

## 1. Leitura rápida
- **Stack detectada:** jquery, workcontrol
- **Tokens já existentes no :root:** 0 (2 folhas cross-origin não lidas)
- **Cor primária provável:** `#06173a` com texto `#ffffff` → contraste **17.63:1** 
- **Tipografia:** display `Archivo Black` · corpo `Open Sans` · razão média da escala ≈ **1.164**
- **Grade de espaço:** sem base clara (11% múltiplos de 8 · 49% de 4)
- **Container:** 1123px · **Header:** 100px, static, bg #ffffff
- **Overflow horizontal no mobile (390px):** não

## 2. Cor
### Fundos (peso = área)
| cor | peso |
|---|---|
| `#f4f4f1` | 2507 |
| `#152024` | 2135.5 |
| `#ffffff` | 1703.7 |
| `#e6e6e6` | 973.5 |
| `#cccccc` | 319.4 |
| `#d12421` | 77.9 |
| `#fc0000` | 75.4 |
| `#008068` | 3.1 |
| `#06173a` | 1.9 |

### Texto (peso = caracteres)
| cor | peso |
|---|---|
| `#666666` | 2481 |
| `#555555` | 1555 |
| `#000000` | 815 |
| `#006699` | 621 |
| `#152024` | 504 |
| `#b81f1c` | 426 |
| `#cccccc` | 409 |
| `#ffffff` | 218 |

### Bordas
| cor | peso |
|---|---|
| `#0000001a` | 766 |
| `#ffffff29` | 373.3 |
| `#d12421` | 144 |
| `#333333` | 112.3 |
| `#00000047` | 71.4 |
| `#06173a` | 12 |

### Gradientes
- `radial-gradient(circle at 100% 50%, rgba(209, 36, 33, 0.14), rgba(0, 0, 0, 0) 60%), none`

## 3. Tipografia
- Famílias (por caracteres): `Open Sans` (7219), `Archivo Black` (321)
- Fontes carregadas: Open Sans 300 normal; Open Sans 400 normal; Open Sans 600 normal; Open Sans 700 normal; Archivo Black 400 normal; icomoon 400 normal; icc-icons 400 normal
- Google Fonts: https://fonts.googleapis.com/css?family=Open%20Sans:300,400,600,700 https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap
- Pesos: 400, 300, 700, 600, 800, 900 · line-heights: normal, 1.65, 1.20, 1.15, 1.50, 1.40

| tag | família · tamanho · peso · line-height · tracking · transform |
|---|---|
| a | `Open Sans | 16px | 400 | normal | normal | none` |
| h1 | `Archivo Black | 32px | 400 | 1.15 | -0.64px | none` |
| p | `Open Sans | 16.8px | 300 | 1.65 | 0.672px | none` |
| h2 | `Open Sans | 24px | 700 | normal | normal | none` |
| h3 | `Open Sans | 18.72px | 700 | normal | normal | none` |

Escala de tamanhos: 13px → 15px → 17px → 19px → 22px → 24px → 32px

## 4. Espaço, forma, profundidade, movimento
- Espaçamentos mais usados: 10px, 12px, 20px, 8px, 11.0625px, 15px, 5px, 11.2188px, 40px, 50px, 6px, 30px
- Gaps (flex/grid): 9.6px
- Raios: 4px×68, 999px×11
- Sombras:
  - `rgba(0, 0, 0, 0.2) 0px 0px 5px 1px inset` ×16
  - `rgba(209, 36, 33, 0.22) 0px 0px 38.4px 0px` ×3
  - `rgba(0, 0, 0, 0.1) 0px 1px 0px 0px` ×1
  - `rgb(209, 36, 33) 0px -3px 0px 0px inset` ×1
  - `rgba(0, 0, 0, 0.3) 0px 2px 6px 0px, rgba(0, 0, 0, 0.2) 0px 6px 16px 0px` ×1
- Transições: 0.15s, 0.3s, 0.5s · easing: cubic-bezier(0.16, linear
- Breakpoints (media queries): max 768px, max 544px, max 1280px, min 544px, min 768px, max 1024px, max 960px, min 1024px, max 1056px, max 928px, max 375px
- z-index em uso: 97, 1000, 9999

## 5. Componentes
### Botões (assinaturas mais comuns)
- ×5 — `bg #06173a · color #ffffff · radius 0px · pad 6px 10px · fs 19.2px · fw 600 · tt uppercase · border 2px #06173a · h 16px`
- ×2 — `bg #d12421 · color #ffffff · radius 4px · pad 13.25px 53.0312px · fs 22.4px · fw 800 · tt uppercase · border 1px #00000000 · h 55px`
- ×1 — `bg #00000000 · color #84171d · radius 0px · pad 8px 8px · fs 19.52px · fw 400 · tt uppercase · border 0px #84171d · h 44px`

### Campos de formulário
- —

### Links no texto
- `#006699 · none · fw 400`
- `#cccccc · none · fw 300`
- `#555555 · none · fw 400`
- `#ffffff · none · fw 700`

## 6. Estrutura e acessibilidade (alertas rápidos)
- h1 na página: **10** · landmarks: header×7, nav×2, main×1, aside×1, footer×1
- imagens sem alt: **0** · botões sem nome: **0** · campos sem label: **0**
- textos com contraste abaixo de AA: **17**
  - `p.site_title` "Banco do Povo" #000000 sobre #152024 = 1.26:1 (16px)
  - `a.btn_docs` "BAIXAR" #ffffff sobre #fc0000 = 4.09:1 (16px)
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
- h1: Instituição Comunitária de Crédito ICC SERRA - BANCO DO POVO
- h2: Nosso negócio
- h2: Nosso perfil de clientes
- h2: Nossas vantagens
- h2: Acesse nosso simulador, e realize uma simulação de crédito agora mesmo
- h1: Conheça nossa cultura
- h3: MISSÃO:
- h3: VISÃO:
- h3: VALORES
- h2: Alguns de nossos Parceiros
- h1: Siga o ICC SERRA - BANCO DO POVO no Facebook
- h1: Linhas de Crédito. Tudo que você precisa está ao seu alcance.
- h1: MICROCRÉDITO MULHER
- h1: MICROCRÉDITO PRODUTIVO
- h1: CRÉDITO PARA REFORMA OU AMPLIAÇÃO
- h1: CRÉDITO AGRÍCOLA
- h1: TROCA DE CHEQUES
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
</details>


## 7. Uso ético da referência
Tokens (cor, escala, ritmo, raio, sombra) e **padrões** de layout são inspiração legítima.
**Não copiar:** logotipo, fotos, ilustrações, ícones proprietários, textos e marca registrada. Ver `references/extracao-referencia.md`.
