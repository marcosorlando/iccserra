"""Gera a folha de referencia dos icones, com o sprite embutido.

Estatica (HTML), entao o sprite e copiado de dentro do partial PHP em vez de
incluido — assim a pagina abre direto do disco, sem PHP.
"""
import os
import re

HERE = os.path.dirname(os.path.abspath(__file__))
DEST = os.path.dirname(HERE)          # themes/wc_icc

ICONS = [
    ('coin', 'limite', 0xe900, 'Limite / valor'),
    ('calendar', 'prazo', 0xe901, 'Prazos'),
    ('calculator', 'taxa', 0xe902, 'Taxa de juros'),
    ('user-tie', 'avalista', 0xe903, 'Avalista'),
    ('tags', 'tac', 0xe904, 'TAC / taxa de abertura'),
    ('pie-chart', 'renda', 0xe905, 'Comprometimento de renda'),
    ('facebook', None, 0xe906, 'Facebook'),
    ('linkedin', None, 0xe907, 'LinkedIn'),
    ('x-twitter', 'x', 0xe908, 'X (Twitter)'),
    ('whatsapp', None, 0xe909, 'WhatsApp'),
]

CARDS = [
    ('limite', 'Limite:<br>mínimo R$ 600,00<br>máximo R$ 3 mil.'),
    ('prazo', 'Prazos:<br>no mínimo 3 meses e<br>no máximo 24 meses.'),
    ('taxa', 'Taxa de juros:<br>ao mês - máxima 3,99%<br>ao ano - máxima 56,45%.'),
    ('avalista', 'Poderá ser solicitado<br>avalista com renda<br>compatível.'),
    ('tac', 'Sujeito à aplicação<br>de Taxa de Abertura de<br>Crédito (TAC) de 3%.'),
    ('renda', 'Você poderá comprometer<br>somente 20% da renda<br>líquida comprovada.'),
]

CSS = """
:root{--primary:#D12421;--accent:#781F1C;--deep:#152024;--bg:#0e1619;
  --line:rgba(255,255,255,.22);--t1:#fff;--t3:rgba(255,255,255,.76);--t5:rgba(255,255,255,.60)}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--t3);
  font:400 16px/1.7 "Open Sans",system-ui,-apple-system,sans-serif;-webkit-font-smoothing:antialiased}
.wrap{max-width:1120px;margin:0 auto;padding:clamp(2rem,5vw,4.5rem) clamp(1.25rem,4vw,2.5rem)}
h1{font:400 clamp(1.8rem,4vw,2.6rem)/1.1 "Archivo Black",Impact,sans-serif;color:var(--t1);
  margin:0 0 .5rem;letter-spacing:-.02em}
h2{font:400 1.15rem/1.3 "Archivo Black",Impact,sans-serif;color:var(--t1);
  margin:3.5rem 0 .4rem;letter-spacing:-.01em}
.sub{color:var(--t5);font-size:.92rem;margin:0 0 1.25rem}
.lede{color:var(--t5);max-width:64ch;margin:0 0 .25rem}
code{font-family:ui-monospace,SFMono-Regular,Menlo,monospace;font-size:.86em;
  background:rgba(255,255,255,.08);padding:.15em .45em;border-radius:4px;color:#fff}
.cards{display:grid;gap:1.5rem;grid-template-columns:repeat(auto-fit,minmax(258px,1fr));
  background:var(--deep);padding:2rem;border-radius:6px}
.card{border:2px solid #fff;padding:2.25rem 1.25rem 1.75rem;text-align:center;color:#fff}
.card i{font-size:4.25rem;display:block;margin-bottom:1.5rem}
.card p{margin:0;font-size:1.02rem;line-height:1.45}
/* grade compacta, no mesmo tratamento do set utilitario do design system */
.util{display:grid;gap:1px;background:var(--line);border:1px solid var(--line);
  grid-template-columns:repeat(auto-fill,minmax(9.5rem,1fr))}
.util div{background:var(--deep);padding:1.5rem .75rem;display:flex;flex-direction:column;
  align-items:center;gap:.85rem;color:#fff}
.util span{font-size:.8rem;color:var(--t5);text-align:center;word-break:break-all}
.ref{display:grid;gap:1px;background:var(--line);border:1px solid var(--line);
  grid-template-columns:repeat(auto-fit,minmax(285px,1fr))}
.ref div{background:var(--deep);padding:1.1rem 1rem;display:flex;align-items:center;gap:1rem}
.ref .pair{display:flex;gap:.85rem;align-items:center;color:#fff;flex:none;width:4.6rem;
  justify-content:center}
.ref b{display:block;color:var(--t1);font-weight:600;font-size:.9rem}
.ref small{color:var(--t5);font-size:.8rem;display:block}
.social{background:var(--deep);padding:2rem;border-radius:6px;display:flex;gap:1.25rem;flex-wrap:wrap}
.social a{width:60px;height:60px;display:grid;place-items:center;color:#fff;
  background:var(--primary);border-radius:50%;font-size:1.6rem;text-decoration:none;transition:background .2s}
.social a:hover{background:var(--accent)}
.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;
  clip:rect(0,0,0,0);white-space:nowrap;border:0}
pre{background:var(--deep);border:1px solid var(--line);padding:1.1rem 1.25rem;overflow-x:auto;
  border-radius:6px;font-size:.86rem;line-height:1.6;color:var(--t3)}
.sizes{display:flex;align-items:flex-end;gap:2rem;background:var(--deep);padding:2rem;
  border-radius:6px;color:#fff;flex-wrap:wrap}
.sizes span{text-align:center;font-size:.78rem;color:var(--t5)}
.sizes i,.sizes svg{display:block;color:#fff;margin:0 auto .6rem}
"""


def main():
    # le o sprite em SVG puro, nao o partial PHP: o docblock do partial cita
    # `<svg>` e ate `?>` nos exemplos de uso, e qualquer parsing dele erra
    sprite = open(os.path.join(DEST, 'icons', 'icc-icons-sprite.svg')).read().strip()

    cards = '\n'.join(
        f'    <div class="card"><i class="icc-icon-{n}" aria-hidden="true"></i>'
        f'<p>{t}</p></div>' for n, t in CARDS)

    util = '\n'.join(
        f'    <div><svg class="icc-ic icc-ic-lg"><use href="#icc-i-{s}"/></svg>'
        f'<span>#icc-i-{s}</span></div>' for s, _, _, _ in ICONS)

    ref = []
    for shape, alias, cp, desc in ICONS:
        al = f'<code>.icc-icon-{alias}</code> · ' if alias else ''
        ref.append(
            f'    <div><span class="pair"><i class="icc-icon-{shape}"></i>'
            f'<svg class="icc-ic"><use href="#icc-i-{shape}"/></svg></span>'
            f'<span><b>{shape}</b><small>{desc}<br>{al}U+{cp:X}</small></span></div>')

    html = f'''<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>icc-icons · ICC Serra Banco do Povo</title>
<link rel="stylesheet" href="css/icc-icons.css">
<style>{CSS}</style>
</head>
<body>
{sprite}
<div class="wrap">

  <h1>icc-icons</h1>
  <p class="lede">Dois conjuntos com os mesmos dez nomes. O <b>sólido</b> vem da fonte
  (<code>.icc-icon-*</code>, 2,5&nbsp;KB em WOFF2) e carrega os cards de condições, onde o peso
  cheio é o que dá o efeito. O <b>linha</b> vem do sprite SVG (<code>#icc-i-*</code>, 5,3&nbsp;KB
  inline) e é para interface — menus, botões, listas —, na mesma linguagem do set utilitário
  do design system.</p>

  <h2>Sólido · cards de condições</h2>
  <p class="sub">Fonte de ícones. <code>&lt;i class="icc-icon-limite"&gt;&lt;/i&gt;</code></p>
  <div class="cards">
{cards}
  </div>

  <h2>Linha · interface</h2>
  <p class="sub">Sprite SVG, 24×24, stroke 1.6, herda a cor por <code>currentColor</code>.
  <code>&lt;svg class="icc-ic"&gt;&lt;use href="#icc-i-limite"/&gt;&lt;/svg&gt;</code></p>
  <div class="util">
{util}
  </div>

  <h2>Redes sociais</h2>
  <p class="sub">Aqui o sólido, dentro de um botão circular.</p>
  <div class="social">
    <a href="#"><i class="icc-icon-facebook" aria-hidden="true"></i><span class="sr-only">Facebook</span></a>
    <a href="#"><i class="icc-icon-linkedin" aria-hidden="true"></i><span class="sr-only">LinkedIn</span></a>
    <a href="#"><i class="icc-icon-x" aria-hidden="true"></i><span class="sr-only">X</span></a>
    <a href="#"><i class="icc-icon-whatsapp" aria-hidden="true"></i><span class="sr-only">WhatsApp</span></a>
  </div>

  <h2>Referência</h2>
  <p class="sub">Sólido e linha lado a lado. Os aliases valem nos dois sistemas.</p>
  <div class="ref">
{chr(10).join(ref)}
  </div>

  <h2>Tamanhos</h2>
  <div class="sizes">
    <span><i class="icc-icon-coin"></i>1em</span>
    <span><i class="icc-icon-coin icc-icon-2x"></i>.icc-icon-2x</span>
    <span><i class="icc-icon-coin icc-icon-4x"></i>.icc-icon-4x</span>
    <span><svg class="icc-ic"><use href="#icc-i-coin"/></svg>.icc-ic</span>
    <span><svg class="icc-ic icc-ic-2x"><use href="#icc-i-coin"/></svg>.icc-ic-2x</span>
    <span><svg class="icc-ic icc-ic-4x"><use href="#icc-i-coin"/></svg>.icc-ic-4x</span>
  </div>

  <h2>Como usar</h2>
  <pre>&lt;!-- SOLIDO: fonte. So precisa do CSS, ja carregado pelo index.php --&gt;
&lt;i class="icc-icon-limite" aria-hidden="true"&gt;&lt;/i&gt;

&lt;!-- LINHA: sprite. O partial ja entra logo apos o &lt;body&gt; no index.php --&gt;
&lt;svg class="icc-ic"&gt;&lt;use href="#icc-i-limite"/&gt;&lt;/svg&gt;

&lt;!-- icone que carrega significado sozinho pede rotulo textual --&gt;
&lt;a href="https://wa.me/5527999999999"&gt;
  &lt;svg class="icc-ic" aria-hidden="true"&gt;&lt;use href="#icc-i-whatsapp"/&gt;&lt;/svg&gt;
  &lt;span class="sr-only"&gt;WhatsApp&lt;/span&gt;
&lt;/a&gt;

&lt;!-- cor e tamanho: os dois herdam de color / font-size ou .icc-ic-* --&gt;
&lt;i class="icc-icon-renda" style="font-size:4rem;color:#D12421"&gt;&lt;/i&gt;
&lt;svg class="icc-ic icc-ic-2x" style="color:#D12421"&gt;&lt;use href="#icc-i-renda"/&gt;&lt;/svg&gt;</pre>

  <p class="lede" style="margin-top:2rem">Os glifos de marca (Facebook, LinkedIn, X, WhatsApp)
  são marcas registradas de seus detentores. Os sólidos vêm do
  <a href="https://simpleicons.org" style="color:#fff">Simple Icons</a> (CC0) e do
  <a href="https://icons.getbootstrap.com" style="color:#fff">Bootstrap Icons</a> (MIT);
  os de linha, do <a href="https://feathericons.com" style="color:#fff">Feather</a> (MIT),
  com X e WhatsApp desenhados aqui. Use-os apenas para identificar os perfis do ICC Serra.</p>

</div>
</body>
</html>
'''
    out = os.path.join(DEST, 'icc-icons.html')
    open(out, 'w').write(html)
    print(f'{out}  {os.path.getsize(out) / 1024:.1f} KB')


if __name__ == '__main__':
    main()
