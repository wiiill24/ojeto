    <?php if (isset($js)): ?>
        <?php foreach ($js as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>

