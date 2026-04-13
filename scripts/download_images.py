#!/usr/bin/env python3
"""
Download placeholder images from picsum.photos (Lorem Picsum) for venues and services.
"""

import os
import time
import requests
import mysql.connector
from pathlib import Path
from urllib.parse import urlparse

DB_CONFIG = {
    "host": "127.0.0.1",
    "user": "casamentos",
    "password": "guQ4zOsE5qqRemnKNcFR",
    "database": "casamentos",
}

BASE_DIR = Path(__file__).resolve().parent.parent
VENUES_DIR = BASE_DIR / "public" / "images" / "venues"
SERVICES_DIR = BASE_DIR / "public" / "images" / "services"

HEADERS = {
    "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36",
}

def slugify(text):
    import unicodedata, re
    text = unicodedata.normalize("NFKD", text).encode("ascii", "ignore").decode("ascii")
    text = re.sub(r"[^\w\s-]", "", text.lower())
    return re.sub(r"[-\s]+", "-", text).strip("-")


def download_picsum(dest_dir, filename, seed):
    """Download from picsum.photos with a seed for consistent images"""
    dest_dir.mkdir(parents=True, exist_ok=True)
    filepath = dest_dir / filename
    if filepath.exists():
        return str(filepath.relative_to(BASE_DIR / "public"))

    url = f"https://picsum.photos/seed/{seed}/800/600"
    try:
        resp = requests.get(url, headers=HEADERS, timeout=20, allow_redirects=True)
        resp.raise_for_status()
        with open(filepath, "wb") as f:
            f.write(resp.content)
        print(f"  📥 {filename} ({len(resp.content) // 1024}KB)")
        return str(filepath.relative_to(BASE_DIR / "public"))
    except Exception as e:
        print(f"  ⚠️  Failed {filename}: {e}")
        return None


def main():
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor(dictionary=True)

    # ── Venues ────────────────────────────────────
    cursor.execute("SELECT id, name, slug, image FROM venues WHERE image IS NULL OR image = ''")
    venues = cursor.fetchall()
    print(f"📸 Downloading images for {len(venues)} venues...")

    for i, v in enumerate(venues):
        seed = f"venue-{v['slug']}-{v['id']}"
        filename = f"{v['slug']}.jpg"
        path = download_picsum(VENUES_DIR, filename, seed)
        if path:
            cursor.execute("UPDATE venues SET image = %s WHERE id = %s", (path, v["id"]))
        time.sleep(0.3)

    # Also download gallery images (3 per venue)
    cursor.execute("SELECT id, slug, gallery FROM venues WHERE gallery IS NULL")
    venues_no_gallery = cursor.fetchall()
    print(f"\n🖼️  Downloading gallery images for {len(venues_no_gallery)} venues...")

    import json
    for v in venues_no_gallery:
        gallery = []
        for j in range(3):
            seed = f"venue-gallery-{v['slug']}-{j}"
            filename = f"{v['slug']}-gallery-{j+1}.jpg"
            path = download_picsum(VENUES_DIR, filename, seed)
            if path:
                gallery.append(path)
            time.sleep(0.3)
        if gallery:
            cursor.execute("UPDATE venues SET gallery = %s WHERE id = %s", (json.dumps(gallery), v["id"]))

    # ── Services ──────────────────────────────────
    cursor.execute("SELECT id, name, slug, image FROM services WHERE image IS NULL OR image = ''")
    services = cursor.fetchall()
    print(f"\n📸 Downloading images for {len(services)} services...")

    for i, s in enumerate(services):
        seed = f"service-{s['slug']}-{s['id']}"
        filename = f"{s['slug']}.jpg"
        path = download_picsum(SERVICES_DIR, filename, seed)
        if path:
            cursor.execute("UPDATE services SET image = %s WHERE id = %s", (path, s["id"]))
        time.sleep(0.3)

    conn.commit()
    cursor.close()
    conn.close()

    print(f"\n🎉 Done! Updated {len(venues)} venue images, {len(venues_no_gallery)} galleries, {len(services)} service images.")


if __name__ == "__main__":
    main()
