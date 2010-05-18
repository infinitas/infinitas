<?php
    header('Content-type: application/octet-stream');
    header('Content-Disposition: attachment; filename="my-data.csv"');

    echo $content_for_layout;