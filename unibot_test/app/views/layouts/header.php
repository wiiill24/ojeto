<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Unibot'; ?></title>
    <?php if (isset($css)): ?>
        <?php foreach ($css as $stylesheet): ?>
            <link rel="stylesheet" href="<?php echo $stylesheet; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>

