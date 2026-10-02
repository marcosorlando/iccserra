# Spec visual — Materiais - ICC SERRA

> URL: https://localhost/iccserra/materiais · capturado em 2026-09-17 · larguras: 390, 1440px
> Extração automática (za-web-design). **Leia como evidência, não como decisão.** Valide nos prints em `shots/`.

## 1. Leitura rápida
- **Stack detectada:** jquery, workcontrol
- **Tokens já existentes no :root:** 0 (2 folhas cross-origin não lidas)
- **Cor primária provável:** `#06173a` com texto `#ffffff` → contraste **17.63:1** 
- **Tipografia:** display `Archivo Black` · corpo `Open Sans` · razão média da escala ≈ **1.200**
- **Grade de espaço:** sem base clara (15% múltiplos de 8 · 57% de 4)
- **Container:** 1123px · **Header:** 100px, static, bg #ffffff
- **Overflow horizontal no mobile (390px):** não

## 2. Cor
### Fundos (peso = área)
| cor | peso |
|---|---|
| `#ffffff` | 4097.1 |
| `#152024` | 1801.7 |
| `#eeeeee` | 1296 |
| `#f4f4f1` | 1296 |
| `#e6e6e6` | 973.5 |
| `#333333` | 252.8 |
| `#fbfbfb` | 67.8 |
| `#d12421` | 60.7 |
| `#fc0000` | 37.7 |
| `#008068` | 3.1 |

### Texto (peso = caracteres)
| cor | peso |
|---|---|
| `#555555` | 1133 |
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
| `#0000001a` | 728.9 |
| `#eeeeee` | 589.4 |
| `#ffffff29` | 373.3 |
| `#d12421` | 144 |
| `#333333` | 112.3 |
| `#cccccc` | 26.5 |

### Gradientes
- `radial-gradient(circle at 100% 50%, rgba(209, 36, 33, 0.14), rgba(0, 0, 0, 0) 60%), none`
- `linear-gradient(90deg, rgb(209, 36, 33), rgb(21, 32, 36))`

## 3. Tipografia
- Famílias (por caracteres): `Open Sans` (3772), `Archivo Black` (81)
- Fontes carregadas: Open Sans 300 normal; Open Sans 400 normal; Open Sans 600 normal; Open Sans 700 normal; Archivo Black 400 normal; icomoon 400 normal; icc-icons 400 normal
- Google Fonts: https://fonts.googleapis.com/css?family=Open%20Sans:300,400,600,700 https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap
- Pesos: 400, 700, 300, 600, 900, 800 · line-heights: normal, 1.50, 1.20, 1.00, 1.15, 1.40

| tag | família · tamanho · peso · line-height · tracking · transform |
|---|---|
| a | `Open Sans | 16px | 400 | normal | normal | none` |
| h1 | `Archivo Black | 32px | 400 | 1.40 | -0.64px | none` |
| p | `Open Sans | 16px | 400 | 1.50 | normal | none` |
| h4 | `Open Sans | 16px | 700 | normal | normal | none` |
| h2 | `Open Sans | 19.2px | 700 | normal | normal | uppercase` |
| h3 | `Open Sans | 18.72px | 700 | normal | normal | none` |

Escala de tamanhos: 13px → 16px → 19px → 22px → 24px → 32px

## 4. Espaço, forma, profundidade, movimento
- Espaçamentos mais usados: 12px, 20px, 10px, 8px, 11.0625px, 15px, 5px, 30px, 11.2188px, 6px, 25px, 11.0312px
- Gaps (flex/grid): 9.6px, 8px
- Raios: 4px×53, 999px×11, 5px×5
- Sombras:
  - `rgba(0, 0, 0, 0.2) 0px 0px 5px 1px inset` ×8
  - `rgba(209, 36, 33, 0.22) 0px 0px 38.4px 0px` ×3
  - `rgba(0, 0, 0, 0.1) 0px 1px 0px 0px` ×1
  - `rgba(0, 0, 0, 0.3) 0px 2px 6px 0px, rgba(0, 0, 0, 0.2) 0px 6px 16px 0px` ×1
- Transições: 0.3s, 0.15s · easing: cubic-bezier(0.16, linear, ease
- Breakpoints (media queries): max 768px, max 544px, max 1280px, min 544px, min 768px, max 1024px, max 960px, min 1024px, max 1056px, max 928px, max 375px
- z-index em uso: 97, 1000, 9999

## 5. Componentes
### Botões (assinaturas mais comuns)
- ×5 — `bg #06173a · color #ffffff · radius 0px · pad 6px 10px · fs 19.2px · fw 600 · tt uppercase · border 2px #06173a · h 16px`
- ×1 — `bg #00000000 · color #84171d · radius 0px · pad 8px 8px · fs 19.52px · fw 400 · tt uppercase · border 0px #84171d · h 44px`
- ×1 — `bg #00000000 · color #ffffff · radius 4px · pad 8px 8px · fs 17.6px · fw 800 · tt uppercase · border 1px #00000000 · h 46px`
- ×1 — `bg #d12421 · color #ffffff · radius 4px · pad 13.25px 53.0312px · fs 22.4px · fw 800 · tt uppercase · border 1px #00000000 · h 55px`

### Campos de formulário
- ×1 — `bg #ffffff · border 1px #858585 · radius 4px · pad 11.2px 14.4px · h 46px · fs 16px`

### Links no texto
- `#006699 · none · fw 400`
- `#cccccc · none · fw 300`
- `#555555 · none · fw 600`
- `#fbfbfb · none · fw 300`

## 6. Estrutura e acessibilidade (alertas rápidos)
- h1 na página: **10** · landmarks: header×9, nav×2, main×1, aside×1, footer×1
- imagens sem alt: **0** · botões sem nome: **1** · campos sem label: **1**
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
</details>


## 7. Uso ético da referência
Tokens (cor, escala, ritmo, raio, sombra) e **padrões** de layout são inspiração legítima.
**Não copiar:** logotipo, fotos, ilustrações, ícones proprietários, textos e marca registrada. Ver `references/extracao-referencia.md`.
