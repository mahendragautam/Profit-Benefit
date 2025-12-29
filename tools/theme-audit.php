#!/usr/bin/env php
<?php
// E:\webiste theme and plugin\a-Profit-Benefit\Profit-Benefit\tools\theme-audit.php
declare(strict_types=1);

/**
 * Theme Auditor CLI
 *
 * Usage:
 *   php tools/theme-audit.php /path/to/theme
 *
 * Produces a numbered report with ✅/⚠️/❌, file names and line numbers.
 */

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "Run from CLI.\n");
    exit(1);
}

$themeDir = $argv[1] ?? getcwd();
$themeDir = rtrim($themeDir, DIRECTORY_SEPARATOR);

if (!is_dir($themeDir)) {
    fwrite(STDERR, "Theme directory not found: $themeDir\n");
    exit(1);
}

function walkFiles(string $dir, array $exts = ['php','css','js','html']): array {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $files = [];
    foreach ($it as $f) {
        if (!$f->isFile()) continue;
        $ext = strtolower(pathinfo($f->getFilename(), PATHINFO_EXTENSION));
        if (in_array($ext, $exts, true)) {
            $files[] = $f->getPathname();
        }
    }
    return $files;
}

function readLines(string $file): array {
    $content = @file($file, FILE_IGNORE_NEW_LINES);
    return $content === false ? [] : $content;
}

function findRegexLines(array $lines, string $pattern): array {
    $res = [];
    foreach ($lines as $i => $line) {
        if (preg_match($pattern, $line, $m)) {
            $res[] = ['line' => $i+1, 'text' => trim($line), 'match' => $m];
        }
    }
    return $res;
}

$report = [];
$report[] = "WordPress Theme Audit — " . date('Y-m-d H:i:s');
$report[] = "Theme path: $themeDir";
$report[] = "";

/*** 1. Theme Structure Check ***/
$report[] = "1) Theme Structure Check";
$required = ['style.css','functions.php','index.php','screenshot.png'];
$found = [];
foreach ($required as $r) {
    $path = $themeDir . DIRECTORY_SEPARATOR . $r;
    if (file_exists($path)) {
        $found[] = "✅ $r: exists";
    } else {
        $found[] = "❌ $r: MISSING";
    }
}
$report = array_merge($report, $found);

// list folders and check templates/parts/assets
$expectedDirs = ['templates','parts','assets','src','inc','template-parts','patterns'];
$dirChecks = [];
foreach ($expectedDirs as $d) {
    $p = $themeDir . DIRECTORY_SEPARATOR . $d;
    $dirChecks[] = is_dir($p) ? "✅ $d/ : present" : "⚠️ $d/ : not present (optional)";
}
$report = array_merge($report, $dirChecks);
$report[] = "";

/*** 2. style.css Header Check ***/
$report[] = "2) style.css Header Check";
$stylePath = $themeDir . DIRECTORY_SEPARATOR . 'style.css';
if (!file_exists($stylePath)) {
    $report[] = "❌ style.css not found; cannot verify header.";
} else {
    $lines = readLines($stylePath);
    $headerText = implode("\n", array_slice($lines, 0, 60));
    $fields = ['Theme Name'=>'Theme Name','Theme URI'=>'Theme URI','Author'=>'Author','Author URI'=>'Author URI','Description'=>'Description','Version'=>'Version','Requires at least'=>'Requires at least','Requires PHP'=>'Requires PHP','Text Domain'=>'Text Domain'];
    foreach ($fields as $key => $label) {
        if (preg_match('/' . preg_quote($key, '/') . '\s*:\s*(.+)/i', $headerText, $m)) {
            $report[] = "✅ $key: " . trim($m[1]);
        } else {
            $report[] = "⚠️ $key: not set in style.css header";
        }
    }
    // basic standard check
    if (preg_match('/Theme Name\s*:\s*/i', $headerText)) {
        $report[] = "✅ style.css header format appears correct (WordPress standard)";
    } else {
        $report[] = "❌ style.css header missing 'Theme Name' line or malformed header block";
    }
}
$report[] = "";

/*** 3. functions.php Analysis ***/
$report[] = "3) functions.php Analysis";
$fnPath = $themeDir . DIRECTORY_SEPARATOR . 'functions.php';
if (!file_exists($fnPath)) {
    $report[] = "❌ functions.php missing";
} else {
    $lines = readLines($fnPath);
    $enqueues = findRegexLines($lines, '/wp_enqueue_(script|style)\s*\(/i');
    if (empty($enqueues)) {
        $report[] = "⚠️ No wp_enqueue_script/wp_enqueue_style calls found (searching line-by-line may miss dynamic registrations).";
    } else {
        $report[] = "✅ Found enqueue calls:";
        foreach ($enqueues as $e) {
            $report[] = "  - [functions.php:" . $e['line'] . "] " . $e['text'];
        }
        // Try to extract literal src strings from nearby lines (simple heuristic)
        $report[] = "  Attempting to locate literal asset paths used in enqueues (best-effort):";
        foreach ($enqueues as $e) {
            $snippet = '';
            $lineIndex = $e['line'] - 1;
            $window = array_slice($lines, max(0, $lineIndex-3), 7, true);
            $joined = implode("\n", $window);
            if (preg_match_all('/[\'\"]((?:[^\'\"]*\/)?[^\'\"]+\.(?:css|js))(?:\?[^\']*)?[\'\"]/i', $joined, $m)) {
                $uniq = array_unique($m[1]);
                foreach ($uniq as $asset) {
                    $assetPath = $asset;
                    // if starts with get_template_directory_uri or get_stylesheet_directory_uri we skip; we only have literal paths
                    $report[] = "    - literal asset: " . $assetPath;
                    $candidate = $themeDir . DIRECTORY_SEPARATOR . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, preg_replace('/^\//', '', $assetPath)), DIRECTORY_SEPARATOR);
                    if (file_exists($candidate)) {
                        $report[] = "      ✅ exists at " . $candidate;
                    } else {
                        // also check assets/build or assets directories
                        $alt = $themeDir . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . basename($assetPath);
                        if (file_exists($alt)) {
                            $report[] = "      ✅ asset exists at " . $alt;
                        } else {
                            $report[] = "      ❌ asset not found (checked theme root and assets/build)";
                        }
                    }
                }
            } else {
                $report[] = "    - no literal path found in nearby lines for enqueue at line " . $e['line'];
            }
        }
    }

    // Basic PHP parse check: try to parse for syntax errors
    $tmp = sys_get_temp_dir() . '/theme_fn_check_' . rand(1000,9999) . '.php';
    // Write file contents directly; do not prepend '<?php' to avoid duplicate open tags
    file_put_contents($tmp, implode("\n", $lines));
    $out = null;
    $rc = null;
    exec("php -l " . escapeshellarg($tmp) . " 2>&1", $out, $rc);
    unlink($tmp);
    if ($rc === 0) {
        $report[] = "✅ functions.php: PHP syntax OK";
    } else {
        $report[] = "❌ functions.php: PHP syntax error detected:";
        $report = array_merge($report, array_map(fn($l)=>"    $l", $out));
    }
}
$report[] = "";

/*** 4. Template Files Review ***/
$report[] = "4) Template Files Review";
$templates = [];
$allFiles = walkFiles($themeDir, ['php','html']);
foreach ($allFiles as $f) {
    $base = str_replace($themeDir . DIRECTORY_SEPARATOR, '', $f);
    // ignore vendor/composer files
    if (stripos($base, 'vendor' . DIRECTORY_SEPARATOR) === 0) continue;
    $templates[] = $base;
}
sort($templates);
$report[] = "✅ Template files found (" . count($templates) . "):";
foreach ($templates as $t) {
    $report[] = "  - " . $t;
}
// Check hierarchy essentials and presence of wp_head/wp_footer
$needsHeadFooter = [];
foreach ($templates as $t) {
    $ext = pathinfo($t, PATHINFO_EXTENSION);
    if ($ext === 'php' || $ext === 'html') {
        $path = $themeDir . DIRECTORY_SEPARATOR . $t;
        $lines = readLines($path);
        $hasHead = false; $hasFooter = false;
        foreach ($lines as $i => $l) {
            if (!$hasHead && stripos($l, 'wp_head') !== false) $hasHead = true;
            if (!$hasFooter && stripos($l, 'wp_footer') !== false) $hasFooter = true;
        }
        if (!$hasHead || !$hasFooter) {
            $needsHeadFooter[] = [$t, $hasHead, $hasFooter];
        }
    }
}
if (empty($needsHeadFooter)) {
    $report[] = "✅ All template files contain wp_head() and wp_footer() (or are HTML templates for FSE).";
} else {
    $report[] = "⚠️ Some templates missing wp_head()/wp_footer():";
    foreach ($needsHeadFooter as [$t, $h, $f]) {
        $report[] = "  - $t: wp_head " . ($h ? "✅" : "❌") . ", wp_footer " . ($f ? "✅" : "❌");
    }
}
$report[] = "";

/*** 5. CSS/JS Assets Check ***/
$report[] = "5) CSS/JS Assets Check";
$assetExts = ['css','js','map'];
$assetFiles = walkFiles($themeDir, $assetExts);
$assetIndex = array_map(fn($p)=>str_replace($themeDir . DIRECTORY_SEPARATOR, '', $p), $assetFiles);
$report[] = "✅ Found " . count($assetIndex) . " asset files (css/js):";
foreach (array_slice($assetIndex,0,50) as $a) {
    $report[] = "  - " . $a;
}
$report[] = "  (listing truncated if >50)";

// Verify links in templates to assets
$brokenLinks = [];
foreach ($templates as $t) {
    $path = $themeDir . DIRECTORY_SEPARATOR . $t;
    $lines = readLines($path);
    foreach ($lines as $i => $l) {
        if (preg_match_all('/(?:href|src)\s*=\s*[\'\"]([^\'\"]+\.(?:css|js))(?:\?[^\'\"]*)?[\'\"]/i', $l, $m)) {
            foreach ($m[1] as $rel) {
                // ignore absolute URLs (http/https) or protocol relative //
                if (preg_match('#^https?://#i', $rel) || strpos($rel, '//') === 0) continue;
                // normalize
                $cand1 = $themeDir . DIRECTORY_SEPARATOR . ltrim($rel, '/');
                $cand2 = $themeDir . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . ltrim($rel, '/');
                if (!file_exists($cand1) && !file_exists($cand2)) {
                    $brokenLinks[] = [
                        'template' => $t,
                        'line' => $i+1,
                        'link' => $rel
                    ];
                }
            }
        }
    }
}
if (empty($brokenLinks)) {
    $report[] = "✅ No broken local CSS/JS links found in templates (best-effort).";
} else {
    $report[] = "❌ Broken local CSS/JS links found:";
    foreach ($brokenLinks as $b) {
        $report[] = "  - [{$b['template']}:{$b['line']}] {$b['link']}";
    }
}
$report[] = "";

/*** 6. Mobile Polish Integration ***/
$report[] = "6) Mobile Polish Integration";
$mobileCssCandidates = [];
// search enqueues for files with 'mobile' in name
foreach ($allFiles as $f) {
    if (stripos($f, 'functions.php') !== false) {
        $lines = readLines($f);
        foreach ($lines as $i => $l) {
            if (stripos($l, 'mobile') !== false && preg_match('/[\'\"]([^\'\"]+mobile[^\'\"]+\.(?:css|js))[\'\"]/i', $l, $m)) {
                $mobileCssCandidates[] = ['file' => $f, 'line' => $i+1, 'match' => $m[1]];
            }
        }
    }
}
// also check assets folders
foreach ($assetIndex as $a) {
    if (stripos($a, 'mobile') !== false) {
        $mobileCssCandidates[] = ['file' => $a, 'line' => 0, 'match' => $a];
    }
}
if (empty($mobileCssCandidates)) {
    $report[] = "⚠️ No mobile-specific CSS/JS file detected by name (e.g., '*mobile*.css').";
} else {
    $report[] = "✅ Mobile-specific assets detected:";
    foreach ($mobileCssCandidates as $m) {
        $report[] = "  - " . (isset($m['line']) && $m['line'] ? $m['file'] . ':' . $m['line'] : $m['file']) . " -> " . $m['match'];
    }
}

// Check templates for required classes .card .cards-grid
$classesNeeded = ['card','cards-grid'];
$classUsage = [];
foreach ($templates as $t) {
    $path = $themeDir . DIRECTORY_SEPARATOR . $t;
    $lines = readLines($path);
    foreach ($lines as $i => $l) {
        foreach ($classesNeeded as $c) {
            // search for class="...card..." or className in JS/React templates
            if (stripos($l, $c) !== false && preg_match('/class(?:Name)?\s*=\s*[\'\"][^\'\"]*\b' . preg_quote($c, '/') . '\b[^\'\"]*[\'\"]/i', $l)) {
                $classUsage[$c][] = [$t, $i+1, trim($l)];
            }
        }
    }
}
foreach ($classesNeeded as $c) {
    if (!isset($classUsage[$c]) || empty($classUsage[$c])) {
        $report[] = "❌ Class .$c: NOT found in templates (add to templates that render lists/cards).";
    } else {
        $report[] = "✅ Class .$c: found in " . count($classUsage[$c]) . " places (showing up to 10):";
        $cnt = 0;
        foreach ($classUsage[$c] as $u) {
            $report[] = "  - [{$u[0]}:{$u[1]}] {$u[2]}";
            if (++$cnt >= 10) break;
        }
    }
}
// Suggest templates that likely need class additions
$report[] = "Suggested templates to check for card markup: archive.php, index.php, home.php, template-parts/content-*.php, parts/header/footer.";
$report[] = "";

/*** 7. WordPress Integration ***/
$report[] = "7) WordPress Integration";
$report[] = "Will WordPress recognize this as a theme?";

if (file_exists($stylePath) && preg_match('/Theme Name\s*:\s*(.+)/i', implode("\n", array_slice(readLines($stylePath),0,60)), $m)) {
    $report[] = "✅ style.css contains Theme Name — WordPress will list the theme in Appearance > Themes.";
} else {
    $report[] = "❌ Theme missing valid style.css header — WordPress will not recognize the theme.";
}

// Check for essential template tags in main templates
$essentialTags = ['have_posts','the_title','the_content','get_header','get_footer','wp_head','wp_footer'];
$tagMissing = [];
foreach (['index.php','single.php','page.php'] as $t) {
    $p = $themeDir . DIRECTORY_SEPARATOR . $t;
    if (!file_exists($p)) {
        $tagMissing[] = "⚠️ $t: file missing (may be okay if using FSE templates)";
        continue;
    }
    $lines = readLines($p);
    $joined = implode("\n", array_slice($lines, 0, 400));
    foreach ($essentialTags as $tag) {
        if (stripos($joined, $tag) === false) {
            $tagMissing[] = "⚠️ $t missing reference to {$tag} (search only first 400 lines)";
        }
    }
}
if (empty($tagMissing)) {
    $report[] = "✅ Essential template tags appear present in main templates (best-effort scan).";
} else {
    $report = array_merge($report, $tagMissing);
}

// Common errors: search for short_open_tag usage and direct DB queries
$commonIssues = [];
$allPhpFiles = walkFiles($themeDir, ['php']);
foreach ($allPhpFiles as $f) {
    $lines = readLines($f);
    foreach ($lines as $i => $l) {
        if (strpos($l, '<? ') !== false && strpos($l, '<?php') === false) {
            $commonIssues[] = "⚠️ Short PHP open tag in [$f:" . ($i+1) . "] — avoid '<? ' use '<?php'.";
        }
        if (preg_match('/\$wpdb->query\s*\(/i', $l)) {
            $commonIssues[] = "⚠️ Direct \$wpdb->query usage in [$f:" . ($i+1) . "] — ensure proper prepare() and capability checks.";
        }
    }
}
if (empty($commonIssues)) {
    $report[] = "✅ No quick common-issue patterns detected (short tags, direct \$wpdb->query) in PHP files (best-effort).";
} else {
    $report = array_merge($report, $commonIssues);
}

$report[] = "";
$report[] = "End of audit.";

echo implode(PHP_EOL, $report) . PHP_EOL;
