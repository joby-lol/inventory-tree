<?php

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

$package->cache_noStore();

$helper = $cms->helper('inventory');
$media = $cms->helper('media');
$renderer = new ImageRenderer(
    new RendererStyle(600,1),
    new ImagickImageBackEnd()
);
$writer = new Writer($renderer);

$COUNT = 10;

$tags = array_map(
    [$helper,'generateTag'],
    range(1,$COUNT)
);

echo "<div class='inventory-label-sheet'>";
foreach ($tags as $tag) {
    $color = $helper->color($tag);
    $img = $media->create('qr.png',$writer->writeString($tag));
    echo "<div class='inventory-label color-".substr(md5($tag),0,1)."'>";
    echo '<div class="inventory-label-qr"><img src="'.$img['url'].'"></div>';
    echo "<div class='inventory-label-text'>$tag</div>";
    echo "<div class='inventory-label-color' style='background-color:$color;'></div>";
    echo "</div>";
}
echo "</div>";
