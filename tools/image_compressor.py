from PIL import Image

def compress_image(image_path, output_path, quality):
    image = Image.open(image_path)
    image.save(output_path, optimize=True, quality=quality)

    print(f"Image compressed to {output_path}")
