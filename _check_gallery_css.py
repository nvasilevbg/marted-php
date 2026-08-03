import sys, re
sys.stdout.reconfigure(encoding="utf-8")

css = open(r"C:\PROJECTS\marted-php\assets\css\style.css", "r", encoding="utf-8").read()

# Find projectGallery CSS
gallery_rules = re.findall(r'\.projectGallery[^{]*\{[^}]*\}', css)
for r in gallery_rules:
    print(r.strip()[:200])

# Find projectCard img CSS (mosaic)
card_rules = re.findall(r'\.projectCard[^{]*img[^{]*\{[^}]*\}', css)
for r in card_rules:
    print(f"\nCard: {r.strip()[:200]}")

# Find heroPhoto img
hero_rules = re.findall(r'\.heroPhoto[^{]*img[^{]*\{[^}]*\}', css)
for r in hero_rules:
    print(f"\nHero: {r.strip()[:200]}")

# Find projectCover
cover_rules = re.findall(r'\.projectCover\{[^}]*\}', css)
for r in cover_rules:
    print(f"\nCover: {r.strip()[:200]}")
