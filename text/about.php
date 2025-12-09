<?php
require_once __DIR__ . '/includes/lang.php';

$availableLanguages = getAvailableLanguages();
$lang = getCurrentLanguage($availableLanguages);
$texts = loadLanguage($lang);
?>

<h1><?php echo $texts['about']['title']; ?></h1>

<?php renderLanguageSwitchersimple($availableLanguages, $lang); ?>

<a href="index.php"><?php echo $texts['about']['link_back']; ?></a>