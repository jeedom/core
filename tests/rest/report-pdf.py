"""Contrôle le chemin de rendu avec la version de ReportLab fournie par Debian."""

import json
import re
import subprocess
import tempfile
from pathlib import Path

from PIL import Image

RENDERER = Path(__file__).resolve().parents[2] / "resources" / "report" / "render.py"


def block(identifier, kind, y, height, options, data=None):
    return {"id": identifier, "type": kind, "x": 18, "y": y, "width": 174, "height": height,
            "options": options, "data": data}


with tempfile.TemporaryDirectory() as workspace:
    root = Path(workspace)
    picture = root / "image"
    Image.new("RGB", (100, 40), (109, 153, 205)).save(picture, format="PNG")
    document = {
        "name": "Bilan énergie",
        "pages": [
            {"id": "energie", "blocks": [
                block("titre", "text", 18, 30, {"text": "Électricité <b>texte brut</b>\nCoût estimé en €", "fontSize": 20}),
                block("valeur", "value", 57, 23, {}, {"label": "Puissance actuelle", "value": "0", "unit": "W"}),
                block("photo", "image", 90, 45, {}, str(picture)),
                block("graphique", "chart", 150, 90, {"title": "Historique de puissance"},
                      {"start": 0, "end": 600, "series": [{"data": "weather", "label": "Prise salon", "color": "#689f19"}],
                       "labels": ["00:00", "00:02", "00:05", "00:07", "00:10"], "unit": "W"}),
            ]},
            {"id": "details", "blocks": [
                block("perdu", "value", 30, 23, {}, {"label": "Commande supprimée", "value": None, "unit": ""}),
                block("vide", "chart", 70, 80, {}, {"series": []}),
            ]},
        ],
        "histories": {"weather": {"gap": 90, "points": [[0, 0], [60, 2], [120, None], [420, 9], [480, 7]]}},
    }
    pdf = root / "report.pdf"

    def render(limit=25 * 1024 * 1024):
        with pdf.open("wb") as output:
            return subprocess.run(
                ["/usr/bin/python3", "-I", str(RENDERER), str(limit)],
                input=json.dumps(document, ensure_ascii=False).encode("utf-8"),
                stdout=output,
                stderr=subprocess.PIPE,
            )

    rendered = render()
    assert rendered.returncode == 0, rendered.stderr.decode("utf-8")
    info = subprocess.check_output(["pdfinfo", str(pdf)], text=True)
    assert re.search(r"^Pages:\s+2$", info, re.MULTILINE), info
    assert re.search(r"^Page size:.*\(A4\)", info, re.MULTILINE), info
    text = subprocess.check_output(["pdftotext", "-layout", str(pdf), "-"], text=True)
    for label in ("Électricité <b>texte brut</b>", "Coût estimé en €", "Prise salon",
                  "Indisponible", "Aucune donnée sur la période."):
        assert label in text, (label, text)
    assert re.search(r"^0[ \t]*W$", text, re.MULTILINE), text
    images = subprocess.check_output(["pdfimages", "-list", str(pdf)], text=True).splitlines()[2:]
    assert len(images) == 1, images
    too_large = render(limit=1024)
    assert too_large.returncode == 65 and b"taille maximale" in too_large.stderr, too_large
    assert pdf.stat().st_size <= 1024

    document["pages"][0]["blocks"][0]["height"] = .1
    overflow = render()
    assert overflow.returncode == 65 and b"Page energie, bloc titre" in overflow.stderr, overflow
    assert pdf.stat().st_size == 0, "Une erreur de composition ne doit pas produire de PDF partiel."
    print("Rapports PDF : pages A4, texte, valeur zéro, image, graphique, débordement et taille vérifiés.")
