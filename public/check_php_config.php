<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification Configuration PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .config-item {
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #2196F3;
            background: #f9f9f9;
        }
        .config-item.good {
            border-left-color: #4CAF50;
            background: #e8f5e9;
        }
        .config-item.warning {
            border-left-color: #FF9800;
            background: #fff3e0;
        }
        .config-item.error {
            border-left-color: #f44336;
            background: #ffebee;
        }
        .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 200px;
        }
        .value {
            color: #333;
            font-size: 1.1em;
        }
        .path-info {
            margin-top: 30px;
            padding: 15px;
            background: #e3f2fd;
            border-radius: 4px;
        }
        .recommended {
            margin-top: 20px;
            padding: 15px;
            background: #f3e5f5;
            border-radius: 4px;
        }
        .recommended h3 {
            margin-top: 0;
            color: #7b1fa2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Vérification Configuration PHP</h1>
        
        <?php
        $maxFileUploads = ini_get('max_file_uploads');
        $uploadMaxFilesize = ini_get('upload_max_filesize');
        $postMaxSize = ini_get('post_max_size');
        $maxExecutionTime = ini_get('max_execution_time');
        $memoryLimit = ini_get('memory_limit');
        
        // Convertir en bytes pour comparaison
        function convertToBytes($val) {
            $val = trim($val);
            $last = strtolower($val[strlen($val)-1]);
            $val = (int)$val;
            switch($last) {
                case 'g': $val *= 1024;
                case 'm': $val *= 1024;
                case 'k': $val *= 1024;
            }
            return $val;
        }
        
        $uploadMaxBytes = convertToBytes($uploadMaxFilesize);
        $postMaxBytes = convertToBytes($postMaxSize);
        $memoryLimitBytes = convertToBytes($memoryLimit);
        
        // Évaluer chaque configuration
        function evaluateConfig($value, $type, $recommended) {
            switch($type) {
                case 'max_file_uploads':
                    $class = ($value >= $recommended) ? 'good' : (($value >= $recommended/2) ? 'warning' : 'error');
                    return $class;
                case 'size':
                    $valBytes = convertToBytes($value);
                    $recBytes = convertToBytes($recommended);
                    $class = ($valBytes >= $recBytes) ? 'good' : (($valBytes >= $recBytes/2) ? 'warning' : 'error');
                    return $class;
                case 'time':
                    $class = ($value >= $recommended) ? 'good' : (($value >= $recommended/2) ? 'warning' : 'error');
                    return $class;
                default:
                    return 'good';
            }
        }
        
        $maxFileUploadsClass = evaluateConfig($maxFileUploads, 'max_file_uploads', 400);
        $uploadMaxFilesizeClass = evaluateConfig($uploadMaxFilesize, 'size', '10M');
        $postMaxSizeClass = evaluateConfig($postMaxSize, 'size', '800M');
        $maxExecutionTimeClass = evaluateConfig($maxExecutionTime, 'time', 600);
        $memoryLimitClass = evaluateConfig($memoryLimit, 'size', '512M');
        ?>
        
        <div class="config-item <?php echo $maxFileUploadsClass; ?>">
            <span class="label">max_file_uploads:</span>
            <span class="value"><?php echo $maxFileUploads; ?></span>
            <?php if ($maxFileUploads < 400): ?>
                <p style="margin: 5px 0 0 200px; font-size: 0.9em; color: #666;">
                    ⚠️ Recommandé: ≥ 400 (actuellement <?php echo $maxFileUploads; ?>)
                </p>
            <?php else: ?>
                <p style="margin: 5px 0 0 200px; font-size: 0.9em; color: #2e7d32;">
                    ✓ Excellente valeur ! Permet l'import de beaucoup de photos.
                </p>
            <?php endif; ?>
        </div>
        
        <div class="config-item <?php echo $uploadMaxFilesizeClass; ?>">
            <span class="label">upload_max_filesize:</span>
            <span class="value"><?php echo $uploadMaxFilesize; ?></span>
            <?php if (convertToBytes($uploadMaxFilesize) < convertToBytes('10M')): ?>
                <p style="margin: 5px 0 0 200px; font-size: 0.9em; color: #666;">
                    ⚠️ Recommandé: ≥ 10M
                </p>
            <?php else: ?>
                <p style="margin: 5px 0 0 200px; font-size: 0.9em; color: #2e7d32;">
                    ✓ OK
                </p>
            <?php endif; ?>
        </div>
        
        <div class="config-item <?php echo $postMaxSizeClass; ?>">
            <span class="label">post_max_size:</span>
            <span class="value"><?php echo $postMaxSize; ?></span>
            <?php if (convertToBytes($postMaxSize) < convertToBytes('800M')): ?>
                <p style="margin: 5px 0 0 200px; font-size: 0.9em; color: #666;">
                    ⚠️ Recommandé: ≥ 800M pour importer 100-200 photos
                </p>
            <?php else: ?>
                <p style="margin: 5px 0 0 200px; font-size: 0.9em; color: #2e7d32;">
                    ✓ OK
                </p>
            <?php endif; ?>
        </div>
        
        <div class="config-item <?php echo $maxExecutionTimeClass; ?>">
            <span class="label">max_execution_time:</span>
            <span class="value"><?php echo $maxExecutionTime; ?> secondes</span>
            <?php if ($maxExecutionTime < 600): ?>
                <p style="margin: 5px 0 0 200px; font-size: 0.9em; color: #666;">
                    ⚠️ Recommandé: ≥ 600 secondes (10 minutes) pour les gros imports
                </p>
            <?php else: ?>
                <p style="margin: 5px 0 0 200px; font-size: 0.9em; color: #2e7d32;">
                    ✓ OK
                </p>
            <?php endif; ?>
        </div>
        
        <div class="config-item <?php echo $memoryLimitClass; ?>">
            <span class="label">memory_limit:</span>
            <span class="value"><?php echo $memoryLimit; ?></span>
            <?php if (convertToBytes($memoryLimit) < convertToBytes('512M')): ?>
                <p style="margin: 5px 0 0 200px; font-size: 0.9em; color: #666;">
                    ⚠️ Recommandé: ≥ 512M
                </p>
            <?php else: ?>
                <p style="margin: 5px 0 0 200px; font-size: 0.9em; color: #2e7d32;">
                    ✓ OK
                </p>
            <?php endif; ?>
        </div>
        
        <div class="path-info">
            <h3>📁 Fichier php.ini utilisé :</h3>
            <p style="font-family: monospace; background: white; padding: 10px; border-radius: 4px;">
                <?php echo php_ini_loaded_file(); ?>
            </p>
            <?php 
            $iniPath = php_ini_loaded_file();
            if (strpos($iniPath, 'apache') !== false || strpos($iniPath, 'Apache') !== false) {
                echo '<p style="color: #2e7d32; margin-top: 10px;">✓ C\'est le bon fichier php.ini (celui d\'Apache)</p>';
            } else if (strpos($iniPath, 'php') !== false && strpos($iniPath, 'bin') !== false) {
                echo '<p style="color: #f44336; margin-top: 10px;">⚠️ ATTENTION : Ce fichier semble être celui de PHP CLI, pas d\'Apache !</p>';
                echo '<p style="color: #666;">Vous devez modifier le php.ini d\'Apache. Voir VERIFIER_PHP_INI.md</p>';
            }
            ?>
        </div>
        
        <div class="recommended">
            <h3>📋 Résumé</h3>
            <?php
            $allGood = ($maxFileUploads >= 400) && 
                      (convertToBytes($uploadMaxFilesize) >= convertToBytes('10M')) &&
                      (convertToBytes($postMaxSize) >= convertToBytes('800M')) &&
                      ($maxExecutionTime >= 600);
            
            if ($allGood) {
                echo '<p style="color: #2e7d32; font-size: 1.1em; font-weight: bold;">✓ Votre configuration est excellente ! Vous pouvez importer 100-200 photos sans problème.</p>';
            } else {
                echo '<p style="color: #f44336; font-size: 1.1em; font-weight: bold;">⚠️ Certaines valeurs doivent être ajustées. Consultez VERIFIER_PHP_INI.md pour les instructions.</p>';
            }
            ?>
        </div>
        
        <div style="margin-top: 30px; padding: 15px; background: #fff3cd; border-radius: 4px;">
            <p><strong>💡 Conseil :</strong> Après avoir modifié php.ini, n'oubliez pas de <strong>redémarrer Apache</strong> !</p>
            <p>Dans WAMP : Clic droit sur l'icône → Apache → Redémarrer</p>
        </div>
    </div>
</body>
</html>

