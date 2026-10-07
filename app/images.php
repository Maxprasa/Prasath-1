<?php
// Image processing with GD: resize to a few widths, save as WebP. Re-encoding drops all EXIF/GPS data.
declare(strict_types=1);

const MAX_UPLOAD_BYTES = 30 * 1024 * 1024;

/** Load JPEG/PNG/WebP into a GD image with EXIF rotation applied. Throws on anything else. */
function image_load(string $path): \GdImage
{
    @ini_set('memory_limit', '768M');
    $info = @getimagesize($path);
    if (!$info) {
        throw new RuntimeException('Not an image');
    }
    [$w, $h, $type] = $info;
    if ($w * $h > 60_000_000) {
        throw new RuntimeException('Image too large (over 60 megapixels)');
    }
    $img = match ($type) {
        IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
        IMAGETYPE_PNG => @imagecreatefrompng($path),
        IMAGETYPE_WEBP => @imagecreatefromwebp($path),
        default => false,
    };
    if (!$img) {
        throw new RuntimeException('Only JPG, PNG or WebP images are allowed');
    }
    if ($type === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
        $exif = @exif_read_data($path);
        $o = (int) ($exif['Orientation'] ?? 1);
        if (in_array($o, [2, 4, 5, 7], true)) {
            imageflip($img, IMG_FLIP_HORIZONTAL); // mirrored orientations: flip, then rotate like 1/3/8/6
            $o = [2 => 1, 4 => 3, 5 => 8, 7 => 6][$o];
        }
        $rot = [3 => 180, 6 => -90, 8 => 90][$o] ?? 0;
        if ($rot) {
            $img = imagerotate($img, $rot, 0);
        }
    }
    return $img;
}

/** Save resized WebP copies. Returns the widths written. */
function image_save_widths(\GdImage $img, string $base, array $widths, int $quality = 80): array
{
    $sw = imagesx($img);
    $sh = imagesy($img);
    $done = [];
    foreach ($widths as $w) {
        if ($w > $sw) {
            $w = $sw; // never upscale: the largest copy is the original width
        }
        if (in_array($w, $done, true)) {
            continue;
        }
        $h = (int) round($sh * $w / $sw);
        $dst = imagecreatetruecolor($w, $h);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $img, 0, 0, 0, 0, $w, $h, $sw, $sh);
        $ok = imagewebp($dst, "$base-$w.webp", $quality);
        imagedestroy($dst);
        if (!$ok) {
            foreach ($done as $d) {
                @unlink("$base-$d.webp");
            }
            @unlink("$base-$w.webp");
            throw new RuntimeException('Could not save the image (too tall, or the disk is full)');
        }
        $done[] = $w;
        if ($w === $sw) {
            break;
        }
    }
    return $done;
}

/**
 * Process one photo file into /media/photos. Returns the photo record (without id).
 * $nameHint becomes part of the file name (descriptive names are better for search).
 */
function photo_process(string $path, string $nameHint = 'kuva'): array
{
    $img = image_load($path);
    $file = slugify($nameHint) . '-' . bin2hex(random_bytes(4));
    $widths = image_save_widths($img, MEDIA_DIR . '/photos/' . $file, PHOTO_WIDTHS);
    $rec = ['file' => $file, 'w' => imagesx($img), 'h' => imagesy($img), 'widths' => $widths, 'alt' => ['fi' => '', 'en' => ''], 'added' => date('c')];
    imagedestroy($img);
    return $rec;
}

function photo_delete_files(array $p): void
{
    foreach ($p['widths'] as $w) {
        @unlink(MEDIA_DIR . '/photos/' . $p['file'] . "-$w.webp");
    }
}

/** Extract an 11-character YouTube id from a link or id. */
function youtube_id(string $s): ?string
{
    $s = trim($s);
    if (preg_match('/^[\w-]{11}$/', $s)) {
        return $s;
    }
    if (preg_match('~(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/|v/))([\w-]{11})~', $s, $m)) {
        return $m[1];
    }
    return null;
}

/** Download the YouTube thumbnail once and keep it on our server (visitors never contact YouTube before a click). */
function video_poster_fetch(string $id): bool
{
    foreach (['maxresdefault', 'sddefault', 'hqdefault'] as $name) {
        $ctx = stream_context_create(['http' => ['timeout' => 15, 'ignore_errors' => true]]);
        $data = @file_get_contents("https://i.ytimg.com/vi/$id/$name.jpg", false, $ctx);
        if ($data === false || strlen($data) < 2000) {
            continue;
        }
        $img = @imagecreatefromstring($data);
        if (!$img) {
            continue;
        }
        // Crop to 16:9 (sd/hq thumbnails have black bars)
        $w = imagesx($img);
        $h = imagesy($img);
        $ch = (int) round($w * 9 / 16);
        if ($ch < $h) {
            $crop = imagecrop($img, ['x' => 0, 'y' => (int) (($h - $ch) / 2), 'width' => $w, 'height' => $ch]);
            imagedestroy($img);
            $img = $crop;
        }
        foreach ([640, 1280] as $tw) {
            $dst = imagecreatetruecolor($tw, (int) round($tw * 9 / 16));
            imagecopyresampled($dst, $img, 0, 0, 0, 0, imagesx($dst), imagesy($dst), imagesx($img), imagesy($img));
            $ok = imagewebp($dst, MEDIA_DIR . "/videos/$id-$tw.webp", 78);
            imagedestroy($dst);
            if (!$ok) {
                imagedestroy($img);
                return false;
            }
        }
        imagedestroy($img);
        return true;
    }
    return false;
}
