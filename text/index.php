<?php
require_once __DIR__ . '/includes/lang.php'; // adjust path relative to index.php

$availableLanguages = getAvailableLanguages();
$lang = getCurrentLanguage($availableLanguages);
$texts = loadLanguage($lang);
?>
<!-- Language switcher -->
<?php renderLanguageSwitcher($availableLanguages, $lang); ?>

<h1><?php echo $texts['homepage']['hello']; ?></h1>

<a href="about.php"><?php echo $texts['homepage']['link_about']; ?></a>
