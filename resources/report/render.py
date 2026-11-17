"""Rendu PDF d'une composition Jeedom et de ses données résolues par le Core."""

import errno
import json
import resource
import sys
from html import escape
from math import isfinite
from pathlib import Path

try:
    from PIL import Image
    from reportlab.lib.colors import HexColor
    from reportlab.lib.enums import TA_CENTER, TA_LEFT, TA_RIGHT
    from reportlab.lib.pagesizes import A4
    from reportlab.lib.styles import ParagraphStyle
    from reportlab.lib.units import mm
    from reportlab.lib.utils import ImageReader
    from reportlab.pdfbase import pdfmetrics
    from reportlab.pdfbase.ttfonts import TTFont
    from reportlab.pdfgen.canvas import Canvas
    from reportlab.platypus import Paragraph
except ImportError as error:
    print(error, file=sys.stderr)
    sys.exit(69)


FONT_DIRECTORY = Path("/usr/share/fonts/truetype/dejavu")
FONT = "DejaVuSans"
BOLD = "DejaVuSans-Bold"
INK = HexColor("#202630")
MUTED = HexColor("#536175")
GRID = HexColor("#dbe0e6")
COLORS = ("#689f19", "#2767b3", "#bc4d21", "#8156a3", "#157d79",
          "#aa4060", "#786329", "#3d5487", "#257042", "#895738")
ALIGNMENTS = {"left": TA_LEFT, "center": TA_CENTER, "right": TA_RIGHT}
MAX_PDF_BYTES = 25 * 1024 * 1024
MAX_IMAGE_PIXELS = 16_000_000


class InvalidLayout(Exception):
    """Composition qu'on ne peut pas rendre dans les dimensions demandées."""


def paragraph(pdf, text, x, top, width, height, size=10, color=INK, font=FONT, align="left"):
    if not text:
        return 0
    markup = escape(text).replace("\r\n", "\n").replace("\r", "\n").replace("\n", "<br/>")
    body = Paragraph(markup, ParagraphStyle("report", fontName=font, fontSize=size, leading=size * 1.22,
                                            textColor=color, alignment=ALIGNMENTS[align]))
    _, used = body.wrap(width, height)
    if used <= height + 1e-7:
        body.drawOn(pdf, x, top - used)
        return used
    return None


def single_line(pdf, label, x, y, width, size=8, color=MUTED, font=FONT):
    if width <= 0:
        return
    if pdfmetrics.stringWidth(label, font, size) > width:
        suffix = "…"
        if pdfmetrics.stringWidth(suffix, font, size) > width:
            return
        while label and pdfmetrics.stringWidth(label + suffix, font, size) > width:
            label = label[:-1]
        label += suffix
    pdf.setFillColor(color)
    pdf.setFont(font, size)
    pdf.drawString(x, y, label)


def draw_value(pdf, data, x, top, width, height):
    if height < 12:
        return
    if height >= 24:
        single_line(pdf, data["label"], x, top - 9, width, size=8)
        top -= 13
        height -= 13
    value = data["value"]
    displayed = "Indisponible" if value is None else " ".join(filter(None, (value, data["unit"])))
    size = min(18, height / 1.22)
    for text_size in (size, min(size, 10)):
        if paragraph(pdf, displayed, x, top, width, height, size=text_size, font=BOLD) is not None:
            return
    single_line(pdf, displayed, x, top - size, width, size=size, color=INK, font=BOLD)


def draw_image(pdf, filename, x, top, width, height):
    if filename:
        try:
            with Image.open(filename, formats=("PNG", "JPEG")) as image:
                if image.width * image.height <= MAX_IMAGE_PIXELS:
                    ratio = min(width / image.width, height / image.height)
                    drawn_width, drawn_height = image.width * ratio, image.height * ratio
                    image.thumbnail((int(drawn_width * 3) or 1, int(drawn_height * 3) or 1))
                    pixels = image.convert("RGBA" if "A" in image.getbands() or "transparency" in image.info else "RGB")
                    pdf.drawImage(ImageReader(pixels), x + (width - drawn_width) / 2,
                                  top - (height + drawn_height) / 2, drawn_width, drawn_height, mask="auto")
                    return
        except (OSError, ValueError, Image.DecompressionBombError):
            pass
    single_line(pdf, "Image indisponible", x + 3, top - 12, width - 6)


def draw_chart(pdf, options, data, histories, x, top, width, height):
    if width < 30 or height < 15:
        return
    single_line(pdf, options.get("title") or "Graphique", x + 4, top - 12, width - 8, size=10, color=INK, font=BOLD)
    series = [(entry, histories[entry["data"]]) for entry in data["series"]]
    values = [value for _, trace in series for _, value in trace["points"] if value is not None]
    if not values:
        single_line(pdf, "Aucune donnée sur la période.", x + 4, top - 25, width - 8)
        return
    if width < 115 or height < 76:
        single_line(pdf, "Graphique trop petit", x + 4, top - 25, width - 8)
        return

    minimum, maximum = min(values), max(values)
    if minimum == maximum:
        margin = abs(minimum) * .05 if minimum else 1
        minimum -= margin
        maximum += margin
    unit = data["unit"]
    labels = [format(minimum + (maximum - minimum) * step / 4, ".4g") + (" " + unit if unit else "")
              for step in range(5)]
    label_width = max(pdfmetrics.stringWidth(label, FONT, 7) for label in labels)
    left = x + min(label_width + 9, width * .35)
    right_margin = pdfmetrics.stringWidth(data["labels"][-1], FONT, 7) / 2 + 4
    right = x + width - max(8, right_margin)
    bottom = top - height + 33
    ceiling = top - 25
    pdf.setStrokeColor(GRID)
    pdf.setLineWidth(.5)
    for step, label in enumerate(labels):
        pos_y = bottom + (ceiling - bottom) * step / 4
        pdf.line(left, pos_y, right, pos_y)
        single_line(pdf, label, x + 2, pos_y - 2, left - x - 5, size=7)
    for step, label in enumerate(data["labels"]):
        pos_x = left + (right - left) * step / 4
        label_width = pdfmetrics.stringWidth(label, FONT, 7)
        if (right - left) / 4 < label_width + 3 and step not in (0, 4):
            continue
        single_line(pdf, label, pos_x - label_width / 2, bottom - 11, label_width + 1, size=7)

    legend_width = (width - 8) / len(series)
    for index, (entry, trace) in enumerate(series):
        color = HexColor(entry["color"] or COLORS[index])
        pdf.setStrokeColor(color)
        pdf.setFillColor(color)
        pdf.setLineWidth(1)
        path = pdf.beginPath()
        previous = None
        for time, value in trace["points"]:
            if value is None or not isfinite(value) or not data["start"] <= time < data["end"]:
                previous = None
                continue
            px = left + (right - left) * (time - data["start"]) / (data["end"] - data["start"])
            py = bottom + (ceiling - bottom) * (value - minimum) / (maximum - minimum)
            if previous is None or time - previous > trace["gap"]:
                path.moveTo(px, py)
                pdf.circle(px, py, .75, stroke=0, fill=1)
            else:
                path.lineTo(px, py)
            previous = time
        pdf.drawPath(path)
        legend_x = x + 4 + legend_width * index
        legend_y = top - height + 7
        pdf.line(legend_x, legend_y + 2, legend_x + 6, legend_y + 2)
        single_line(pdf, entry["label"], legend_x + 8, legend_y, legend_width - 10, size=7)


def draw_block(pdf, page_id, block, histories):
    x = block["x"] * mm
    top = A4[1] - block["y"] * mm
    width, height = block["width"] * mm, block["height"] * mm
    options = block["options"]
    pdf.saveState()
    bounds = pdf.beginPath()
    bounds.rect(x, top - height, width, height)
    pdf.clipPath(bounds, stroke=0)
    if block["type"] == "text":
        size = options.get("fontSize", 14)
        color = HexColor(options.get("color", "#202630"))
        if paragraph(pdf, options["text"], x, top, width, height, size, color, align=options.get("align", "left")) is None:
            raise InvalidLayout(f"Page {page_id}, bloc {block['id']} : le texte dépasse de son cadre.")
    elif block["type"] == "value":
        draw_value(pdf, block["data"], x, top, width, height)
    elif block["type"] == "image":
        draw_image(pdf, block["data"], x, top, width, height)
    elif block["type"] == "chart":
        draw_chart(pdf, options, block["data"], histories, x, top, width, height)
    pdf.restoreState()


def main(input_path, output_path):
    try:
        for font in (FONT, BOLD):
            path = FONT_DIRECTORY / (font + ".ttf")
            if not path.is_file():
                print(f"Police manquante : {path}", file=sys.stderr)
                return 69
            pdfmetrics.registerFont(TTFont(font, str(path)))
        resource.setrlimit(resource.RLIMIT_FSIZE, (MAX_PDF_BYTES, MAX_PDF_BYTES))
        with open(input_path, encoding="utf-8") as handle:
            document = json.load(handle)
        pdf = Canvas(output_path, pagesize=A4, pageCompression=1)
        pdf.setTitle(document["name"])
        pdf.setCreator("Jeedom")
        for page in document["pages"]:
            for block in page["blocks"]:
                draw_block(pdf, page["id"], block, document["histories"])
            pdf.showPage()
        pdf.save()
        return 0
    except InvalidLayout as error:
        print(error, file=sys.stderr)
        return 65
    except OSError as error:
        if error.errno != errno.EFBIG:
            raise
        print("Le PDF dépasse 25 Mio.", file=sys.stderr)
        return 65


if __name__ == "__main__":
    sys.exit(main(*sys.argv[1:]))
