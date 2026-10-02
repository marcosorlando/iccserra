# Spec visual — Materiais - ICC SERRA

> URL: https://localhost/iccserra/materiais · capturado em 2026-09-17 · larguras: 390, 1440px
> Extração automática (za-web-design). **Leia como evidência, não como decisão.** Valide nos prints em `shots/`.

## 1. Leitura rápida
- **Stack detectada:** jquery, workcontrol
- **Tokens já existentes no :root:** 0 (1 folhas cross-origin não lidas)
- **Cor primária provável:** `#06173a` com texto `#ffffff` → contraste **17.63:1** 
- **Tipografia:** display `Open Sans` · corpo `Open Sans` · razão média da escala ≈ **1.200**
- **Grade de espaço:** sem base clara (16% múltiplos de 8 · 56% de 4)
- **Container:** 1123px · **Header:** 109px, static, bg #e6e6e6
- **Overflow horizontal no mobile (390px):** não

## 2. Cor
### Fundos (peso = área)
| cor | peso |
|---|---|
| `#ffffff` | 2934.2 |
| `#e6e6e6` | 2353.1 |
| `#eeeeee` | 1305.3 |
| `#152024` | 1296 |
| `#303030` | 515.7 |
| `#333333` | 252.8 |
| `#fbfbfb` | 67.8 |
| `#d12421` | 61.3 |
| `#fc0000` | 28.6 |
| `#008068` | 3.1 |

### Texto (peso = caracteres)
| cor | peso |
|---|---|
| `#555555` | 1027 |
| `#006699` | 621 |
| `#b81f1c` | 426 |
| `#fbfbfb` | 421 |
| `#cccccc` | 409 |
| `#000000` | 226 |
| `#152024` | 195 |
| `#ffffff` | 157 |

### Bordas
| cor | peso |
|---|---|
| `#dedede` | 636.8 |
| `#eeeeee` | 590.6 |
| `#888888` | 271.5 |
| `#d12421` | 245.8 |
| `#333333` | 112.3 |
| `#84171d` | 34.2 |

## 3. Tipografia
- Famílias (por caracteres): `Open Sans` (3853)
- Fontes carregadas: Open Sans 300 normal; Open Sans 400 normal; Open Sans 600 normal; Open Sans 700 normal; icomoon 400 normal
- Google Fonts: https://fonts.googleapis.com/css?family=Open%20Sans:300,400,600,700
- Pesos: 400, 700, 300, 600, 900 · line-heights: normal, 1.50, 1.00, 1.40, 1.20, 0.17

| tag | família · tamanho · peso · line-height · tracking · transform |
|---|---|
| a | `Open Sans | 16px | 400 | normal | normal | none` |
| h1 | `Open Sans | 32px | 300 | 1.40 | normal | none` |
| p | `Open Sans | 16px | 400 | 1.50 | normal | none` |
| h4 | `Open Sans | 16px | 700 | normal | normal | none` |
| h2 | `Open Sans | 19.2px | 700 | normal | normal | uppercase` |
| h3 | `Open Sans | 18.72px | 700 | normal | normal | none` |

Escala de tamanhos: 13px → 16px → 19px → 22px → 24px → 32px

## 4. Espaço, forma, profundidade, movimento
- Espaçamentos mais usados: 12px, 20px, 10px, 8px, 5px, 11.0625px, 15px, 30px, 11.2188px, 6px, 100px, 25px
- Gaps (flex/grid): —
- Raios: 12px×25, 20px×8, 5px×5, 999px×1
- Sombras:
  - `rgba(0, 0, 0, 0.2) 0px 0px 5px 1px inset` ×8
  - `rgba(0, 0, 0, 0.3) 0px 2px 6px 0px, rgba(0, 0, 0, 0.2) 0px 6px 16px 0px` ×1
- Transições: 0.3s · easing: ease
- Breakpoints (media queries): max 768px, max 544px, max 1280px, min 544px, min 768px, max 1024px, max 960px, min 1024px, max 1056px, max 928px, max 375px
- z-index em uso: 97, 1000, 9999

## 5. Componentes
### Botões (assinaturas mais comuns)
- ×5 — `bg #06173a · color #ffffff · radius 0px · pad 6px 10px · fs 19.2px · fw 600 · tt uppercase · border 2px #06173a · h 16px`
- ×1 — `bg #00000000 · color #84171d · radius 0px · pad 8px 8px · fs 19.52px · fw 400 · tt uppercase · border 0px #84171d · h 43px`
- ×1 — `bg #f45563 · color #ffffff · radius 0px · pad 8px 8px · fs 17.6px · fw 400 · tt none · border 0px #ffffff · h 40px`
- ×1 — `bg #d12421 · color #ffffff · radius 0px · pad 13.25px 53.0312px · fs 22.4px · fw 700 · tt uppercase · border 0px #ffffff · h 58px`

### Campos de formulário
- ×1 — `bg #ffffff · border 0px #000000 · radius 0px · pad 10px 10px · h 38px · fs 13.3333px`

### Links no texto
- `#006699 · none · fw 400`
- `#cccccc · none · fw 300`
- `#888888 · none · fw 600`
- `#fbfbfb · none · fw 300`

## 6. Estrutura e acessibilidade (alertas rápidos)
- h1 na página: **10** · landmarks: header×9, nav×2, main×1, aside×1, footer×1
- imagens sem alt: **0** · botões sem nome: **1** · campos sem label: **1**
- textos com contraste abaixo de AA: **15**
  - `p.site_title` "Banco do Povo" #000000 sobre #152024 = 1.26:1 (16px)
  - `a` "Microcrédito Produtivo" #888888 sobre #ffffff = 3.54:1 (14px)
  - `a` "Troca de cheques" #888888 sobre #ffffff = 3.54:1 (14px)
  - `a` "Crédito Agrícola" #888888 sobre #ffffff = 3.54:1 (14px)
  - `a` "Crédito para Reforma ou ampliação" #888888 sobre #ffffff = 3.54:1 (14px)
  - `a` "Microcrédito Mulher" #888888 sobre #ffffff = 3.54:1 (14px)
  - `a.btn_docs` "BAIXAR" #ffffff sobre #fc0000 = 4.09:1 (16px)
  - `a.btn_docs` "BAIXAR" #ffffff sobre #fc0000 = 4.09:1 (16px)
  - `a.btn_docs` "BAIXAR" #ffffff sobre #fc0000 = 4.09:1 (16px)
  - `a.btn_docs` "BAIXAR" #ffffff sobre #fc0000 = 4.09:1 (16px)

<details><summary>Outline de headings</summary>

- h1: ICC SERRA - Banco do Povo
- h1: Biblioteca do Microcrédito ICC SERRA
- h4: Metas: O Segredo das pessoas bem sucedidas
- h4: Microcrédito: Como realizar seu sonho
- h4: Dúvidas sobre o microcrédito?
- h4: Microempreendedor individual: Entenda o que é MEI
- h4: Cartilha do Microempreendedor Individual - MEI
- h4: 6 Dicas Essenciais Para Quem Trabalha em Casa
- h4: Manual do Vendedor Vencedor
- h4: Planilha de Controle Financeiro
- h4: Como elaborar um Plano de Negócio
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
- h2: POSTOS DE ATENDIMENTO BANCO DO POVO
INSTITUIÇÃO COMUNITÁRIA DE CRÉDITO
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
</details>


## 7. Uso ético da referência
Tokens (cor, escala, ritmo, raio, sombra) e **padrões** de layout são inspiração legítima.
**Não copiar:** logotipo, fotos, ilustrações, ícones proprietários, textos e marca registrada. Ver `references/extracao-referencia.md`.
