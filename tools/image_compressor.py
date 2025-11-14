import os
from PIL import Image

# Folder sumber dan tujuan
SOURCE_DIR = './assets/originals'
OUTPUT_DIR = './assets/compressed'

# Buat folder output jika belum ada
os.makedirs(OUTPUT_DIR, exist_ok=True)

# Iterasi semua file di folder sumber
for filename in os.listdir(SOURCE_DIR):
    if filename.lower().endswith('.png'):
        input_path = os.path.join(SOURCE_DIR, filename)
        output_path = os.path.join(OUTPUT_DIR, filename)

        try:
            # Buka gambar
            with Image.open(input_path) as img:
                # Konversi ke mode yang efisien
                img = img.convert("P", palette=Image.ADAPTIVE)

                # Simpan dengan optimasi dan kompresi
                img.save(output_path, optimize=True, compress_level=9)
                print(f"✅ {filename} dikompres dan disimpan ke {output_path}")
        except Exception as e:
            print(f"❌ Gagal memproses {filename}: {e}")

print("🎉 Semua gambar PNG berhasil diproses!")
