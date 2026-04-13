#!/usr/bin/env python3
"""
Final image assignment script using REAL Unsplash photo IDs
obtained from the Unsplash search API (napi/search/photos).
All IDs have been verified to return wedding-related images.
"""
import requests
import mysql.connector
import json
import os
import sys
import time

DB_CONFIG = {
    'host': '127.0.0.1',
    'database': 'casamentos',
    'user': 'casamentos',
    'password': 'guQ4zOsE5qqRemnKNcFR',
}

BASE_DIR = '/home/eflow-casamentos/htdocs/casamentos.eflow.space/public'

# Load raw URLs from API results
with open('/home/eflow-casamentos/htdocs/casamentos.eflow.space/scripts/unsplash_ids.json') as f:
    API_DATA = json.load(f)

def get_raw_url(category, index):
    """Get the raw URL for a photo from the API data."""
    items = API_DATA.get(category, [])
    if not items:
        return None
    item = items[index % len(items)]
    raw = item['raw']
    return raw

def build_url(raw_url, w=800, h=600):
    """Build a sized URL from a raw Unsplash URL."""
    sep = '&' if '?' in raw_url else '?'
    return f"{raw_url}{sep}w={w}&h={h}&fit=crop&q=80"

# ============================================================
# Curated indoor venue IDs from indoor2 + indoor3 searches
# (non-premium only, verified wedding reception halls)
# ============================================================
INDOOR_VENUE_IDS = [
    ('indoor2', 1),   # 6QI4RM5ybEQ - Elegant wedding reception hall with guests
    ('indoor2', 2),   # HDgu-wT3OTs - Elegant banquet hall with long tables
    ('indoor2', 4),   # jds_OTZ8c7w - Long tables in grand hall (skip premium idx 3/4)
    ('indoor2', 5),   # iDr7G1kh0Cs - Elegant banquet hall long tables/chairs
    ('indoor2', 6),   # XiZd25Fk7H0 - Long tables in grand hall
    ('indoor2', 9),   # ROOE-zHpZYU - Wedding tables with candles/greenery
    ('indoor2', 10),  # FeO_txj9yVc - Elegant tables with floral
    ('indoor2', 14),  # sHEwH4lgGrY - Castle wedding reception
    ('indoor2', 16),  # 7K67WDBrcIs - Reception Tables Casa Loma
    ('indoor_venue', 0),  # FKLXn1mw3Bg - Grand ballroom with chandeliers
]

# Outdoor venue IDs (ceremony, garden, beach)
OUTDOOR_VENUE_IDS = [
    ('outdoor_venue', 1),   # nyQi_13Zl3A - Lush forest garden
    ('outdoor_venue', 3),   # dJgGLX4F-eM - Ceremony with chairs
    ('outdoor_venue', 5),   # _ObILA1XpSQ - White chairs and arch
    ('outdoor_venue', 6),   # HyvqNe3vaxQ - Outdoor ceremony
    ('outdoor_venue', 7),   # JyAMGd2WYZk - Outdoor ceremony floral
    ('outdoor_venue', 9),   # Fpgq9v5x7gU - Ceremony guests outdoors
    ('outdoor_venue', 10),  # ohEq2BC8THM - Garden with rustic barn
    ('outdoor_venue', 11),  # 3ZzyA3XIVSk - Chairs and small chapel
    ('outdoor_venue', 13),  # _a4OJrB5Q4A - Floral arch green
    ('outdoor_venue', 14),  # oA3Qir6_-XM - Outdoor ceremony
    ('outdoor_beach', 1),   # MA2A-5pRnsU - Beach ceremony setup
    ('outdoor_beach', 2),   # 3pzxhvn4a0A - Beach wedding arch
    ('outdoor_beach', 3),   # Qa-PDcZVdHg - Wooden deck by water
    ('outdoor_beach', 9),   # -oFxHLYKgLA - Ocean wedding
    ('outdoor_venue', 2),   # 7XtF4eMSg5s - Sunny garden
    ('outdoor_venue', 4),   # ozrrdXH3hFQ - Chairs with flowers
    ('outdoor_venue', 8),   # b32oKB54P2E - Hanging flower baskets
    ('outdoor_beach', 19),  # evMXfNP1ZZ8 - Bride/groom on beach
    ('outdoor_beach', 18),  # Mv-u4O_cULA - Bride and father on pier
    ('outdoor_beach', 5),   # NEWrTHWxfFk - Wedding on beach
]

# Both/resort venue IDs (luxury resorts with pools)
BOTH_VENUE_IDS = [
    ('resort_venue', 0),   # 4WLZdtWQ0lw - Pool umbrellas
    ('resort_venue', 2),   # Z76GWwaTCjQ - Aerial resort pool
    ('resort_venue', 3),   # r6Ec-FynhW4 - Aerial beach resort
    ('resort_venue', 5),   # v1W7X00fk7Q - Aerial resort pool
    ('resort_venue', 6),   # 9jaJ6Kjt-KA - Aerial resort pool
    ('resort_venue', 7),   # OhQpBH7M69g - Bird's eye beach resort
    ('resort_venue', 10),  # pt0ZBfB06gI - Pool surrounded by trees
    ('resort_venue', 11),  # QQALjksA15g - Pool with lounge chair
    ('resort_venue', 13),  # b8fsuhP-c14 - Resort surrounded by mountains
    ('resort_venue', 14),  # WSyXG-zDm_U - House surrounded by trees
    ('resort_venue', 1),   # WQLIxSEczSA - Aerial house pool
]

# Gallery images - mix of ceremony, couple, table, decoration
GALLERY_IDS = [
    ('photography', 0),     # Bride and groom posing
    ('photography', 1),     # White dress couple
    ('photography', 4),     # Dancing on mountain
    ('photography', 8),     # Man/woman wedding attire
    ('photography', 9),     # Lake Tahoe wedding
    ('decoration', 0),      # Wedding cake/table flowers
    ('decoration', 1),      # Wedding center piece
    ('decoration', 2),      # Wildflower rustic table
    ('decoration', 3),      # Flowers decor greenery
    ('decoration', 4),      # Bouquet on wooden chair
    ('decoration', 7),      # Tiffany green decoration
    ('decoration', 9),      # Wedding flower table
    ('cake', 0),            # White wedding cake
    ('cake', 2),            # Cake with frosting/flowers
    ('cake', 5),            # Tiered wedding cake
    ('buffet', 0),          # Catering buffet
    ('buffet', 2),          # Wedding appetizer
    ('indoor2', 9),         # Tables with candles
    ('indoor2', 10),        # Tables with floral
    ('outdoor_venue', 1),   # Forest garden setting
    ('outdoor_venue', 7),   # Chairs and chapel
    ('outdoor_beach', 1),   # Beach ceremony
    ('outdoor_beach', 3),   # Wooden deck wedding
    ('photography', 13),    # Focus on rings
]

# Photography service images
PHOTO_IDS = [
    ('photography', 0),   # Bride/groom posing
    ('photography', 1),   # White dress couple
    ('photography', 4),   # Dancing on mountain
    ('photography', 8),   # Wedding attire
    ('photography', 9),   # Lake Tahoe wedding
    ('photography', 2),   # Nepali bride/groom
    ('photography', 3),   # Another couple
    ('photography', 13),  # Focus on rings
    ('photography', 14),  # Indian couple
]

# Buffet service images (buffet + cake)
BUFFET_IDS = [
    ('buffet', 0),   # Catering buffet party
    ('buffet', 2),   # Wedding appetizer
    ('buffet', 4),   # Server with tray
    ('buffet', 8),   # Buffet concept
    ('buffet', 9),   # Outdoor banquet food
    ('cake', 0),     # White wedding cake
    ('cake', 2),     # Cake with frosting
    ('cake', 3),     # Gold monogram cake
    ('cake', 5),     # Tiered wedding cake
    ('buffet', 12),  # Lavish buffet display
]

# Decoration service images
DECOR_IDS = [
    ('decoration', 1),   # Wedding center piece
    ('decoration', 2),   # Wildflower rustic table
    ('decoration', 3),   # Flowers decor greenery
    ('decoration', 4),   # Bouquet on wooden chair
    ('decoration', 5),   # Pink roses in vase
    ('decoration', 7),   # Tiffany green
    ('decoration', 9),   # Wedding flower table
    ('decoration', 12),  # White flowers on table
]

# Music service images
MUSIC_IDS = [
    ('music', 0),    # DJ turntable
    ('music', 1),    # Orchestral concert
    ('music', 2),    # Two DJs stage
    ('music', 4),    # DJ laptop
    ('music', 5),    # DJ mixing party
    ('music', 7),    # DJ large screen
    ('music', 10),   # DJs red lights
    ('music', 11),   # DJ performing
    ('music', 13),   # DJ controller stage
]

# Dress service images
DRESS_IDS = [
    ('dress', 0),    # Dress hanging on black
    ('dress', 2),    # Strapless gown mannequin
    ('dress', 4),    # Dress on mannequin curtain
    ('dress', 6),    # Three mannequins gowns
    ('dress', 7),    # White dress on hanger
    ('dress', 8),    # Dress in front of window
    ('dress', 9),    # Dress on wooden door
]


def download_image(raw_url, dest_path, width=800, height=600):
    """Download an image from Unsplash using its raw URL."""
    url = build_url(raw_url, width, height)
    try:
        r = requests.get(url, timeout=15)
        if r.status_code == 200 and len(r.content) > 5000:
            os.makedirs(os.path.dirname(dest_path), exist_ok=True)
            with open(dest_path, 'wb') as f:
                f.write(r.content)
            return True
        else:
            print(f"    ❌ HTTP {r.status_code} (size: {len(r.content)})")
            return False
    except Exception as e:
        print(f"    ❌ Error: {e}")
        return False


def main():
    conn = mysql.connector.connect(**DB_CONFIG)
    cur = conn.cursor(dictionary=True)

    # ---- VENUES ----
    cur.execute("SELECT id, slug, type FROM venues ORDER BY id")
    venues = cur.fetchall()

    print(f"🏛️ Updating {len(venues)} venues...")

    indoor_idx = 0
    outdoor_idx = 0
    both_idx = 0
    gallery_idx = 0
    ok_count = 0
    fail_count = 0

    for v in venues:
        slug = v['slug']
        vtype = v['type']

        # Pick main image based on venue type
        if vtype == 'indoor':
            cat, idx = INDOOR_VENUE_IDS[indoor_idx % len(INDOOR_VENUE_IDS)]
            indoor_idx += 1
        elif vtype == 'outdoor':
            cat, idx = OUTDOOR_VENUE_IDS[outdoor_idx % len(OUTDOOR_VENUE_IDS)]
            outdoor_idx += 1
        else:  # both
            cat, idx = BOTH_VENUE_IDS[both_idx % len(BOTH_VENUE_IDS)]
            both_idx += 1

        raw_url = get_raw_url(cat, idx)
        if not raw_url:
            print(f"  ⚠️ No URL for {slug}")
            continue

        # Download main image
        main_path = f"{BASE_DIR}/images/venues/{slug}.jpg"
        ok = download_image(raw_url, main_path)
        main_db = f"images/venues/{slug}.jpg"

        # Download 3 gallery images
        gallery_paths = []
        for g in range(1, 4):
            gcat, gidx = GALLERY_IDS[gallery_idx % len(GALLERY_IDS)]
            gallery_idx += 1
            graw = get_raw_url(gcat, gidx)
            if graw:
                gal_path = f"{BASE_DIR}/images/venues/{slug}-{g}.jpg"
                download_image(graw, gal_path)
                gallery_paths.append(f"images/venues/{slug}-{g}.jpg")

        # Update database
        cur.execute(
            "UPDATE venues SET image=%s, gallery=%s WHERE id=%s",
            (main_db, json.dumps(gallery_paths), v['id'])
        )

        status = "✅" if ok else "⚠️"
        if ok:
            ok_count += 1
        else:
            fail_count += 1
        print(f"  {status} {slug} ({vtype})")

    conn.commit()
    print(f"  → {ok_count} OK, {fail_count} failed")

    # ---- SERVICES ----
    cur.execute("""
        SELECT s.id, s.slug, s.service_category_id, c.name as cat
        FROM services s JOIN service_categories c ON s.service_category_id = c.id
        ORDER BY s.service_category_id, s.id
    """)
    services = cur.fetchall()

    print(f"\n🎯 Updating {len(services)} services...")

    cat_indices = {}
    ok_count = 0
    fail_count = 0

    for s in services:
        slug = s['slug']
        cat = s['cat']
        cat_id = s['service_category_id']

        if cat_id not in cat_indices:
            cat_indices[cat_id] = 0

        idx = cat_indices[cat_id]

        # Pick image list based on category
        if cat == 'Fotografia':
            id_list = PHOTO_IDS
        elif cat == 'Buffet':
            id_list = BUFFET_IDS
        elif cat == 'Decoração':
            id_list = DECOR_IDS
        elif cat == 'Música':
            id_list = MUSIC_IDS
        elif cat == 'Vestidos':
            id_list = DRESS_IDS
        else:
            id_list = GALLERY_IDS

        scat, sidx = id_list[idx % len(id_list)]
        cat_indices[cat_id] = idx + 1

        raw_url = get_raw_url(scat, sidx)
        if not raw_url:
            print(f"  ⚠️ No URL for {slug}")
            continue

        dest = f"{BASE_DIR}/images/services/{slug}.jpg"
        ok = download_image(raw_url, dest)
        db_path = f"images/services/{slug}.jpg"

        cur.execute("UPDATE services SET image=%s WHERE id=%s", (db_path, s['id']))

        status = "✅" if ok else "⚠️"
        if ok:
            ok_count += 1
        else:
            fail_count += 1
        print(f"  {status} {slug} ({cat})")

    conn.commit()
    cur.close()
    conn.close()

    print(f"  → {ok_count} OK, {fail_count} failed")
    print(f"\n🎉 Done!")


if __name__ == '__main__':
    main()
