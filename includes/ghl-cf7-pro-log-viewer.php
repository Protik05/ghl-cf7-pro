<?php
    $ghl_log = new GHLCF7PRO_Log();
    
    echo '<div class="wrap">';
    echo '<h3>Plugin Log Viewer</h3>';
    echo '<div class="ghlcf7pro_logs">';
    $ghl_log->display_log();
    echo '</div>';
    echo '</div>';