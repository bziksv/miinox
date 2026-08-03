<?php
/**
 * Общие хелперы сайта (портировано из metplus-vrn).
 */

/**
 * Возвращает путь к WebP-версии картинки (кеш в /upload/webp_cache/).
 * Если конвертация невозможна — исходный SRC.
 */
function getImageWebpSrc($src, $quality = 82)
{
    $src = trim((string)$src);
    if ($src === '') {
        return '';
    }

    if (preg_match('/\.webp$/i', $src)) {
        return $src;
    }

    if (!function_exists('imagewebp')) {
        return $src;
    }

    $docRoot = rtrim((string)($_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
    if ($docRoot === '' || $src[0] !== '/') {
        return $src;
    }

    $absSource = $docRoot . $src;
    if (!is_file($absSource) || !is_readable($absSource)) {
        return $src;
    }

    $sourceMtime = (int)@filemtime($absSource);
    $sourceSize = (int)@filesize($absSource);
    $hash = md5($src . '|' . $sourceMtime . '|' . $sourceSize);
    $webpRel = '/upload/webp_cache/' . substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . $hash . '.webp';
    $absWebp = $docRoot . $webpRel;

    if (is_file($absWebp) && (int)@filemtime($absWebp) >= $sourceMtime) {
        return $webpRel;
    }

    $info = @getimagesize($absSource);
    if (!$info || empty($info[2])) {
        return $src;
    }

    switch ((int)$info[2]) {
        case IMAGETYPE_JPEG:
            $image = @imagecreatefromjpeg($absSource);
            break;
        case IMAGETYPE_PNG:
            $image = @imagecreatefrompng($absSource);
            break;
        case IMAGETYPE_GIF:
            $image = @imagecreatefromgif($absSource);
            break;
        default:
            return $src;
    }

    if (!$image) {
        return $src;
    }

    if (function_exists('imagepalettetotruecolor')) {
        @imagepalettetotruecolor($image);
    }

    if (function_exists('imagealphablending')) {
        imagealphablending($image, true);
        imagesavealpha($image, true);
    }

    $dir = dirname($absWebp);
    if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
        imagedestroy($image);
        return $src;
    }

    $tmpWebp = $absWebp . '.tmp.' . getmypid();
    $ok = @imagewebp($image, $tmpWebp, (int)$quality);
    imagedestroy($image);

    if (!$ok || !is_file($tmpWebp)) {
        @unlink($tmpWebp);
        return $src;
    }

    if (!@rename($tmpWebp, $absWebp)) {
        @unlink($tmpWebp);
        return $src;
    }

    @chmod($absWebp, 0664);

    return $webpRel;
}

/**
 * Разрешает добавить товар в корзину, даже если 1С прислала QUANTITY=0
 * и запрет покупки при нулевом остатке (типично для металлопроката «под заказ»).
 */
function ensureCatalogProductOrderable($productId, $iblockId = 39)
{
	$productId = (int)$productId;
	$iblockId = (int)$iblockId;

	if ($productId <= 0 || !CModule::IncludeModule('catalog') || !CModule::IncludeModule('iblock')) {
		return false;
	}

	$element = CIBlockElement::GetList(
		[],
		['ID' => $productId, 'IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y'],
		false,
		false,
		['ID']
	)->Fetch();

	if (!$element) {
		return false;
	}

	$product = CCatalogProduct::GetByID($productId);
	if (!$product) {
		return false;
	}

	$trace = (string)($product['QUANTITY_TRACE'] ?? 'N');
	$canBuyZero = (string)($product['CAN_BUY_ZERO'] ?? 'N');
	$available = (string)($product['AVAILABLE'] ?? 'N');

	if ($trace === 'N' && $canBuyZero === 'Y' && $available === 'Y') {
		return true;
	}

	return (bool)CCatalogProduct::Update($productId, [
		'QUANTITY_TRACE' => 'N',
		'CAN_BUY_ZERO' => 'Y',
		'AVAILABLE' => 'Y',
	]);
}
