<?php

namespace App\Services;

use GdImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Stores admin uploads on the public disk (storage/app/public → public/storage).
 *
 * Every image is decoded and re-encoded with GD: this strips EXIF/GPS data and anything
 * hidden in the file, fixes phone-camera rotation and caps the size. Photos also get an
 * 800px copy ("name@800.jpg") that <x-media> serves to phones via srcset.
 *
 * Returned paths are relative to public/ (e.g. "storage/products/abc.jpg"), the same
 * format as the bundled photos ("images/placeholders/…"), so views treat both alike.
 */
class ImageService
{
    private const DISK = 'public';

    private const MAX_WIDTH = 2000;

    private const SMALL_WIDTH = 800;

    /** Photo → JPEG (quality 82) + 800px copy. */
    public function storePhoto(UploadedFile $file, string $folder): string
    {
        $image = $this->decode($file);
        $name = $folder.'/'.Str::uuid().'.jpg';

        $this->put($name, $this->resize($image, self::MAX_WIDTH), 'jpg');

        if (imagesx($image) > self::SMALL_WIDTH) {
            $this->put(substr($name, 0, -4).'@800.jpg', $this->resize($image, self::SMALL_WIDTH), 'jpg');
        }

        return 'storage/'.$name;
    }

    /** Logo → PNG, transparency kept. */
    public function storeLogo(UploadedFile $file): string
    {
        $name = 'branding/'.Str::uuid().'.png';
        $this->put($name, $this->resize($this->decode($file), 1200), 'png');

        return 'storage/'.$name;
    }

    /** Deletes an uploaded image (and its small copy). Bundled images under images/ are never touched. */
    public function delete(?string $path): void
    {
        if (! $path || ! str_starts_with($path, 'storage/')) {
            return;
        }

        $relative = substr($path, strlen('storage/'));
        Storage::disk(self::DISK)->delete([$relative, preg_replace('/\.jpg$/', '@800.jpg', $relative)]);
    }

    private function decode(UploadedFile $file): GdImage
    {
        $image = @imagecreatefromstring((string) file_get_contents($file->getRealPath()));

        if (! $image instanceof GdImage) {
            throw new RuntimeException('The file could not be read as an image.');
        }

        return $this->orient($image, $file);
    }

    /** Applies the EXIF orientation from phone cameras, then the EXIF data is dropped on re-encode. */
    private function orient(GdImage $image, UploadedFile $file): GdImage
    {
        if (! function_exists('exif_read_data') || ! in_array($file->getMimeType(), ['image/jpeg', 'image/jpg'], true)) {
            return $image;
        }

        $orientation = @exif_read_data($file->getRealPath())['Orientation'] ?? 1;

        return match ((int) $orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }

    private function resize(GdImage $image, int $maxWidth): GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);

        $targetW = min($width, $maxWidth);
        $targetH = (int) round($height * $targetW / $width);

        $canvas = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $targetW, $targetH, $width, $height);

        return $canvas;
    }

    private function put(string $path, GdImage $image, string $format): void
    {
        if ($format === 'jpg') {
            // JPEG has no transparency: flatten onto the site's off-white.
            $flat = imagecreatetruecolor(imagesx($image), imagesy($image));
            imagefill($flat, 0, 0, imagecolorallocate($flat, 245, 245, 242));
            imagecopy($flat, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
            $image = $flat;
        }

        ob_start();
        $format === 'png' ? imagepng($image, null, 6) : imagejpeg($image, null, 82);
        Storage::disk(self::DISK)->put($path, (string) ob_get_clean());
    }
}
