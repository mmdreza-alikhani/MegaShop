<?php

namespace Database\Seeders\Support;

/**
 * سازندهٔ تصاویر جایگزین (placeholder) برای داده‌های نمونه.
 *
 * چون فایل‌های تصویر واقعی همراه مخزن نیستند، این کلاس با GD برای هر
 * محصول/بنر/پست تصویری هماهنگ با پالت رنگی می‌سازد تا فروشگاه بعد از
 * سید کردن، ظاهری طبیعی و منسجم داشته باشد. اگر فایل از قبل موجود باشد،
 * دوباره ساخته نمی‌شود.
 */
class PlaceholderImage
{
    /** پالت‌های رنگی: [رنگ شروع، رنگ پایان] */
    public const PALETTES = [
        ['#1e3a8a', '#3b82f6'],
        ['#0f172a', '#475569'],
        ['#065f46', '#10b981'],
        ['#7c2d12', '#f97316'],
        ['#7f1d1d', '#ef4444'],
        ['#4c1d95', '#8b5cf6'],
        ['#134e4a', '#14b8a6'],
        ['#78350f', '#eab308'],
        ['#831843', '#ec4899'],
        ['#0c4a6e', '#0ea5e9'],
        ['#365314', '#84cc16'],
        ['#3f3f46', '#a1a1aa'],
    ];

    /**
     * انتخاب پالت پایدار بر اساس یک رشته (مثلاً کد محصول).
     */
    public static function paletteIndex(string $seed): int
    {
        return abs(crc32($seed)) % count(self::PALETTES);
    }

    /**
     * ساخت تصویر و ذخیره در storage/app/public/{relativePath}
     *
     * @return string نام فایل ذخیره‌شده
     */
    public static function make(
        string $relativePath,
        int $width,
        int $height,
        string $title = '',
        string $subtitle = '',
        int $paletteIndex = 0,
        string $format = 'jpg',
        bool $force = false
    ): string {
        $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
        $fullPath = storage_path('app/public/' . $relativePath);

        if (! is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0775, true);
        }

        if (! $force && is_file($fullPath)) {
            return basename($fullPath);
        }

        if (! extension_loaded('gd')) {
            file_put_contents($fullPath, '');

            return basename($fullPath);
        }

        $im = imagecreatetruecolor($width, $height);
        imagealphablending($im, true);

        [$start, $end] = self::PALETTES[$paletteIndex % count(self::PALETTES)];
        [$r1, $g1, $b1] = self::hexToRgb($start);
        [$r2, $g2, $b2] = self::hexToRgb($end);

        // گرادیان عمودی
        for ($y = 0; $y < $height; $y++) {
            $t = $height > 1 ? $y / ($height - 1) : 0;
            $lineColor = imagecolorallocate(
                $im,
                (int) round($r1 + ($r2 - $r1) * $t),
                (int) round($g1 + ($g2 - $g1) * $t),
                (int) round($b1 + ($b2 - $b1) * $t)
            );
            imagefilledrectangle($im, 0, $y, $width, $y, $lineColor);
        }

        // حباب‌های تزئینی نیمه‌شفاف
        $glow = imagecolorallocatealpha($im, 255, 255, 255, 100);
        imagefilledellipse($im, (int) ($width * 0.85), (int) ($height * 0.15), (int) ($width * 0.6), (int) ($width * 0.6), $glow);
        imagefilledellipse($im, (int) ($width * 0.08), (int) ($height * 0.95), (int) ($width * 0.45), (int) ($width * 0.45), $glow);

        // نوار تزئینی تیره پایین تصویر
        $shade = imagecolorallocatealpha($im, 0, 0, 0, 110);
        imagefilledpolygon($im, [
            0, (int) ($height * 0.74),
            $width, (int) ($height * 0.56),
            $width, $height,
            0, $height,
        ], $shade);

        $font = self::fontPath();
        $white = imagecolorallocate($im, 255, 255, 255);

        if ($title !== '') {
            self::drawFittedText($im, $font, $title, (int) ($width * 0.86), (int) ($width * 0.5), (int) ($height * 0.44), (int) ($height * 0.12), $white);
        }

        if ($subtitle !== '') {
            self::drawFittedText($im, $font, $subtitle, (int) ($width * 0.7), (int) ($width * 0.5), (int) ($height * 0.58), (int) ($height * 0.05), $white);
        }

        // واترمارک
        $watermark = imagecolorallocatealpha($im, 255, 255, 255, 42);
        self::drawFittedText($im, $font, 'MEGASHOP', (int) ($width * 0.25), (int) ($width * 0.5), (int) ($height * 0.93), max(9, (int) ($height * 0.03)), $watermark);

        if ($format === 'png') {
            imagepng($im, $fullPath, 7);
        } else {
            imagejpeg($im, $fullPath, 88);
        }

        imagedestroy($im);

        return basename($fullPath);
    }

    /**
     * یافتن یک فونت TTF مناسب روی سیستم.
     */
    public static function fontPath(): ?string
    {
        $candidates = [
            'C:\\Windows\\Fonts\\arialbd.ttf',
            'C:\\Windows\\Fonts\\segoeuib.ttf',
            'C:\\Windows\\Fonts\\tahoma.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private static function drawFittedText($im, ?string $font, string $text, int $maxWidth, int $centerX, int $centerY, int $maxSize, int $color): void
    {
        if ($font === null) {
            $fontSize = 5;
            $textWidth = imagefontwidth($fontSize) * strlen($text);
            imagestring($im, $fontSize, max(0, $centerX - intdiv($textWidth, 2)), max(0, $centerY - intdiv(imagefontheight($fontSize), 2)), $text, $color);

            return;
        }

        $size = $maxSize;
        while ($size > 11 && ($size - 2) >= 11) {
            $bbox = imagettfbbox($size, 0, $font, $text);
            if (($bbox[2] - $bbox[0]) <= $maxWidth) {
                break;
            }
            $size -= 2;
        }

        $bbox = imagettfbbox($size, 0, $font, $text);
        $textWidth = $bbox[2] - $bbox[0];
        $textHeight = $bbox[1] - $bbox[7];

        imagettftext($im, $size, 0, (int) ($centerX - $textWidth / 2 - $bbox[0]), (int) ($centerY + $textHeight / 2), $color, $font, $text);
    }

    private static function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        return [
            (int) hexdec(substr($hex, 0, 2)),
            (int) hexdec(substr($hex, 2, 2)),
            (int) hexdec(substr($hex, 4, 2)),
        ];
    }
}
