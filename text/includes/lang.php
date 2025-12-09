<?php
// includes/lang.php
session_start(); // Start session once

// Get list of available JSON language files
function getAvailableLanguages($path = __DIR__ . '/../lang') {
    $languageFiles = glob("{$path}/*.json");
    $availableLanguages = [];

    foreach ($languageFiles as $file) {
        $availableLanguages[] = basename($file, ".json");
    }

    return $availableLanguages;
}

// Detect best browser language
function getPreferredLanguage($availableLanguages, $default = 'en') {
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        foreach ($langs as $lang) {
            $code = strtolower(substr($lang, 0, 2));
            if (in_array($code, $availableLanguages)) {
                return $code;
            }
        }
    }
    return $default;
}

// Determine current language (GET param → session → browser → default)
function getCurrentLanguage($availableLanguages, $default = 'en') {

    // User chooses language manually
    if (isset($_GET['lang']) && in_array($_GET['lang'], $availableLanguages)) {
        $_SESSION['lang'] = $_GET['lang'];
        return $_GET['lang'];
    }

    // If session already has a valid language
    if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $availableLanguages)) {
        return $_SESSION['lang'];
    }

    // Detect from browser or fallback
    $_SESSION['lang'] = getPreferredLanguage($availableLanguages, $default);
    return $_SESSION['lang'];
}

// Load JSON language file
function loadLanguage($lang) {
    $path = __DIR__ . "/../lang/{$lang}.json";
    if (file_exists($path)) {
        $json = file_get_contents($path);
        return json_decode($json, true) ?? [];
    }
    return [];
}

// Optional: output language switcher HTML
function renderLanguageSwitchersimple($availableLanguages, $currentLang) {
    echo '<ul>';
    foreach ($availableLanguages as $code) {
        $active = $code === $currentLang ? ' style="font-weight:bold;"' : '';
        echo "<li{$active}><a href=\"?lang={$code}\">" . strtoupper($code) . "</a></li>";
    }
    echo '</ul>';
}

// Optional: output language switcher HTML with dropdown
function renderLanguageSwitcher($availableLanguages, $currentLang) {
    ?>
    <style>
        .lang-dropdown {
            position: relative;
            display: inline-block;
            font-family: sans-serif;
        }
        .lang-dropdown button {
            padding: 6px 12px;
            background: #f1f1f1;
            border: 1px solid #ccc;
            cursor: pointer;
            border-radius: 4px;
        }
        .lang-dropdown button:hover {
            background: #e4e4e4;
        }
        .lang-dropdown ul {
            display: none;
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            margin: 0;
            padding: 0;
            list-style: none;
            min-width: 100%;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 999;
        }
        .lang-dropdown ul li a {
            display: block;
            padding: 8px 12px;
            text-decoration: none;
            color: #333;
        }
        .lang-dropdown ul li a:hover {
            background: #f0f0f0;
        }
        .lang-dropdown:hover ul {
            display: block;
        }
    </style>

    <div class="lang-dropdown">
        <button>
            <?= strtoupper($currentLang); ?> ▼
        </button>
        <ul>
            <?php foreach ($availableLanguages as $code): ?>
                <li>
                    <a href="?lang=<?= $code; ?>"<?= $code === $currentLang ? ' style="font-weight:bold;"' : ''; ?>>
                        <?= strtoupper($code); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}


