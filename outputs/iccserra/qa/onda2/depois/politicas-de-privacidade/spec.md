# Spec visual — Políticas de Privacidade - ICC SERRA

> URL: https://localhost/iccserra/politicas-de-privacidade · capturado em 2026-09-17 · larguras: 390, 1440px
> Extração automática (za-web-design). **Leia como evidência, não como decisão.** Valide nos prints em `shots/`.

## 1. Leitura rápida
- **Stack detectada:** jquery, workcontrol
- **Tokens já existentes no :root:** 0 (2 folhas cross-origin não lidas)
- **Cor primária provável:** `#06173a` com texto `#ffffff` → contraste **17.63:1** 
- **Tipografia:** display `Open Sans` · corpo `Open Sans` · razão média da escala ≈ **1.200**
- **Grade de espaço:** sem base clara (10% múltiplos de 8 · 54% de 4)
- **Container:** 1123px · **Header:** 100px, static, bg #ffffff
- **Overflow horizontal no mobile (390px):** não

## 2. Cor
### Fundos (peso = área)
| cor | peso |
|---|---|
| `#ffffff` | 1929.1 |
| `#152024` | 1801.7 |
| `#f4f4f1` | 1296 |
| `#eeeeee` | 1112.9 |
| `#e6e6e6` | 973.5 |
| `#303030` | 113.8 |
| `#d12421` | 60.7 |
| `#fc0000` | 37.7 |
| `#008068` | 3.1 |
| `#06173a` | 1.9 |

### Texto (peso = caracteres)
| cor | peso |
|---|---|
| `#666666` | 1412 |
| `#555555` | 1027 |
| `#006699` | 645 |
| `#b81f1c` | 426 |
| `#cccccc` | 409 |
| `#152024` | 195 |
| `#ffffff` | 166 |
| `#333333` | 119 |

### Bordas
| cor | peso |
|---|---|
| `#0000001a` | 636.8 |
| `#ffffff29` | 373.3 |
| `#d12421` | 288 |
| `#333333` | 112.3 |
| `#06173a` | 12 |

### Gradientes
- `radial-gradient(circle at 100% 50%, rgba(209, 36, 33, 0.14), rgba(0, 0, 0, 0) 60%), none`

## 3. Tipografia
- Famílias (por caracteres): `Open Sans` (4631), `Archivo Black` (45)
- Fontes carregadas: Open Sans 300 normal; Open Sans 400 normal; Open Sans 600 normal; Open Sans 700 normal; Archivo Black 400 normal; icomoon 400 normal; icc-icons 400 normal
- Google Fonts: https://fonts.googleapis.com/css?family=Open%20Sans:300,400,600,700 https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap
- Pesos: 300, 400, 700, 800, 900, 600 · line-heights: normal, 1.65, 1.50, 1.15, 1.20

| tag | família · tamanho · peso · line-height · tracking · transform |
|---|---|
| a | `Open Sans | 16px | 400 | normal | normal | none` |
| h1 | `Open Sans | 19.2px | 700 | normal | normal | none` |
| p | `Open Sans | 16.8px | 300 | 1.65 | 0.672px | none` |
| h4 | `Open Sans | 12.8px | 300 | normal | normal | none` |
| h2 | `Open Sans | 19.2px | 700 | normal | normal | uppercase` |
| h3 | `Open Sans | 18.72px | 700 | normal | normal | none` |

Escala de tamanhos: 13px → 16px → 19px → 22px → 24px → 32px

## 4. Espaço, forma, profundidade, movimento
- Espaçamentos mais usados: 12px, 10px, 20px, 8px, 11.0625px, 15px, 5px, 30px, 11.2188px, 6px, 40px, 11.0312px
- Gaps (flex/grid): 9.6px
- Raios: 4px×38, 999px×11
- Sombras:
  - `rgba(0, 0, 0, 0.2) 0px 0px 5px 1px inset` ×8
  - `rgba(209, 36, 33, 0.22) 0px 0px 38.4px 0px` ×2
  - `rgba(0, 0, 0, 0.1) 0px 1px 0px 0px` ×1
  - `rgba(0, 0, 0, 0.3) 0px 2px 6px 0px, rgba(0, 0, 0, 0.2) 0px 6px 16px 0px` ×1
- Transições: 0.15s, 0.3s · easing: linear, cubic-bezier(0.16
- Breakpoints (media queries): max 768px, max 544px, max 1280px, min 544px, min 768px, max 1024px, max 960px, min 1024px, max 1056px, max 928px, max 375px
- z-index em uso: 97, 1000, 9999

## 5. Componentes
### Botões (assinaturas mais comuns)
- ×5 — `bg #06173a · color #ffffff · radius 0px · pad 6px 10px · fs 19.2px · fw 600 · tt uppercase · border 2px #06173a · h 16px`
- ×1 — `bg #00000000 · color #84171d · radius 0px · pad 8px 8px · fs 19.52px · fw 400 · tt uppercase · border 0px #84171d · h 44px`
- ×1 — `bg #d12421 · color #ffffff · radius 4px · pad 13.25px 53.0312px · fs 22.4px · fw 800 · tt uppercase · border 1px #00000000 · h 55px`

### Campos de formulário
- —

### Links no texto
- `#006699 · none · fw 400`
- `#cccccc · none · fw 300`
- `#fbfbfb · none · fw 300`
- `#333333 · none · fw 400`

## 6. Estrutura e acessibilidade (alertas rápidos)
- h1 na página: **3** · landmarks: header×2, nav×2, main×1, footer×1
- imagens sem alt: **0** · botões sem nome: **0** · campos sem label: **0**
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
- h1: Políticas de Privacidade
- h1: POLÍTICA DE PRIVACIDADE:
- h4: ICC SERRA BANCO DO POVO.
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
- h3: FELIZ
- h3: GRAMADO
- h3: CANELA
- h2: ÚLTIMAS DO SITE!
- h3: Fluxo de Caixa apertado? Veja como usar Capital de Giro com segurança
- h3: Banco do Povo inspira Candiota com apoio real ao pequeno empreendedor
- h3: Microcrédito Mulher
- h3: O QUE É MAIS VANTAJOSO CONSTRUIR OU REFORMAR?
- h2: MAIS ACESSADOS!
- h3: Microcrédito Produtivo
- h3: A importância do microcrédito para o desenvolvimento econômico e socia
- h3: Troca de cheques
</details>


## 7. Uso ético da referência
Tokens (cor, escala, ritmo, raio, sombra) e **padrões** de layout são inspiração legítima.
**Não copiar:** logotipo, fotos, ilustrações, ícones proprietários, textos e marca registrada. Ver `references/extracao-referencia.md`.
