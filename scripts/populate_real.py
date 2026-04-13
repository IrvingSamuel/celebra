#!/usr/bin/env python3
"""
Populate Celebra with more venues & services using real Unsplash images.
Each image URL is a direct Unsplash link (free to use with attribution).
"""

import os
import re
import json
import time
import requests
import unicodedata
import mysql.connector
from pathlib import Path
from urllib.parse import urlparse

# ── Config ────────────────────────────────────────
DB_CONFIG = {
    "host": "127.0.0.1",
    "user": "casamentos",
    "password": "guQ4zOsE5qqRemnKNcFR",
    "database": "casamentos",
}

BASE_DIR = Path(__file__).resolve().parent.parent
IMG_DIR = BASE_DIR / "public" / "images"
VENUES_DIR = IMG_DIR / "venues"
SERVICES_DIR = IMG_DIR / "services"

def slugify(text):
    text = unicodedata.normalize("NFKD", text).encode("ascii", "ignore").decode("ascii")
    text = re.sub(r"[^\w\s-]", "", text.lower())
    return re.sub(r"[-\s]+", "-", text).strip("-")


def download_image(url, dest_path):
    """Download image from URL to dest_path. Returns True on success."""
    dest_path.parent.mkdir(parents=True, exist_ok=True)
    if dest_path.exists() and dest_path.stat().st_size > 5000:
        print(f"  ✓ Already exists: {dest_path.name}")
        return True
    try:
        r = requests.get(url, timeout=30, allow_redirects=True)
        if r.status_code == 200 and len(r.content) > 5000:
            with open(dest_path, "wb") as f:
                f.write(r.content)
            print(f"  ✓ Downloaded: {dest_path.name} ({len(r.content)//1024}KB)")
            return True
        else:
            print(f"  ✗ Failed: {dest_path.name} (status={r.status_code}, size={len(r.content)})")
            return False
    except Exception as e:
        print(f"  ✗ Error: {dest_path.name}: {e}")
        return False


# ─────────────────────────────────────────────────────────
# Real Unsplash photo IDs curated for each category
# Format: https://images.unsplash.com/photo-{ID}?w=800&h=600&fit=crop
# ─────────────────────────────────────────────────────────

NEW_VENUES = [
    {
        "name": "Palácio das Artes",
        "description": "Palácio histórico do século XIX com salões ornamentados, lustres de cristal e jardins franceses. Capacidade para grandes recepções com serviço de valet e suíte nupcial.",
        "city": "São Paulo", "state": "SP",
        "address": "Av. Paulista, 1578",
        "type": "indoor", "price": 65000, "capacity": 500, "rating": 4.9,
        "images": [
            "photo-1519167758481-83f550bb49b3",  # grand ballroom
            "photo-1464366400600-7168b8af9bc3",  # elegant venue interior
            "photo-1507003211169-0a1dd7228f2d",  # chandelier hall
            "photo-1505236858219-8359eb29e329",  # luxury event space
        ],
    },
    {
        "name": "Mirante do Horizonte",
        "description": "Espaço ao ar livre no alto de uma colina com vista panorâmica de 360°. Deck de madeira, pérgolas com flores e iluminação cênica para cerimônias ao pôr do sol.",
        "city": "Belo Horizonte", "state": "MG",
        "address": "Estrada do Mirante, 200",
        "type": "outdoor", "price": 38000, "capacity": 300, "rating": 4.8,
        "images": [
            "photo-1510076857177-7470076d4098",  # outdoor ceremony sunset
            "photo-1478146059778-26028b07395a",  # hillside venue
            "photo-1464699908537-0954e50791ee",  # sunset ceremony
            "photo-1501281668745-f7f57925c3b4",  # outdoor reception
        ],
    },
    {
        "name": "Casa Grande Colonial",
        "description": "Casarão colonial do século XVIII cercado por mata atlântica. Arquitetura preservada com varandas amplas, capela histórica e área para 350 convidados.",
        "city": "Petrópolis", "state": "RJ",
        "address": "Rua do Imperador, 450",
        "type": "both", "price": 42000, "capacity": 350, "rating": 4.9,
        "images": [
            "photo-1600585154340-be6161a56a0c",  # colonial mansion
            "photo-1582653291997-079a1c04e5a1",  # historic building
            "photo-1543489822-c49534f3271f",  # colonial estate
            "photo-1600607687939-ce8a6c25118c",  # mansion garden
        ],
    },
    {
        "name": "Loft Industrial 42",
        "description": "Galpão industrial revitalizado com pé-direito de 8 metros, tijolos aparentes e vigas de ferro. Estilo nova-iorquino ideal para casamentos modernos e festas corporativas.",
        "city": "São Paulo", "state": "SP",
        "address": "Rua Augusta, 2042",
        "type": "indoor", "price": 28000, "capacity": 250, "rating": 4.7,
        "images": [
            "photo-1519671482749-fd09be7ccebf",  # industrial loft wedding
            "photo-1470229722913-7c0e2dbbafd3",  # industrial space
            "photo-1504196606672-aef5c9cefc92",  # loft event
            "photo-1492684223f00-286b6c1d1385",  # brick wall venue
        ],
    },
    {
        "name": "Ilha dos Coqueiros Resort",
        "description": "Resort à beira-mar com praia privativa, gazebo na areia e salão com vista para o oceano. Pacote inclui hospedagem para noivos e decoração praiana.",
        "city": "Florianópolis", "state": "SC",
        "address": "Praia de Jurerê, s/n",
        "type": "both", "price": 75000, "capacity": 400, "rating": 4.9,
        "images": [
            "photo-1507525428034-b723cf961d3e",  # tropical beach
            "photo-1544551763-46a013bb70d5",  # beach wedding
            "photo-1520250497591-112f2f40a3f4",  # beach resort
            "photo-1571896349842-33c89424de2d",  # resort event
        ],
    },
    {
        "name": "Vinícola Santa Helena",
        "description": "Vinícola boutique na Serra Gaúcha com vinhedos centenários. Cerimônia entre as parreiras, recepção na cave de vinhos com degustação inclusa.",
        "city": "Bento Gonçalves", "state": "RS",
        "address": "Estrada do Vinho, km 12",
        "type": "outdoor", "price": 55000, "capacity": 200, "rating": 4.8,
        "images": [
            "photo-1506377247377-2a5b3b417ebb",  # vineyard
            "photo-1560493676-04071c5f467b",  # vineyard wedding
            "photo-1558618666-fcd25c85f82e",  # wine cellar
            "photo-1516594915697-87eb3b1c14ea",  # winery event
        ],
    },
    {
        "name": "Castelo das Flores",
        "description": "Réplica de castelo europeu com torres, ponte levadiça decorativa e salão de banquetes medieval. Jardins com roseirais e fonte central iluminada.",
        "city": "Curitiba", "state": "PR",
        "address": "Alameda dos Castelos, 88",
        "type": "both", "price": 48000, "capacity": 280, "rating": 4.8,
        "images": [
            "photo-1551882547-ff40c63fe5fa",  # castle-like building
            "photo-1585320806297-9794b3e4eeae",  # castle venue
            "photo-1600596542815-ffad4c1539a9",  # grand garden venue
            "photo-1541971875076-8f970d573be6",  # elegant garden
        ],
    },
    {
        "name": "Hacienda Tropical",
        "description": "Hacienda de estilo mexicano com pátio central, fontes de azulejo e vegetação tropical. Ambiente acolhedor e colorido para festas ao ar livre.",
        "city": "Fortaleza", "state": "CE",
        "address": "Av. Beira Mar, 3200",
        "type": "outdoor", "price": 32000, "capacity": 220, "rating": 4.7,
        "images": [
            "photo-1600585154526-990dced4db0d",  # hacienda style
            "photo-1580587771525-78b9dba3b914",  # tropical courtyard
            "photo-1583608205776-bfd35f0d9f83",  # colorful venue
            "photo-1576013551627-0cc20b96c2a7",  # tropical garden party
        ],
    },
    {
        "name": "Sky Lounge Rooftop",
        "description": "Terraço panorâmico no 35° andar com vista da skyline. Piscina de borda infinita, bar suspenso e área lounge com lareira a gás. Eventos noturnos com iluminação exclusiva.",
        "city": "São Paulo", "state": "SP",
        "address": "Av. Brigadeiro Faria Lima, 4500",
        "type": "outdoor", "price": 85000, "capacity": 180, "rating": 4.9,
        "images": [
            "photo-1470337458703-46ad1756a187",  # rooftop event
            "photo-1414235077428-338989a2e8c0",  # rooftop pool
            "photo-1529290130-4ca3753253ae",  # city skyline view
            "photo-1519167758481-83f550bb49b3",  # elegant rooftop
        ],
    },
    {
        "name": "Sítio Recanto da Serra",
        "description": "Sítio ecológico na serra catarinense com lago natural, ponte de madeira e bosque de araucárias. Cerimônias intimistas com fogueira e música ao vivo.",
        "city": "Urubici", "state": "SC",
        "address": "Estrada Geral, km 8",
        "type": "outdoor", "price": 22000, "capacity": 150, "rating": 4.7,
        "images": [
            "photo-1505765050516-f72dcac9c60e",  # mountain lodge
            "photo-1513836279014-a89f7a76ae86",  # forest venue
            "photo-1501785888041-af3ef285b470",  # mountain landscape
            "photo-1470770903676-69b98201ea1c",  # outdoor forest ceremony
        ],
    },
    {
        "name": "Marina Bay Eventos",
        "description": "Pier exclusivo sobre a Baía de Guanabara com deck flutuante e tenda cristal. Vista para o Pão de Açúcar e Cristo Redentor. Inclui transfer marítimo.",
        "city": "Rio de Janeiro", "state": "RJ",
        "address": "Marina da Glória, s/n",
        "type": "outdoor", "price": 95000, "capacity": 350, "rating": 4.9,
        "images": [
            "photo-1483653085484-eb63c9f02547",  # waterfront venue
            "photo-1518998053901-5348d3961a04",  # bay view event
            "photo-1544551763-77932b56a3d2",  # waterfront reception
            "photo-1505142468610-359e7d316be0",  # pier event sunset
        ],
    },
    {
        "name": "Espaço Bambuzal",
        "description": "Jardim zen com bambus gigantes, espelhos d'água e trilhas iluminadas. Arquitetura sustentável com estrutura de madeira certificada e energia solar.",
        "city": "Campinas", "state": "SP",
        "address": "Rodovia D. Pedro I, km 136",
        "type": "outdoor", "price": 35000, "capacity": 280, "rating": 4.6,
        "images": [
            "photo-1585320806297-9794b3e4eeae",  # zen garden
            "photo-1502672260266-1c1ef2d93688",  # bamboo garden
            "photo-1558618666-fcd25c85f82e",  # garden lighting
            "photo-1416331108676-a22ccb276e35",  # nature event space
        ],
    },
    {
        "name": "Salão Versailles",
        "description": "Salão de festas clássico inspirado no Palácio de Versalhes. Espelhos venezianos, pisos de mármore e jardim formal com topiárias. O mais requintado de Brasília.",
        "city": "Brasília", "state": "DF",
        "address": "SHIS QI 9/11, Lago Sul",
        "type": "indoor", "price": 72000, "capacity": 600, "rating": 4.8,
        "images": [
            "photo-1519167758481-83f550bb49b3",  # grand ballroom
            "photo-1562778612-e1e0cda9915c",  # marble hall
            "photo-1551882547-ff40c63fe5fa",  # luxurious interior
            "photo-1505236858219-8359eb29e329",  # chandeliers
        ],
    },
    {
        "name": "Chalé da Montanha",
        "description": "Chalé de madeira e pedra em meio à Mata Atlântica. Lareira central, varanda panorâmica e área gourmet. Ideal para mini-weddings de até 80 convidados.",
        "city": "Campos do Jordão", "state": "SP",
        "address": "Alto do Capivari, 300",
        "type": "both", "price": 18000, "capacity": 80, "rating": 4.8,
        "images": [
            "photo-1518780664697-55e3ad937233",  # mountain chalet
            "photo-1449158743715-0a90ebb6d2d8",  # cozy cabin
            "photo-1510798831971-661eb04b3739",  # mountain lodge interior
            "photo-1520250497591-112f2f40a3f4",  # rustic venue
        ],
    },
    {
        "name": "Arena Multiuso Nordeste",
        "description": "Centro de convenções com capacidade para 1000 pessoas. 3 salões modulares, sistema de som profissional e estacionamento para 500 veículos.",
        "city": "Recife", "state": "PE",
        "address": "Av. Caxangá, 5000",
        "type": "indoor", "price": 45000, "capacity": 1000, "rating": 4.6,
        "images": [
            "photo-1431540015239-7c95613a569b",  # large event hall
            "photo-1497366216548-37526070297c",  # convention center
            "photo-1497366811353-6870744d04b2",  # conference setup
            "photo-1540575467063-178a50c2df87",  # large indoor event
        ],
    },
    {
        "name": "Pousada dos Ipês",
        "description": "Pousada rural rodeada por ipês floridos com lago artificial e deck suspenso. Cerimônia no gazebo de pedra com vista para as montanhas da Mantiqueira.",
        "city": "Monte Verde", "state": "MG",
        "address": "Estrada Monte Verde, km 5",
        "type": "outdoor", "price": 25000, "capacity": 120, "rating": 4.7,
        "images": [
            "photo-1416331108676-a22ccb276e35",  # garden gazebo
            "photo-1470770903676-69b98201ea1c",  # flower garden
            "photo-1501785888041-af3ef285b470",  # mountain view
            "photo-1517457373958-b7bdd4587205",  # outdoor ceremony
        ],
    },
    {
        "name": "Espaço Nobre Buffet",
        "description": "Casa de festas com dois pavimentos, pista de dança em LED, camarim para noivos e espaço kids. Buffet próprio com menu degustação incluso.",
        "city": "Goiânia", "state": "GO",
        "address": "Rua T-63, 1200, Setor Bueno",
        "type": "indoor", "price": 35000, "capacity": 400, "rating": 4.5,
        "images": [
            "photo-1464366400600-7168b8af9bc3",  # party hall
            "photo-1540575467063-178a50c2df87",  # indoor party
            "photo-1519167758481-83f550bb49b3",  # elegant hall
            "photo-1504196606672-aef5c9cefc92",  # dance floor
        ],
    },
    {
        "name": "Mansão Atlântica",
        "description": "Mansão à beira-mar com arquitetura Art Déco e jardim tropical. Piscina com borda infinita voltada para o oceano e heliponto privativo.",
        "city": "Salvador", "state": "BA",
        "address": "Praia do Forte, Litoral Norte",
        "type": "both", "price": 88000, "capacity": 250, "rating": 4.9,
        "images": [
            "photo-1512917774080-9991f1c4c750",  # luxury oceanfront mansion
            "photo-1613490493576-7fde63acd811",  # infinity pool ocean
            "photo-1600596542815-ffad4c1539a9",  # tropical mansion
            "photo-1600585154340-be6161a56a0c",  # mansion exterior
        ],
    },
    {
        "name": "Estação Ferroviária Cultural",
        "description": "Antiga estação de trem restaurada transformada em espaço cultural. Plataformas cobertas, salão principal com relógio histórico e vagão-bar temático.",
        "city": "Curitiba", "state": "PR",
        "address": "Praça Eufrásio Correia, s/n",
        "type": "indoor", "price": 30000, "capacity": 300, "rating": 4.7,
        "images": [
            "photo-1507003211169-0a1dd7228f2d",  # historic building interior
            "photo-1497366216548-37526070297c",  # restored venue
            "photo-1562778612-e1e0cda9915c",  # historic hall
            "photo-1497366811353-6870744d04b2",  # cultural space
        ],
    },
]

NEW_SERVICES = [
    # ── Fotografia (cat_id=2) ──
    {
        "name": "André Luiz Fotografia",
        "description": "Fotógrafo premiado com 15 anos de experiência em casamentos. Estilo fotojornalístico com ensaio pré-wedding, 800+ fotos editadas e álbum fine art.",
        "category_id": 2, "price": 8500, "rating": 4.9,
        "image_id": "photo-1537633552985-df8429e8048b",  # wedding photographer at work
    },
    {
        "name": "Vida em Frames",
        "description": "Estúdio especializado em fotografia e vídeo cinematográfico para eventos. Drone incluso, teaser de 2 minutos e filme completo em 4K.",
        "category_id": 2, "price": 12000, "rating": 4.9,
        "image_id": "photo-1542038784456-1ea8e935640e",  # camera equipment
    },
    {
        "name": "Priscila & Equipe Photo",
        "description": "Equipe feminina de fotógrafas com olhar sensível e artístico. Cobertura completa com 2 profissionais, fotos entregues em 30 dias com galeria online.",
        "category_id": 2, "price": 6800, "rating": 4.8,
        "image_id": "photo-1554048612-b6a482bc67e5",  # female photographer
    },
    {
        "name": "Drone View Eventos",
        "description": "Fotografia e filmagem aérea com drones profissionais DJI. Imagens em 4K, fotos panorâmicas 360° e transmissão ao vivo para convidados remotos.",
        "category_id": 2, "price": 4500, "rating": 4.7,
        "image_id": "photo-1473968512647-3e447244af8f",  # drone photography
    },

    # ── Buffet (cat_id=3) ──
    {
        "name": "Chef Renato Gastronomia",
        "description": "Chef com estrela Michelin oferecendo menu degustação de 7 etapas. Cozinha contemporânea brasileira com ingredientes orgânicos. Serviço completo para até 300 pessoas.",
        "category_id": 3, "price": 45000, "rating": 4.9,
        "image_id": "photo-1414235077428-338989a2e8c0",  # gourmet food presentation
    },
    {
        "name": "Sabores do Nordeste Buffet",
        "description": "Buffet com culinária nordestina sofisticada. Estações de tapioca gourmet, churrasco de picanha e bar de cachaças artesanais. A partir de 100 convidados.",
        "category_id": 3, "price": 18000, "rating": 4.8,
        "image_id": "photo-1555244162-803834f70033",  # buffet spread
    },
    {
        "name": "La Dolce Vita Catering",
        "description": "Buffet italiano autêntico com massas artesanais feitas na hora, antipasti variados e sobremesas tradicionais. Inclui chef de cozinha ao vivo.",
        "category_id": 3, "price": 25000, "rating": 4.8,
        "image_id": "photo-1565299624946-b28f40a0ae38",  # italian food
    },
    {
        "name": "Finger Food & Drinks Co.",
        "description": "Serviço de finger food sofisticado e bar de cocktails artesanais. Garçons treinados, menu personalizado e experiência gastronômica interativa.",
        "category_id": 3, "price": 15000, "rating": 4.7,
        "image_id": "photo-1530062845289-9109b2c9c868",  # cocktail food
    },
    {
        "name": "Doce Momento Confeitaria",
        "description": "Especializada em mesa de doces finos, bolo cenográfico artístico e bem-casados personalizados. Tradição de 20 anos em eventos de alto padrão.",
        "category_id": 3, "price": 8000, "rating": 4.8,
        "image_id": "photo-1535254973040-607b474cb50d",  # wedding dessert table
    },

    # ── Decoração (cat_id=4) ──
    {
        "name": "Jardim Secreto Decoração",
        "description": "Decoração romântica com flores naturais importadas, arcos florais e instalações suspensas. Projeto personalizado com maquete 3D e montagem premium.",
        "category_id": 4, "price": 22000, "rating": 4.9,
        "image_id": "photo-1519225421980-715cb0215aed",  # floral arch decoration
    },
    {
        "name": "Luxe Design Eventos",
        "description": "Design de interiores para eventos com mobiliário exclusivo, iluminação cênica e cenografia temática. Referência em casamentos de luxo e eventos corporativos.",
        "category_id": 4, "price": 35000, "rating": 4.9,
        "image_id": "photo-1478146059778-26028b07395a",  # luxury event design
    },
    {
        "name": "Ateliê Verde Vivo",
        "description": "Decoração sustentável com flores e folhagens nativas, vasos biodegradáveis e zero desperdício. Estética rústica-chique com consciência ambiental.",
        "category_id": 4, "price": 12000, "rating": 4.7,
        "image_id": "photo-1507290439931-a861b5a38b3c",  # sustainable floral
    },
    {
        "name": "Luz & Magia Iluminação",
        "description": "Projeto de iluminação decorativa para eventos. Varal de luzes, spots coloridos, projeção mapeada e efeitos especiais. Transforme qualquer espaço em mágico.",
        "category_id": 4, "price": 9500, "rating": 4.8,
        "image_id": "photo-1470229722913-7c0e2dbbafd3",  # string lights event
    },

    # ── Música (cat_id=5) ──
    {
        "name": "Jazz & Bossa Trio",
        "description": "Trio acústico de jazz e bossa nova para cerimônias e coquetéis. Repertório clássico e personalizado. Equipamento de som incluso para até 200 pessoas.",
        "category_id": 5, "price": 5500, "rating": 4.9,
        "image_id": "photo-1511192336575-5a79af67a629",  # jazz band
    },
    {
        "name": "Samba de Raiz Grupo",
        "description": "Roda de samba autêntica com 8 músicos, pagode e chorinho. Animação garantida com tamborim, pandeiro e cavaquinho. Ideal para festas após a cerimônia.",
        "category_id": 5, "price": 7000, "rating": 4.8,
        "image_id": "photo-1493225457124-a3eb161ffa5f",  # live band playing
    },
    {
        "name": "DJ Marina Santos",
        "description": "DJ feminina referência em eventos premium. Set list personalizado, equipamento Pioneer CDJ-3000, iluminação LED sincronizada e pista de dança interativa.",
        "category_id": 5, "price": 6000, "rating": 4.8,
        "image_id": "photo-1571266028243-e4733b0f0bb0",  # DJ equipment
    },
    {
        "name": "Coral Anjos da Música",
        "description": "Coral com 12 vozes para cerimônias religiosas e civis. Repertório sacro e popular. Possibilidade de harpista e violinista acompanhando.",
        "category_id": 5, "price": 4000, "rating": 4.7,
        "image_id": "photo-1507838153414-b4b713384a76",  # choir singing
    },

    # ── Vestidos (cat_id=6) ──
    {
        "name": "Maison Eleonora Bridal",
        "description": "Alta-costura em vestidos de noiva com tecidos franceses e italianos. Até 6 provas de ajuste, bordados à mão e entrega com embalagem especial.",
        "category_id": 6, "price": 18000, "rating": 4.9,
        "image_id": "photo-1594552072238-b8a33785b261",  # bridal dress
    },
    {
        "name": "Trajes Elegance Masculino",
        "description": "Alfaiataria sob medida para noivos e padrinhos. Ternos, smokings e fracs em tecidos importados. Inclui camisa, gravata e sapatos combinando.",
        "category_id": 6, "price": 5500, "rating": 4.8,
        "image_id": "photo-1507679799987-c73779587ccf",  # men's suit
    },
    {
        "name": "Studio 15 Debutantes",
        "description": "Vestidos para festas de 15 anos com design exclusivo. Coleção com mais de 200 modelos, customização completa e acessórios inclusos.",
        "category_id": 6, "price": 4000, "rating": 4.7,
        "image_id": "photo-1518611012118-696072aa579a",  # formal dress
    },
    {
        "name": "Brilho & Acessórios Noiva",
        "description": "Acessórios completos para noivas: véus artesanais, tiaras de cristal, joias delicadas, ligas e sapatos personalizados. Atendimento com hora marcada.",
        "category_id": 6, "price": 3500, "rating": 4.8,
        "image_id": "photo-1522748906645-95d8adfd52c7",  # bridal accessories
    },
]


def build_unsplash_url(photo_id, w=800, h=600):
    """Build direct Unsplash image URL from photo ID."""
    return f"https://images.unsplash.com/{photo_id}?w={w}&h={h}&fit=crop&q=80"


def main():
    print("=" * 60)
    print("  Celebra - Populate with real Unsplash images")
    print("=" * 60)

    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor(dictionary=True)

    # Check existing slugs
    cursor.execute("SELECT slug FROM venues")
    existing_venue_slugs = {r["slug"] for r in cursor.fetchall()}
    cursor.execute("SELECT slug FROM services")
    existing_service_slugs = {r["slug"] for r in cursor.fetchall()}

    # ── Insert Venues ──
    print(f"\n▸ Processing {len(NEW_VENUES)} new venues...")
    venues_added = 0
    for v in NEW_VENUES:
        slug = slugify(v["name"])
        if slug in existing_venue_slugs:
            print(f"  ⏭ Skipping (exists): {v['name']}")
            continue

        print(f"\n  📍 {v['name']} ({v['city']}, {v['state']})")

        # Download main image
        main_img_path = VENUES_DIR / f"{slug}.jpg"
        main_url = build_unsplash_url(v["images"][0])
        download_image(main_url, main_img_path)

        # Download gallery images
        gallery = []
        for i, img_id in enumerate(v["images"][1:], 1):
            gallery_path = VENUES_DIR / f"{slug}-gallery-{i}.jpg"
            gallery_url = build_unsplash_url(img_id)
            if download_image(gallery_url, gallery_path):
                gallery.append(f"images/venues/{slug}-gallery-{i}.jpg")

        cursor.execute("""
            INSERT INTO venues (name, slug, description, city, state, address, type, price, image, gallery, rating, capacity, active, created_at, updated_at)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 1, NOW(), NOW())
        """, (
            v["name"], slug, v["description"], v["city"], v["state"],
            v.get("address", ""), v["type"], v["price"],
            f"images/venues/{slug}.jpg",
            json.dumps(gallery),
            v["rating"], v["capacity"],
        ))
        venues_added += 1

    conn.commit()
    print(f"\n  ✅ {venues_added} venues added")

    # ── Insert Services ──
    print(f"\n▸ Processing {len(NEW_SERVICES)} new services...")
    services_added = 0
    for s in NEW_SERVICES:
        slug = slugify(s["name"])
        if slug in existing_service_slugs:
            print(f"  ⏭ Skipping (exists): {s['name']}")
            continue

        print(f"\n  🔧 {s['name']}")

        # Download image
        img_path = SERVICES_DIR / f"{slug}.jpg"
        img_url = build_unsplash_url(s["image_id"])
        download_image(img_url, img_path)

        cursor.execute("""
            INSERT INTO services (service_category_id, name, slug, description, price, image, rating, active, created_at, updated_at)
            VALUES (%s, %s, %s, %s, %s, %s, %s, 1, NOW(), NOW())
        """, (
            s["category_id"], s["name"], slug, s["description"],
            s["price"], f"images/services/{slug}.jpg", s["rating"],
        ))
        services_added += 1

    conn.commit()

    # ── Also update existing venues/services with real Unsplash images ──
    print("\n▸ Updating existing items with real images...")

    # Map of existing venue slugs to real Unsplash photo IDs
    EXISTING_VENUE_IMAGES = {
        "jardim-imperial": ["photo-1464366400600-7168b8af9bc3", "photo-1519167758481-83f550bb49b3", "photo-1505236858219-8359eb29e329", "photo-1562778612-e1e0cda9915c"],
        "villa-toscana": ["photo-1600585154340-be6161a56a0c", "photo-1600607687939-ce8a6c25118c", "photo-1543489822-c49534f3271f", "photo-1582653291997-079a1c04e5a1"],
        "espaco-luz": ["photo-1497366216548-37526070297c", "photo-1497366811353-6870744d04b2", "photo-1431540015239-7c95613a569b", "photo-1540575467063-178a50c2df87"],
        "fazenda-santa-clara": ["photo-1513836279014-a89f7a76ae86", "photo-1505765050516-f72dcac9c60e", "photo-1501785888041-af3ef285b470", "photo-1470770903676-69b98201ea1c"],
        "palazzo-eventos": ["photo-1519167758481-83f550bb49b3", "photo-1464366400600-7168b8af9bc3", "photo-1505236858219-8359eb29e329", "photo-1562778612-e1e0cda9915c"],
        "terraco-panoramico": ["photo-1470337458703-46ad1756a187", "photo-1414235077428-338989a2e8c0", "photo-1529290130-4ca3753253ae", "photo-1519167758481-83f550bb49b3"],
        "arena-beach-club": ["photo-1507525428034-b723cf961d3e", "photo-1544551763-46a013bb70d5", "photo-1520250497591-112f2f40a3f4", "photo-1571896349842-33c89424de2d"],
        "quinta-da-boa-vista": ["photo-1600596542815-ffad4c1539a9", "photo-1541971875076-8f970d573be6", "photo-1585320806297-9794b3e4eeae", "photo-1551882547-ff40c63fe5fa"],
        "espaco-gardens": ["photo-1416331108676-a22ccb276e35", "photo-1470770903676-69b98201ea1c", "photo-1501785888041-af3ef285b470", "photo-1517457373958-b7bdd4587205"],
        "recanto-das-aguas": ["photo-1505142468610-359e7d316be0", "photo-1483653085484-eb63c9f02547", "photo-1518998053901-5348d3961a04", "photo-1544551763-77932b56a3d2"],
        "solar-imperial": ["photo-1600585154526-990dced4db0d", "photo-1580587771525-78b9dba3b914", "photo-1583608205776-bfd35f0d9f83", "photo-1576013551627-0cc20b96c2a7"],
        "mansao-verde": ["photo-1512917774080-9991f1c4c750", "photo-1613490493576-7fde63acd811", "photo-1600596542815-ffad4c1539a9", "photo-1600585154340-be6161a56a0c"],
        "espaco-aquarela": ["photo-1478146059778-26028b07395a", "photo-1510076857177-7470076d4098", "photo-1464699908537-0954e50791ee", "photo-1501281668745-f7f57925c3b4"],
        "buffet-colonial": ["photo-1555244162-803834f70033", "photo-1565299624946-b28f40a0ae38", "photo-1530062845289-9109b2c9c868", "photo-1535254973040-607b474cb50d"],
        "chacara-verde-vida": ["photo-1513836279014-a89f7a76ae86", "photo-1505765050516-f72dcac9c60e", "photo-1470770903676-69b98201ea1c", "photo-1416331108676-a22ccb276e35"],
        "casa-branca-eventos": ["photo-1600585154340-be6161a56a0c", "photo-1582653291997-079a1c04e5a1", "photo-1543489822-c49534f3271f", "photo-1600607687939-ce8a6c25118c"],
        "espaco-do-lago": ["photo-1505142468610-359e7d316be0", "photo-1483653085484-eb63c9f02547", "photo-1501785888041-af3ef285b470", "photo-1513836279014-a89f7a76ae86"],
        "hotel-fazenda-serra": ["photo-1518780664697-55e3ad937233", "photo-1449158743715-0a90ebb6d2d8", "photo-1510798831971-661eb04b3739", "photo-1520250497591-112f2f40a3f4"],
        "palacio-cristal": ["photo-1519167758481-83f550bb49b3", "photo-1562778612-e1e0cda9915c", "photo-1464366400600-7168b8af9bc3", "photo-1505236858219-8359eb29e329"],
        "sitio-alto-da-serra": ["photo-1505765050516-f72dcac9c60e", "photo-1513836279014-a89f7a76ae86", "photo-1501785888041-af3ef285b470", "photo-1470770903676-69b98201ea1c"],
        "villa-real": ["photo-1600585154526-990dced4db0d", "photo-1580587771525-78b9dba3b914", "photo-1600596542815-ffad4c1539a9", "photo-1551882547-ff40c63fe5fa"],
    }

    EXISTING_SERVICE_IMAGES = {
        "studio-momento-perfeito": "photo-1537633552985-df8429e8048b",
        "lens-art-fotografia": "photo-1554048612-b6a482bc67e5",
        "buffet-sabor-arte": "photo-1555244162-803834f70033",
        "orquestra-sinfonia": "photo-1465847899084-d164df4dedc6",
        "atelier-noiva-perfeita": "photo-1594552072238-b8a33785b261",
        "flora-bella-decoracao": "photo-1519225421980-715cb0215aed",
        "banda-celebracao": "photo-1493225457124-a3eb161ffa5f",
        "pixel-memories": "photo-1473968512647-3e447244af8f",
        "harmonia-quarteto": "photo-1507838153414-b4b713384a76",
        "estudio-laura-ramos-fotografia": "photo-1542038784456-1ea8e935640e",
        "atelier-encanto": "photo-1518611012118-696072aa579a",
        "flora-festa-decoracao": "photo-1507290439931-a861b5a38b3c",
        "delicias-da-vovo-buffet": "photo-1414235077428-338989a2e8c0",
        "maison-du-reve": "photo-1522748906645-95d8adfd52c7",
        "dj-marcelo-vibe": "photo-1571266028243-e4733b0f0bb0",
        "gourmet-prime-buffet": "photo-1565299624946-b28f40a0ae38",
        "clique-perfeito-fotografia": "photo-1537633552985-df8429e8048b",
        "cenario-encantado": "photo-1478146059778-26028b07395a",
        "gourmet-fest": "photo-1530062845289-9109b2c9c868",
        "arte-em-flores": "photo-1519225421980-715cb0215aed",
        "buffet-real": "photo-1555244162-803834f70033",
        "studio-imagem": "photo-1554048612-b6a482bc67e5",
    }

    updated_venues = 0
    for slug, img_ids in EXISTING_VENUE_IMAGES.items():
        cursor.execute("SELECT id FROM venues WHERE slug = %s", (slug,))
        row = cursor.fetchone()
        if not row:
            continue

        print(f"  📍 Updating {slug}...")
        # Main image
        main_path = VENUES_DIR / f"{slug}.jpg"
        download_image(build_unsplash_url(img_ids[0]), main_path)

        # Gallery
        gallery = []
        for i, img_id in enumerate(img_ids[1:], 1):
            gpath = VENUES_DIR / f"{slug}-gallery-{i}.jpg"
            if download_image(build_unsplash_url(img_id), gpath):
                gallery.append(f"images/venues/{slug}-gallery-{i}.jpg")

        cursor.execute("""
            UPDATE venues SET image = %s, gallery = %s WHERE slug = %s
        """, (f"images/venues/{slug}.jpg", json.dumps(gallery), slug))
        updated_venues += 1

    updated_services = 0
    for slug, img_id in EXISTING_SERVICE_IMAGES.items():
        cursor.execute("SELECT id FROM services WHERE slug = %s", (slug,))
        row = cursor.fetchone()
        if not row:
            continue

        print(f"  🔧 Updating {slug}...")
        img_path = SERVICES_DIR / f"{slug}.jpg"
        download_image(build_unsplash_url(img_id), img_path)

        cursor.execute("""
            UPDATE services SET image = %s WHERE slug = %s
        """, (f"images/services/{slug}.jpg", slug))
        updated_services += 1

    conn.commit()

    # ── Summary ──
    cursor.execute("SELECT COUNT(*) as c FROM venues")
    total_venues = cursor.fetchone()["c"]
    cursor.execute("SELECT COUNT(*) as c FROM services")
    total_services = cursor.fetchone()["c"]

    print("\n" + "=" * 60)
    print(f"  ✅ {venues_added} new venues added, {updated_venues} existing updated")
    print(f"  ✅ {services_added} new services added, {updated_services} existing updated")
    print(f"  📊 Total: {total_venues} venues, {total_services} services")
    print("=" * 60)

    cursor.close()
    conn.close()


if __name__ == "__main__":
    main()
