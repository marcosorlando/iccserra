# Spec visual — ICC SERRA - Banco do Povo

> URL: http://127.0.0.1:8585/ · capturado em 2026-09-17 · larguras: 360, 390, 768, 1024, 1440px
> Extração automática (za-web-design). **Leia como evidência, não como decisão.** Valide nos prints em `shots/`.

## 1. Leitura rápida
- **Stack detectada:** jquery, owlCarousel, workcontrol
- **Tokens já existentes no :root:** 0 (1 folhas cross-origin não lidas)
- **Cor primária provável:** `#06173a` com texto `#ffffff` → contraste **17.63:1** 
- **Tipografia:** display `Open Sans` · corpo `Open Sans` · razão média da escala ≈ **1.217**
- **Grade de espaço:** sem base clara (10% múltiplos de 8 · 49% de 4)
- **Container:** 1123px · **Header:** 109px, static, bg #e6e6e6
- **Overflow horizontal no mobile (360px):** não

## 2. Cor
### Fundos (peso = área)
| cor | peso |
|---|---|
| `#152024` | 2893.6 |
| `#303030` | 2502.1 |
| `#e6e6e6` | 2269.5 |
| `#84171d` | 1263.6 |
| `#3f0425` | 1222.4 |
| `#cccccc` | 328.3 |
| `#ffffff` | 124.7 |
| `#00000080` | 110.1 |
| `#008068` | 49.6 |
| `#ff0000` | 47.3 |

### Texto (peso = caracteres)
| cor | peso |
|---|---|
| `#ffffff` | 2064 |
| `#555555` | 1759 |
| `#000000` | 901 |
| `#cccccc` | 728 |
| `#d12421` | 715 |
| `#006699` | 644 |
| `#84171d` | 514 |
| `#333333` | 195 |

### Bordas
| cor | peso |
|---|---|
| `#f1f1f1` | 646.9 |
| `#dedede` | 636.8 |
| `#d12421` | 389.8 |
| `#84171d` | 290.6 |
| `#888888` | 271.5 |
| `#cccccc` | 190.7 |

## 3. Tipografia
- Famílias (por caracteres): `Open Sans` (8019)
- Fontes carregadas: Open Sans 300 normal; Open Sans 400 normal; Open Sans 600 normal; Open Sans 700 normal; icomoon 400 normal
- Google Fonts: https://fonts.googleapis.com/css?family=Open%20Sans:300,400,600,700
- Pesos: 400, 700, 600, 300, 900 · line-heights: normal, 1.50, 1.34, 0.94

| tag | família · tamanho · peso · line-height · tracking · transform |
|---|---|
| h1 | `Open Sans | 32px | 300 | normal | normal | uppercase` |
| a | `Open Sans | 16px | 400 | normal | normal | none` |
| h2 | `Open Sans | 24px | 700 | normal | normal | none` |
| p | `Open Sans | 16px | 400 | normal | normal | none` |
| h6 | `Open Sans | 15.52px | 700 | normal | normal | none` |
| h3 | `Open Sans | 18.72px | 700 | normal | normal | none` |

Escala de tamanhos: 10px → 13px → 16px → 19px → 22px → 24px → 32px

## 4. Espaço, forma, profundidade, movimento
- Espaçamentos mais usados: 20px, 10px, 12px, 8px, 11.2188px, 5px, 11.0625px, 15px, 50px, 30px, 40px, 25px
- Gaps (flex/grid): —
- Raios: 12px×25, 20px×8, 3px×1
- Sombras:
  - `rgba(0, 0, 0, 0.2) 0px 0px 5px 1px inset` ×8
- Transições: 0.3s · easing: ease
- Breakpoints (media queries): max 768px, max 544px, min 544px, min 768px, max 1280px, max 1024px, max 960px, min 1024px, max 1056px, max 928px, max 480px, min 960px
- z-index em uso: 9999

## 5. Componentes
### Botões (assinaturas mais comuns)
- ×5 — `bg #06173a · color #ffffff · radius 0px · pad 6px 10px · fs 19.2px · fw 600 · tt uppercase · border 2px #06173a · h 16px`
- ×1 — `bg #ff0000 · color #ffffff · radius 0px · pad 2.5% 10% · fs 22.4px · fw 700 · tt uppercase · border 0px #ffffff · h 58px`
- ×1 — `bg #0e96e5 · color #ffffff · radius 3px · pad 15px 30px · fs 19.2px · fw 700 · tt uppercase · border 0px #ffffff · h 62px`
- ×1 — `bg #008068 · color #ffffff · radius 0px · pad 2.5% 10% · fs 22.4px · fw 700 · tt uppercase · border 1px #008068 · h 91px`
- ×1 — `bg #ff0000 · color #ffffff · radius 0px · pad 13.25px 53.0312px · fs 22.4px · fw 700 · tt uppercase · border 0px #ffffff · h 58px`

### Campos de formulário
- —

### Links no texto
- `#006699 · none · fw 400`
- `#555555 · none · fw 400`
- `#cccccc · none · fw 300`
- `#cccccc · none · fw 400`

## 6. Estrutura e acessibilidade (alertas rápidos)
- h1 na página: **35** · landmarks: header×13, nav×2, main×1, aside×1, footer×1
- imagens sem alt: **0** · botões sem nome: **0** · campos sem label: **0**
- textos com contraste abaixo de AA: **20**
  - `a.link.icon-eye` "LER MAIS!" #ffffff sobre #ffffff = 1:1 (14px)
  - `a.link.icon-eye` "LER MAIS!" #ffffff sobre #ffffff = 1:1 (9.8px)
  - `a.link.icon-eye` "LER MAIS!" #ffffff sobre #ffffff = 1:1 (9.8px)
  - `a` "Fluxo de Caixa apertado? Veja como usar " #cccccc sobre #ffffff = 1.61:1 (32px)
  - `a` "Banco do Povo inspira Candiota com apoio" #cccccc sobre #ffffff = 1.61:1 (22.4px)
  - `a` "Microcrédito Mulher" #cccccc sobre #ffffff = 1.61:1 (22.4px)
  - `a.link.icon-eye` "LER MAIS!" #cccccc sobre #ffffff = 1.61:1 (16px)
  - `a.link.icon-eye` "LER MAIS!" #cccccc sobre #ffffff = 1.61:1 (16px)
  - `a.link.icon-eye` "LER MAIS!" #cccccc sobre #ffffff = 1.61:1 (16px)
  - `a.link.icon-eye` "LER MAIS!" #cccccc sobre #ffffff = 1.61:1 (16px)

<details><summary>Outline de headings</summary>

- h1: ICC SERRA - Banco do Povo
- h1: Microcrédito - Crédito para Boas Ideias!
- h2: Acesse nosso simulador, e realize uma simulação de crédito agora mesmo
- h2: DESCUBRA A MODALIDADE DE CRÉDITO IDEAL PARA VOCÊ
- h1: MICROCRÉDITO PRODUTIVO
- h1: MICROCRÉDITO MULHER
- h1: REFORMA E AMPLIAÇÃO
- h1: CRÉDITO AGRÍCOLA
- h1: ÚLTIMAS NOVIDADES DA ICC SERRA!
- h1: QUAIS SÃO AS PRINCIPAIS LINHAS DE CRÉDITO?
- h3: MICROCRÉDITO PRODUTIVO:
- h3: REFORMA & AMPLIACÃO:
- h3: CRÉDITO AGRICOLA:
- h3: TROCA DE CHEQUE:
- h1: ALGUNS DE NOSSOS PARCEIROS
- h1: Torne-se um parceiro ICC SERRA - BANCO DO POVO
- h1: SIGA O ICC SERRA - BANCO DO POVO NO FACEBOOK
- h1: DESCUBRA O QUE É MICROCRÉDITO PRODUTIVO
- h1: QUER SABER MAIS? ENTÃO VEJA TAMBÉM!
- h1: O QUE É MAIS VANTAJOSO CONSTRUIR OU REFORMAR?
- h1: MICROCRÉDITO: FINANCIAMENTO PARA PEQUENOS NEGÓCIOS
- h1: 6 MOTIVOS PARA ADQUIRIR MICROCRÉDITO
- h1: COMO QUALIFICAR O CLIENTE IDEAL
- h1: PASSO A PASSO PARA CRIAR UM PROCESSO DE VENDAS
- h1: EMPREENDEDOR: APRENDA A CRIAR SEU PLANO DE NEGÓCIO
- h1: COMO SE TORNAR UM EMPREENDEDOR DE SUCESSO EM 2019?
- h1: O EMPREENDEDOR COMO AGENTE DE INOVAÇÃO
- h1: E-BOOK METAS
- h1: POSTOS DE ATENDIMENTO BANCO DO POVO
INSTITUIÇÃO COMUNITÁRIA DE CRÉDITO
- h6: HORÁRIO DE ATENDIMENTO: DE SEGUNDA À SEXTA-FEIRA DAS 9:00H AS 17:00H
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
</details>


## 7. Uso ético da referência
Tokens (cor, escala, ritmo, raio, sombra) e **padrões** de layout são inspiração legítima.
**Não copiar:** logotipo, fotos, ilustrações, ícones proprietários, textos e marca registrada. Ver `references/extracao-referencia.md`.
