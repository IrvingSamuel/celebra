#!/usr/bin/env python3
"""
Replace all venue and service images with real themed photos from LoremFlickr.
LoremFlickr serves real Creative Commons photos from Flickr matching keywords.
"""

import json
import time
import requests
import mysql.connector
from pathlib import Path

DB_CONFIG = {
    "host": "127.0.0.1",
    "user": "casamentos",
    "password": "guQ4zOsE5qqRemnKNcFR",
    "database": "casamentos",
}

BASE_DIR = Path(__file__).resolve().parent.parent
VENUES_DIR = BASE_DIR / "public" / "images" / "venues"
SERVICES_DIR = BASE_DIR / "public" / "images" / "services"


def flickr_url(keywords, w=800, h=600, seed=None):
    """Build LoremFlickr URL with keywords and optional seed for variety."""
    base = f"https://loremflickr.com/{w}/{h}/{keywords}"
    if seed is not None:
        base += f"?random={seed}"
    return base


def download(url, dest, retries=2):
    """Download image, retry on failure."""
    for attempt in range(retries + 1):
        try:
            r = requests.get(url, timeout=20, allow_redirects=True)
            if r.status_code == 200 and len(r.content) > 3000:
                ct = r.headers.get("content-type", "")
                if "image" in ct:
                    with open(dest, "wb") as f:
                        f.write(r.content)
                    return True
        except Exception:
            pass
        time.sleep(0.5)
    return False


# Keywords for venue types
VENUE_KEYWORDS = {
    "indoor": [
        "wedding,ballroom,reception",
        "event,hall,elegant",
        "banquet,hall,chandelier",
    ],
    "outdoor": [
        "outdoor,wedding,garden",
        "garden,ceremony,wedding",
        "outdoor,event,nature",
    ],
    "both": [
        "wedding,venue,elegant",
        "event,venue,garden",
        "wedding,reception,venue",
    ],
}

# Keywords for service categories
SERVICE_KEYWORDS = {
    2: "wedding,photographer,camera",       # Fotografia
    3: "catering,food,buffet,gourmet",       # Buffet
    4: "wedding,flowers,decoration,floral",  # Decoração
    5: "live,band,music,musician",           # Música
    6: "wedding,dress,bride,bridal",         # Vestidos
}

# Gallery keywords for venues (different angle/mood)
VENUE_GALLERY_KEYWORDS = {
    "indoor": [
        "chandelier,elegant,interior",
        "table,setting,dinner,event",
        "dance,floor,party,reception",
    ],
    "outdoor": [
        "garden,lights,evening",
        "ceremony,arch,flowers",
        "sunset,outdoor,celebration",
    ],
    "both": [
        "garden,house,event",
        "reception,table,decor",
        "celebration,venue,lights",
    ],
}


def main():
    print("=" * 60)
    print("  Replacing all images with themed LoremFlickr photos")
    print("=" * 60)

    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor(dictionary=True)

    VENUES_DIR.mkdir(parents=True, exist_ok=True)
    SERVICES_DIR.mkdir(parents=True, exist_ok=True)

    # ── Update ALL venue images ──
    cursor.execute("SELECT id, slug, type, name FROM venues ORDER BY id")
    venues = cursor.fetchall()
    print(f"\n▸ Updating {len(venues)} venues with themed images...")

    seed = 1
    for v in venues:
        slug = v["slug"]
        vtype = v["type"]
        keywords_list = VENUE_KEYWORDS.get(vtype, VENUE_KEYWORDS["both"])
        gallery_kw_list = VENUE_GALLERY_KEYWORDS.get(vtype, VENUE_GALLERY_KEYWORDS["both"])

        # Main image
        kw = keywords_list[seed % len(keywords_list)]
        main_path = VENUES_DIR / f"{slug}.jpg"
        url = flickr_url(kw, seed=seed)
        ok = download(url, main_path)
        seed += 1
        time.sleep(0.3)  # Rate limit

        # Gallery (3 images)
        gallery = []
        for i in range(1, 4):
            gkw = gallery_kw_list[(seed + i) % len(gallery_kw_list)]
            gpath = VENUES_DIR / f"{slug}-gallery-{i}.jpg"
            gurl = flickr_url(gkw, seed=seed + i * 100)
            if download(gurl, gpath):
                gallery.append(f"images/venues/{slug}-gallery-{i}.jpg")
            time.sleep(0.3)
            seed += 1

        cursor.execute(
            "UPDATE venues SET image=%s, gallery=%s WHERE id=%s",
            (f"images/venues/{slug}.jpg", json.dumps(gallery), v["id"]),
        )
        status = "✓" if ok else "✗"
        print(f"  {status} {v['name']} ({vtype}) - {len(gallery)} gallery imgs")

    conn.commit()

    # ── Update ALL service images ──
    cursor.execute("SELECT id, slug, service_category_id, name FROM services ORDER BY id")
    services = cursor.fetchall()
    print(f"\n▸ Updating {len(services)} services with themed images...")

    seed = 500
    for s in services:
        slug = s["slug"]
        cat_id = s["service_category_id"]
        kw = SERVICE_KEYWORDS.get(cat_id, "wedding,event,service")

        img_path = SERVICES_DIR / f"{slug}.jpg"
        url = flickr_url(kw, seed=seed)
        ok = download(url, img_path)
        seed += 1
        time.sleep(0.3)

        cursor.execute(
            "UPDATE services SET image=%s WHERE id=%s",
            (f"images/services/{slug}.jpg", s["id"]),
        )
        status = "✓" if ok else "✗"
        print(f"  {status} {s['name']} (cat={cat_id})")

    conn.commit()

    # ── Summary ──
    cursor.execute("SELECT COUNT(*) as c FROM venues")
    tv = cursor.fetchone()["c"]
    cursor.execute("SELECT COUNT(*) as c FROM services")
    ts = cursor.fetchone()["c"]
    print(f"\n{'='*60}")
    print(f"  ✅ Updated {len(venues)} venues (main + gallery)")
    print(f"  ✅ Updated {len(services)} services")
    print(f"  📊 Total: {tv} venues, {ts} services")
    print(f"{'='*60}")

    cursor.close()
    conn.close()


if __name__ == "__main__":
    main()
