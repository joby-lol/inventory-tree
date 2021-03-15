<?php
// associated files
$files = $package->noun()->files();
if ($files) {
    echo "<h2 class='inventory-files-header'>Files</h2>";
    echo "<div class='inventory-files-list'>";
    foreach ($files as $file) {
        echo $file->metaCard();
    }
    echo "</div>";
}

// contained items
$items = $package->noun()->inventory();
if ($items) {
    echo "<h2 class='inventory-items-header'>Contents</h2>";
    echo "<ul class='inventory-items-list'>";
    foreach ($items as $item) {
        echo '<li>' . $item->link() . '</li>';
    }
    echo "</ul>";
}
