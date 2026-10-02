"""Monta o sprite SVG do tema com os dois conjuntos.

- `icc-i-<nome>`        -> versao em linha (24x24, stroke, interface)
- `icc-i-<nome>-solid`  -> versao solida (1000x1000, cards de condicoes)

O sprite e inline (partial PHP) de proposito: `<use href="arquivo.svg#id">`
externo depende de same-origin e falha calado em navegador antigo.
"""
import os
import re

HERE = os.path.dirname(os.path.abspath(__file__))
LINE = os.path.join(HERE, 'svg-line')
SOLID = os.path.join(HERE, 'svg')
DEST = os.path.dirname(HERE)          # themes/wc_icc

# (nome de forma, alias semantico) - mesmos nomes da fonte, para os dois
# sistemas nao divergirem
ICONS = [
    ('coin', 'limite'), ('calendar', 'prazo'), ('calculator', 'taxa'),
    ('user-tie', 'avalista'), ('tags', 'tac'), ('pie-chart', 'renda'),
    ('facebook', None), ('linkedin', None), ('x-twitter', 'x'),
    ('whatsapp', None),
]

LINE_ATTRS = ('viewBox="0 0 24 24" fill="none" stroke="currentColor" '
              'stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"')


def inner(path):
    """Conteudo de dentro do <svg>, sem o wrapper."""
    s = open(path).read()
    m = re.search(r'<svg[^>]*>(.*)</svg>', s, re.S)
    return m.group(1).strip()


def main():
    syms = []
    syms.append('  <!-- linha - interface (24x24, stroke 1.6) -->')
    for shape, alias in ICONS:
        syms.append(f'  <symbol id="icc-i-{shape}" {LINE_ATTRS}>'
                    f'{inner(os.path.join(LINE, shape + ".svg"))}</symbol>')
        if alias:
            syms.append(f'  <symbol id="icc-i-{alias}" {LINE_ATTRS}>'
                        f'<use href="#icc-i-{shape}"/></symbol>')

    # Os solidos ficam FORA do sprite de proposito: sao 17 KB de path contra
    # 3,7 KB dos de linha, e a fonte (2,5 KB, cacheada) ja os entrega. Quem
    # precisar deles em SVG usa os arquivos de icons/solid/.

    php = f'''<?php
/**
 * Sprite de icones do ICC Serra. Inclua UMA vez por pagina, logo depois da
 * abertura do <body>:
 *
 *   <?php require_once REQUIRE_PATH . '/inc/icc-icons-sprite.php'; ?>
 *
 * Uso:
 *   <svg class="icc-ic"><use href="#icc-i-limite"/></svg>          (linha)
 *   <svg class="icc-ic icc-ic-3x"><use href="#icc-i-limite-solid"/></svg>
 *
 * A cor vem do `color` do elemento; o tamanho, da classe .icc-ic*.
 * Gerado por fonts/build_sprite.py - nao edite a mao.
 */
?>
<svg xmlns="http://www.w3.org/2000/svg" width="0" height="0"
     style="position:absolute;width:0;height:0;overflow:hidden"
     aria-hidden="true" focusable="false">
{chr(10).join(syms)}
</svg>
'''
    os.makedirs(os.path.join(DEST, 'inc'), exist_ok=True)
    out = os.path.join(DEST, 'inc', 'icc-icons-sprite.php')
    open(out, 'w').write(php)

    # mesmo sprite sem o wrapper PHP, para quem consome SVG puro (a folha de
    # referencia estatica, ferramentas de design). Gerado no mesmo passo, entao
    # nao diverge do partial.
    svg_only = re.search(r'(<svg xmlns=.*</svg>)', php, re.S).group(1)
    os.makedirs(os.path.join(DEST, 'icons'), exist_ok=True)
    open(os.path.join(DEST, 'icons', 'icc-icons-sprite.svg'), 'w').write(svg_only + '\n')

    # SVGs soltos dos dois conjuntos, para uso fora do sprite
    for sub, src_dir in (('line', LINE), ('solid', SOLID)):
        dst = os.path.join(DEST, 'icons', sub)
        os.makedirs(dst, exist_ok=True)
        for shape, _ in ICONS:
            body = open(os.path.join(src_dir, shape + '.svg')).read()
            if sub == 'solid':
                # solto, o solido precisa herdar cor como o de linha
                body = body.replace('<path d=', '<path fill="currentColor" d=', 1)
            open(os.path.join(dst, shape + '.svg'), 'w').write(body)

    n = len(ICONS) + sum(1 for _, a in ICONS if a)
    print(f'sprite (so linha): {out}  -  {n} symbols, '
          f'{os.path.getsize(out) / 1024:.1f} KB')
    print(f'svg soltos: icons/line ({len(ICONS)}) + icons/solid ({len(ICONS)})')


if __name__ == '__main__':
    main()
