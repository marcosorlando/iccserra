"""Versao em LINHA dos 10 icones: grid 24x24, stroke 1.6, fill none.

Mesma linguagem do set utilitario do design system, para uso de interface
(menus, botoes, listas). Os solidos continuam existindo para os cards de
condicoes, onde o peso cheio e que da o efeito do print.

Cada icone e uma lista de elementos; `d` vira <path>, e circle/rect saem
como estao. Nada aqui e preenchido: o traco carrega o desenho.
"""
import os

OUT = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'svg-line')

ICONS = {
    # ---------------------------------------------------------- proprios
    'coin': [
        {'circle': (12, 12, 9)},
        # cifrao: haste + S de duas curvas
        {'d': 'M12 6.6v10.8'},
        {'d': 'M14.5 9.7c0-.9-1.1-1.6-2.5-1.6s-2.5.7-2.5 1.6 1.1 1.4 2.5 1.7 '
              '2.5.8 2.5 1.7-1.1 1.6-2.5 1.6-2.5-.7-2.5-1.6'},
    ],
    'calendar': [
        {'rect': (3, 5, 18, 16, 2)},
        {'d': 'M8 2.8v4.4'},
        {'d': 'M16 2.8v4.4'},
        {'d': 'M3 10h18'},
        # dias: tracos de comprimento zero viram pontos com linecap round
        {'d': 'M7.5 13.6h.01'}, {'d': 'M12 13.6h.01'}, {'d': 'M16.5 13.6h.01'},
        {'d': 'M7.5 17.4h.01'}, {'d': 'M12 17.4h.01'}, {'d': 'M16.5 17.4h.01'},
    ],
    'calculator': [
        {'rect': (5, 2, 14, 20, 2)},
        {'rect': (8, 5, 8, 4, 1)},          # visor
        {'d': 'M8.5 13h.01'}, {'d': 'M12 13h.01'}, {'d': 'M15.5 13h.01'},
        {'d': 'M8.5 17.2h.01'}, {'d': 'M12 17.2h.01'}, {'d': 'M15.5 17.2h.01'},
    ],
    'user-tie': [
        {'circle': (12, 6.4, 3.3)},
        # ombros como dois arcos separados: fechados num U so, a gola e a
        # gravata caem dentro do contorno e somem
        {'d': 'M4.8 21v-1.2A5.2 5.2 0 0 1 10 14.6'},
        {'d': 'M19.2 21v-1.2A5.2 5.2 0 0 0 14 14.6'},
        {'d': 'M10 14.6 12 17.2l2-2.6'},              # gola em V
        {'d': 'm12 17.2 1 1.4-1 2.4-1-2.4z'},         # gravata (no + corpo)
    ],
    'tags': [
        {'d': 'M2.6 9.2V4.4a1.8 1.8 0 0 1 1.8-1.8h4.8c.5 0 .9.2 1.3.5l8.4 8.4'
              'a1.8 1.8 0 0 1 0 2.5l-4.9 4.9a1.8 1.8 0 0 1-2.5 0L3.1 10.5'
              'a1.8 1.8 0 0 1-.5-1.3z'},
        {'d': 'M14.6 3.5l5.9 5.9a2.2 2.2 0 0 1 0 3.1l-3.9 3.9'},  # 2a etiqueta
        {'circle': (6.9, 6.9, 1.15)},                              # furo
    ],
    'pie-chart': [
        {'circle': (12, 12, 9)},
        {'d': 'M12 3v9h9'},
    ],
    # ------------------------------------------------------------ marcas
    # facebook e linkedin: Feather Icons (MIT)
    'facebook': [
        {'d': 'M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z'},
    ],
    'linkedin': [
        {'d': 'M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7'
              'a6 6 0 0 1 6-6z'},
        {'rect': (2, 9, 4, 12, 0)},
        {'circle': (4, 4, 2)},
    ],
    'x-twitter': [
        {'d': 'M17.9 2.4h3.3l-7.2 8.2 8.5 11.2h-6.6l-5.2-6.8-5.9 6.8H1.5l7.7-8.8'
              'L1.3 2.4h6.8l4.7 6.2 5.1-6.2z'},
    ],
    'whatsapp': [
        {'d': 'M20.6 11.6a8.5 8.5 0 0 1-12.7 7.4l-4.7 1.6 1.6-4.6'
              'A8.5 8.5 0 1 1 20.6 11.6z'},
        {'d': 'M9 9.4c0 3 2.5 5.5 5.5 5.5.4 0 .8-.2 1-.6l.5-.8a.6.6 0 0 0-.2-.8'
              'l-1.5-.9a.6.6 0 0 0-.8.2l-.3.4a4.5 4.5 0 0 1-1.9-1.9l.4-.3'
              'a.6.6 0 0 0 .2-.8l-.9-1.5a.6.6 0 0 0-.8-.2l-.8.5c-.3.2-.6.6-.6 1z'},
    ],
}


def render(elems):
    out = []
    for e in elems:
        if 'd' in e:
            out.append(f'<path d="{e["d"]}"/>')
        elif 'circle' in e:
            cx, cy, r = e['circle']
            out.append(f'<circle cx="{cx}" cy="{cy}" r="{r}"/>')
        elif 'rect' in e:
            x, y, w, h, r = e['rect']
            rx = f' rx="{r}"' if r else ''
            out.append(f'<rect x="{x}" y="{y}" width="{w}" height="{h}"{rx}/>')
    return ''.join(out)


def main():
    os.makedirs(OUT, exist_ok=True)
    for name, elems in ICONS.items():
        body = render(elems)
        svg = ('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" '
               'width="24" height="24" fill="none" stroke="currentColor" '
               'stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">'
               f'{body}</svg>')
        with open(os.path.join(OUT, f'{name}.svg'), 'w') as fh:
            fh.write(svg)
        print(f'{name:12} {len(elems)} elemento(s)')


if __name__ == '__main__':
    main()
