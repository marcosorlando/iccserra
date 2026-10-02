# Spec visual — Aviso Legal - ICC SERRA

> URL: https://localhost/iccserra/aviso-legal · capturado em 2026-09-17 · larguras: 390, 1440px
> Extração automática (za-web-design). **Leia como evidência, não como decisão.** Valide nos prints em `shots/`.

## 1. Leitura rápida
- **Stack detectada:** jquery, workcontrol
- **Tokens já existentes no :root:** 0 (2 folhas cross-origin não lidas)
- **Cor primária provável:** `#06173a` com texto `#ffffff` → contraste **17.63:1** 
- **Tipografia:** display `Open Sans` · corpo `Open Sans` · razão média da escala ≈ **1.164**
- **Grade de espaço:** sem base clara (17% múltiplos de 8 · 51% de 4)
- **Container:** 1184px · **Header:** 100px, static, bg #ffffff
- **Overflow horizontal no mobile (390px):** não

## 2. Cor
### Fundos (peso = área)
| cor | peso |
|---|---|
| `#152024` | 1818.2 |
| `#ffffff` | 1609.9 |
| `#f4f4f1` | 1296 |
| `#eeeeee` | 1292.9 |
| `#e6e6e6` | 865.2 |
| `#303030` | 113.8 |
| `#d12421` | 63.2 |
| `#008068` | 3.1 |
| `#06173a` | 1.9 |

### Texto (peso = caracteres)
| cor | peso |
|---|---|
| `#666666` | 1997 |
| `#555555` | 792 |
| `#cccccc` | 409 |
| `#152024` | 265 |
| `#006699` | 192 |
| `#b81f1c` | 185 |
| `#333333` | 119 |
| `#ffffff` | 118 |

### Bordas
| cor | peso |
|---|---|
| `#0000001a` | 447.4 |
| `#ffffff29` | 394.8 |
| `#d12421` | 288 |
| `#333333` | 118.4 |
| `#858585` | 51.2 |
| `#00000047` | 25.7 |

### Gradientes
- `radial-gradient(circle at 100% 50%, rgba(209, 36, 33, 0.14), rgba(0, 0, 0, 0) 60%), none`

## 3. Tipografia
- Famílias (por caracteres): `Open Sans` (4270), `Archivo Black` (58)
- Fontes carregadas: Open Sans 300 normal; Open Sans 400 normal; Open Sans 600 normal; Open Sans 700 normal; Archivo Black 400 normal; icomoon 400 normal; icc-icons 400 normal
- Google Fonts: https://fonts.googleapis.com/css?family=Open%20Sans:300,400,600,700 https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap
- Pesos: 300, 400, 700, 800, 900, 600 · line-heights: normal, 1.65, 1.30, 1.15, 1.20

| tag | família · tamanho · peso · line-height · tracking · transform |
|---|---|
| a | `Open Sans | 22.4px | 300 | normal | normal | none` |
| h1 | `Open Sans | 32px | 700 | normal | normal | none` |
| p | `Open Sans | 16.8px | 300 | 1.65 | 0.672px | none` |
| h2 | `Open Sans | 19.2px | 700 | normal | normal | uppercase` |
| label | `Open Sans | 16px | 700 | normal | normal | none` |
| h3 | `Open Sans | 18.72px | 700 | normal | normal | none` |
| button | `Open Sans | 14px | 800 | normal | 0.84px | uppercase` |

Escala de tamanhos: 13px → 15px → 17px → 19px → 22px → 24px → 32px

## 4. Espaço, forma, profundidade, movimento
- Espaçamentos mais usados: 10px, 12px, 20px, 8px, 11.8281px, 15px, 5px, 16px, 6px, 30px, 40px, 14.4px
- Gaps (flex/grid): 12px, 9.6px, 8px
- Raios: 4px×23, 999px×11
- Sombras:
  - `rgba(209, 36, 33, 0.22) 0px 0px 38.4px 0px` ×2
  - `rgba(0, 0, 0, 0.1) 0px 1px 0px 0px` ×1
  - `rgba(0, 0, 0, 0.3) 0px 2px 6px 0px, rgba(0, 0, 0, 0.2) 0px 6px 16px 0px` ×1
- Transições: 0.15s, 0.3s · easing: linear, cubic-bezier(0.16
- Breakpoints (media queries): max 768px, max 544px, max 960px, max 640px, min 544px, min 768px, max 1024px, min 1024px, max 1280px, max 1056px, max 928px, max 375px
- z-index em uso: 97, 1000, 9999

## 5. Componentes
### Botões (assinaturas mais comuns)
- ×5 — `bg #06173a · color #ffffff · radius 0px · pad 6px 10px · fs 19.2px · fw 600 · tt uppercase · border 2px #06173a · h 16px`
- ×1 — `bg #00000000 · color #84171d · radius 0px · pad 8px 8px · fs 19.52px · fw 400 · tt uppercase · border 0px #84171d · h 44px`
- ×1 — `bg #d12421 · color #ffffff · radius 4px · pad 14px 56.0156px · fs 22.4px · fw 800 · tt uppercase · border 1px #00000000 · h 57px`
- ×1 — `bg #00000000 · color #152024 · radius 4px · pad 14.4px 28.8px · fs 14px · fw 800 · tt uppercase · border 1px #00000047 · h 50px`

### Campos de formulário
- ×1 — `bg #ffffff · border 1px #858585 · radius 4px · pad 11.2px 14.4px · h 46px · fs 16px`

### Links no texto
- `#cccccc · none · fw 300`
- `#006699 · none · fw 400`
- `#fbfbfb · none · fw 300`
- `#333333 · none · fw 400`

## 6. Estrutura e acessibilidade (alertas rápidos)
- h1 na página: **3** · landmarks: header×2, nav×2, main×1, footer×1
- imagens sem alt: **0** · botões sem nome: **0** · campos sem label: **0**
- textos com contraste abaixo de AA: **1**
  - `p.site_title` "Banco do Povo" #000000 sobre #152024 = 1.26:1 (16px)

<details><summary>Outline de headings</summary>

- h1: ICC SERRA - Banco do Povo
- h1: Aviso Legal
- h1: AVISO LEGAL:
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
- h2: ÚLTIMAS DO SITE!
- h3: Fluxo de Caixa apertado? Veja como usar Capital de Giro com segurança
- h3: Banco do Povo inspira Candiota com apoio real ao pequeno empreendedor
- h3: Microcrédito Mulher
- h3: O QUE É MAIS VANTAJOSO CONSTRUIR OU REFORMAR?
- h2: MAIS ACESSADOS!
- h3: Microcrédito Produtivo
- h3: A importância do microcrédito para o desenvolvimento econômico e socia
- h3: Troca de cheques
- h3: Crédito Agrícola
- h2: CONECTE-SE CONOSCO
- h2: Transparência
</details>


## 7. Uso ético da referência
Tokens (cor, escala, ritmo, raio, sombra) e **padrões** de layout são inspiração legítima.
**Não copiar:** logotipo, fotos, ilustrações, ícones proprietários, textos e marca registrada. Ver `references/extracao-referencia.md`.
