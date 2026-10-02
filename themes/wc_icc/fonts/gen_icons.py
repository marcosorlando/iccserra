"""Desenha os 6 icones do print do ICC Serra como geometria exata.

Cada icone e montado com primitivas (discos, retangulos, arcos com espessura)
combinadas por operacoes booleanas do shapely, e sai como um unico <path>
preenchido. Nada de Bezier adivinhado no olho.

Sistema de coordenadas: SVG, 1000x1000, y para baixo.
"""
import math
import os

from shapely.geometry import LineString, Point, Polygon, box
from shapely.ops import unary_union
from shapely import affinity

SIZE = 1000
RES = 64          # segmentos por quadrante de circulo
SIMPLIFY = 0.6    # tolerancia de simplificacao, em unidades de 1000

OUT = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'svg')


# ---------------------------------------------------------------- primitivas

def disc(cx, cy, r):
    return Point(cx, cy).buffer(r, resolution=RES)


def ring(cx, cy, r_out, r_in):
    return disc(cx, cy, r_out).difference(disc(cx, cy, r_in))


def rrect(x0, y0, x1, y1, r=0.0):
    """Retangulo, opcionalmente com cantos arredondados."""
    b = box(x0, y0, x1, y1)
    if r <= 0:
        return b
    return b.buffer(-r, resolution=RES).buffer(r, resolution=RES)


def arc_points(cx, cy, r, a0, a1, step=2.0):
    """Pontos ao longo de um arco. Angulos em graus, sistema y-para-baixo."""
    n = max(2, int(abs(a1 - a0) / step) + 1)
    return [(cx + r * math.cos(math.radians(a0 + (a1 - a0) * i / (n - 1))),
             cy + r * math.sin(math.radians(a0 + (a1 - a0) * i / (n - 1))))
            for i in range(n)]


def stroke(points, width, cap='flat'):
    """Converte uma polilinha em contorno preenchido (offset de traco)."""
    return LineString(points).buffer(width / 2.0, resolution=RES,
                                     cap_style=cap, join_style='round')


def sector(cx, cy, r, a0, a1):
    """Setor de circulo (fatia de pizza)."""
    return Polygon([(cx, cy)] + arc_points(cx, cy, r, a0, a1) + [(cx, cy)])


def rot(geom, deg, origin=(500, 500)):
    return affinity.rotate(geom, deg, origin=origin)


def mv(geom, dx, dy):
    return affinity.translate(geom, dx, dy)


# ------------------------------------------------------------------- icones

def icon_coin():
    """Moeda: anel + cifrao. O S sai de dois arcos tangentes no centro."""
    g = ring(500, 500, 440, 368)

    # linha central do S: arco de cima (centro 500,393) emendado no de baixo
    pts = arc_points(500, 393, 107, -32, -270) + arc_points(500, 607, 107, -90, 148)
    s = stroke(pts, 64)
    bar = rrect(482, 238, 518, 762)          # haste vertical do cifrao
    return unary_union([g, s, bar])


def icon_calendar():
    """Calendario: dois pinos, cabecalho solido e grade 5x3 de dias."""
    parts = [
        rrect(338, 198, 388, 288, 22),       # pino esquerdo
        rrect(612, 198, 662, 288, 22),       # pino direito
        rrect(248, 248, 752, 378, 16),       # cabecalho
    ]
    w, gap = 72, 36                          # 5*72 + 4*36 = 504 de largura
    for row in range(3):
        for col in range(5):
            x = 248 + col * (w + gap)
            y = 430 + row * (w + gap)
            parts.append(rrect(x, y, x + w, y + w, 10))
    return unary_union(parts)


def icon_calculator():
    """Calculadora: dois paineis; no da esquerda menos e mais, no da
    direita igual e um bloco. Os simbolos sao recortes (furos)."""
    left = rrect(232, 196, 486, 804, 34)
    minus_l = rrect(288, 300, 430, 344, 12)
    plus_h = rrect(288, 578, 430, 622, 12)
    plus_v = rrect(337, 529, 381, 671, 12)
    left = left.difference(unary_union([minus_l, plus_h, plus_v]))

    right = rrect(514, 196, 768, 804, 34)
    eq_a = rrect(570, 286, 712, 326, 12)
    eq_b = rrect(570, 358, 712, 398, 12)
    block = rrect(570, 528, 712, 700, 16)
    right = right.difference(unary_union([eq_a, eq_b, block]))

    return unary_union([left, right])


def icon_user_tie():
    """Avalista: cabeca, busto com gola em V aberta e gravata.

    A gravata so le como gravata separada dos ombros por um sulco de fundo;
    encostada, ela some dentro do branco. E o V precisa ser aberto: estreito,
    ele e a gravata viram dois riscos paralelos.
    """
    head = disc(500, 285, 118)
    x0, x1, r, sy = 246, 754, 140, 660
    bust = Polygon([
        (x0, 800), (x0, sy),
        *arc_points(x0 + r, sy, r, 180, 270, step=3),   # ombro esquerdo
        *arc_points(x1 - r, sy, r, 270, 360, step=3),   # ombro direito
        (x1, 800),
    ])
    tie = Polygon([(500, 520), (558, 618), (528, 792), (472, 792), (442, 618)])
    collar = Polygon([(340, 398), (500, 600), (660, 398)])
    bust = bust.difference(collar).difference(tie.buffer(24, resolution=RES))
    return unary_union([head, bust, tie])


def icon_tags():
    """Duas etiquetas sobrepostas, com a de tras separada por um sulco."""
    def tag(hole=True):
        # cantos da esquerda retos, senao o arredondamento cria degrau onde a
        # ponta encosta no corpo; e a ponta precisa ser longa em relacao a
        # altura, senao o vertice fica obtuso e a forma le como pentagono
        body = unary_union([rrect(470, 310, 880, 690, 44), box(470, 310, 620, 690)])
        tip = Polygon([(120, 500), (474, 310), (474, 690)])
        g = unary_union([body, tip])
        # a de tras nao leva furo: recortado pela sobreposicao, ele viraria
        # reentrancia na silhueta em vez de furo
        return g.difference(disc(556, 500, 58)) if hole else g

    ang, dist, deg = -40, 150, -115
    dx = dist * math.cos(math.radians(deg))
    dy = dist * math.sin(math.radians(deg))
    front = rot(tag(), ang)
    back = mv(rot(tag(hole=False), ang), dx, dy)
    back = back.difference(front.buffer(30, resolution=RES))
    # a ponta da de tras escapa do outro lado da da frente e vira um caco
    # solto; sem esse filtro ele aparece como um bico grudado na borda
    if back.geom_type != 'Polygon':
        back = unary_union([g for g in back.geoms if g.area > 15000])
    return unary_union([front, back])


def icon_pie_chart():
    """Grafico de pizza com o quadrante superior direito destacado."""
    cx, cy, r = 500, 512, 396
    slice_ = sector(cx, cy, r, -90, 0)
    rest = disc(cx, cy, r).difference(slice_.buffer(22, resolution=RES))
    # afasta a fatia ao longo da bissetriz (-45 graus)
    d = 62
    slice_ = mv(slice_, d * math.cos(math.radians(-45)),
                d * math.sin(math.radians(-45)))
    return unary_union([rest, slice_])


ICONS = {
    'coin': icon_coin,
    'calendar': icon_calendar,
    'calculator': icon_calculator,
    'user-tie': icon_user_tie,
    'tags': icon_tags,
    'pie-chart': icon_pie_chart,
}


# ------------------------------------------------------------------ export

def signed_area(coords):
    a = 0.0
    for i in range(len(coords) - 1):
        x0, y0 = coords[i]
        x1, y1 = coords[i + 1]
        a += x0 * y1 - x1 * y0
    return a / 2.0


def ring_to_d(coords, want_positive):
    """Emite um contorno com a orientacao pedida. Em y-para-baixo, area
    assinada positiva e sentido horario; furos precisam do sentido oposto
    para o preenchimento nonzero abrir o buraco."""
    pts = list(coords)
    if (signed_area(pts) > 0) != want_positive:
        pts = pts[::-1]
    d = 'M' + ' L'.join(f'{x:.1f},{y:.1f}' for x, y in pts[:-1])
    return d + ' Z'


def geom_to_d(geom):
    geom = geom.simplify(SIMPLIFY)
    polys = [geom] if geom.geom_type == 'Polygon' else list(geom.geoms)
    parts = []
    for p in polys:
        if p.is_empty or p.area < 4:
            continue
        parts.append(ring_to_d(p.exterior.coords, True))
        for hole in p.interiors:
            parts.append(ring_to_d(hole.coords, False))
    return ' '.join(parts)


# Caixa optica comum. Formas redondas cheias pesam mais que retangulares na
# mesma caixa, entao levam um desconto para parecerem do mesmo tamanho.
BOX = 815
OPTICAL = {'coin': 0.98, 'pie-chart': 0.97, 'tags': 1.0,
           'calendar': 0.97, 'calculator': 0.99, 'user-tie': 1.0}


def normalize(geom, name):
    """Escala uniforme para a caixa optica e centraliza em (500, 500)."""
    x0, y0, x1, y1 = geom.bounds
    target = BOX * OPTICAL.get(name, 1.0)
    s = target / max(x1 - x0, y1 - y0)
    g = affinity.scale(geom, s, s, origin=(( x0 + x1) / 2, (y0 + y1) / 2))
    x0, y0, x1, y1 = g.bounds
    return mv(g, 500 - (x0 + x1) / 2, 500 - (y0 + y1) / 2)


def main():
    os.makedirs(OUT, exist_ok=True)
    for name, fn in ICONS.items():
        g = normalize(fn(), name)
        b = g.bounds
        d = geom_to_d(g)
        svg = (f'<svg xmlns="http://www.w3.org/2000/svg" '
               f'viewBox="0 0 {SIZE} {SIZE}" width="{SIZE}" height="{SIZE}">'
               f'<path d="{d}"/></svg>')
        with open(os.path.join(OUT, f'{name}.svg'), 'w') as fh:
            fh.write(svg)
        print(f'{name:12} bbox=({b[0]:5.0f},{b[1]:5.0f})-({b[2]:5.0f},{b[3]:5.0f})  '
              f'{len(d)//1000}k chars')


if __name__ == '__main__':
    main()
