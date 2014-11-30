<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<style>
    .divIconSel{
        height: 80px;
        border: 1px solid #fff;
        box-sizing: border-box;
        cursor: pointer;
    }

    .iconSel{
        line-height: 1.4;
        font-size: 1.5em;
    }

    .iconSelected{
        background-color: #563d7c;
        color: white;
    }

    .iconDesc{
        font-size: 0.8em;
    }
</style>
<?php
foreach (ls('core/css/icon', '*') as $dir) {
    if (is_dir('core/css/icon/' . $dir) && file_exists('core/css/icon/' . $dir . '/style.css')) {
        $css = file_get_contents('core/css/icon/' . $dir . '/style.css');
        $research = strtolower(str_replace('/', '', $dir));
        preg_match_all("/\." . $research . "-(.*?):/", $css, $matches, PREG_SET_ORDER);
        $height = (ceil(count($matches) / 14) * 40) + 80;
        echo '<div style="height : ' . $height . 'px;"><legend>{{' . str_replace('/', '', $dir) . '}}</legend>';

        $number = 1;
        foreach ($matches as $match) {
            if (isset($match[0])) {
                if ($number == 1) {
                    echo '<div class="row">';
                }
                echo '<div class="col-lg-1 divIconSel">';
                $icon = str_replace(array(':', '.'), '', $match[0]);
                echo '<center><span class="iconSel"><i class=\'icon ' . $icon . '\'></i></span><br/><span class="iconDesc">' . $icon . '</span></center>';
                echo '</div>';
                if ($number == 12) {
                    echo '</div>';
                    $number = 0;
                }
                $number++;
            }
        }
        echo "</div><br/>";
    }
}
?>
<div style="height: 650px;">
    <legend>{{Générale}}</legend>
    <div class="row">
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-glass'></i></span><br/><span class="iconDesc">fa-glass</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-music'></i></span><br/><span class="iconDesc">fa-music</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-search'></i></span><br/><span class="iconDesc">fa-search</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-envelope-o'></i></span><br/><span class="iconDesc">fa-envelope-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-heart'></i></span><br/><span class="iconDesc">fa-heart</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-star'></i></span><br/><span class="iconDesc">fa-star</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-star-o'></i></span><br/><span class="iconDesc">fa-star-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-user'></i></span><br/><span class="iconDesc">fa-user</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-film'></i></span><br/><span class="iconDesc">fa-film</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-th-large'></i></span><br/><span class="iconDesc">fa-th-large</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-check'></i></span><br/><span class="iconDesc">fa-check</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-times'></i></span><br/><span class="iconDesc">fa-times</span></div>
    </div>
    <div class="row">
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-power-off'></i></span><br/><span class="iconDesc">fa-power-off</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-signal'></i></span><br/><span class="iconDesc">fa-signal</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-cog'></i></span><br/><span class="iconDesc">fa-cog</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-trash-o'></i></span><br/><span class="iconDesc">fa-trash-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-home'></i></span><br/><span class="iconDesc">fa-home</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-file-o'></i></span><br/><span class="iconDesc">fa-file-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-clock-o'></i></span><br/><span class="iconDesc">fa-clock-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-road'></i></span><br/><span class="iconDesc">fa-road</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-download'></i></span><br/><span class="iconDesc">fa-download</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-inbox'></i></span><br/><span class="iconDesc">fa-inbox</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-play-circle-o'></i></span><br/><span class="iconDesc">fa-play-circle-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-refresh'></i></span><br/><span class="iconDesc">fa-refresh</span></div>
    </div>
    <div class="row">
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-list-alt'></i></span><br/><span class="iconDesc">fa-list-alt</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-lock'></i></span><br/><span class="iconDesc">fa-lock</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-flag'></i></span><br/><span class="iconDesc">fa-flag</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-headphones'></i></span><br/><span class="iconDesc">fa-headphones</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-volume-down'></i></span><br/><span class="iconDesc">fa-volume-down</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-qrcode'></i></span><br/><span class="iconDesc">fa-qrcode</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-barcode'></i></span><br/><span class="iconDesc">fa-barcode</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-tag'></i></span><br/><span class="iconDesc">fa-tag</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-book'></i></span><br/><span class="iconDesc">fa-book</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-print'></i></span><br/><span class="iconDesc">fa-print</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-camera'></i></span><br/><span class="iconDesc">fa-camera</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-video-camera'></i></span><br/><span class="iconDesc">fa-video-camera</span></div>
    </div>
    <div class="row">
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-picture-o'></i></span><br/><span class="iconDesc">fa-picture-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-pencil'></i></span><br/><span class="iconDesc">fa-pencil</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-map-marker'></i></span><br/><span class="iconDesc">fa-map-marker</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-tint'></i></span><br/><span class="iconDesc">fa-tint</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-pencil-square-o'></i></span><br/><span class="iconDesc">fa-pencil-square-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-check-square-o'></i></span><br/><span class="iconDesc">fa-check-square-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-arrows'></i></span><br/><span class="iconDesc">fa-arrows</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-step-backward'></i></span><br/><span class="iconDesc">fa-step-backward</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-fast-backward'></i></span><br/><span class="iconDesc">fa-fast-backward</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-backward'></i></span><br/><span class="iconDesc">fa-backward</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-play'></i></span><br/><span class="iconDesc">fa-play</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-pause'></i></span><br/><span class="iconDesc">fa-pause</span></div>
    </div>
    <div class="row">
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-stop'></i></span><br/><span class="iconDesc">fa-stop</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-forward'></i></span><br/><span class="iconDesc">fa-forward</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-fast-forward'></i></span><br/><span class="iconDesc">fa-fast-forward</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-step-forward'></i></span><br/><span class="iconDesc">fa-step-forward</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-eject'></i></span><br/><span class="iconDesc">fa-eject</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-chevron-left'></i></span><br/><span class="iconDesc">fa-chevron-left</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-chevron-right'></i></span><br/><span class="iconDesc">fa-chevron-right</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-plus-circle'></i></span><br/><span class="iconDesc">fa-plus-circle</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-minus-circle'></i></span><br/><span class="iconDesc">fa-minus-circle</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-times-circle'></i></span><br/><span class="iconDesc">fa-times-circle</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-check-circle'></i></span><br/><span class="iconDesc">fa-check-circle</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-question-circle'></i></span><br/><span class="iconDesc">fa-question-circle</span></div>
    </div>
    <div class="row">
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-info-circle'></i></span><br/><span class="iconDesc">fa-info-circle</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-crosshairs'></i></span><br/><span class="iconDesc">fa-crosshairs</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-times-circle-o'></i></span><br/><span class="iconDesc">fa-times-circle-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-check-circle-o'></i></span><br/><span class="iconDesc">fa-check-circle-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-ban'></i></span><br/><span class="iconDesc">fa-ban</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-arrow-left'></i></span><br/><span class="iconDesc">fa-arrow-left</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-arrow-right'></i></span><br/><span class="iconDesc">fa-arrow-right</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-arrow-up'></i></span><br/><span class="iconDesc">fa-arrow-up</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-arrow-down'></i></span><br/><span class="iconDesc">fa-arrow-down</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-plus'></i></span><br/><span class="iconDesc">fa-plus</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-minus'></i></span><br/><span class="iconDesc">fa-minus</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-asterisk'></i></span><br/><span class="iconDesc">fa-asterisk</span></div>
    </div>
    <div class="row">
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-exclamation-circle'></i></span><br/><span class="iconDesc">fa-exclamation-circle</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-gift'></i></span><br/><span class="iconDesc">fa-gift</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-leaf'></i></span><br/><span class="iconDesc">fa-leaf</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-fire'></i></span><br/><span class="iconDesc">fa-fire</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-eye'></i></span><br/><span class="iconDesc">fa-eye</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-eye-slash'></i></span><br/><span class="iconDesc">fa-slash</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-exclamation-triangle'></i></span><br/><span class="iconDesc">fa-exclamation-triangle</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-plane'></i></span><br/><span class="iconDesc">fa-plane</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-calendar'></i></span><br/><span class="iconDesc">fa-calendar</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-random'></i></span><br/><span class="iconDesc">fa-random</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-comment'></i></span><br/><span class="iconDesc">fa-comment</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-magnet'></i></span><br/><span class="iconDesc">fa-magnet</span></div>
    </div>
    <div class="row">
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-chevron-up'></i></span><br/><span class="iconDesc">fa-chevron-up</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-chevron-down'></i></span><br/><span class="iconDesc">fa-chevron-down</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-shopping-cart'></i></span><br/><span class="iconDesc">fa-shopping-cart</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-folder'></i></span><br/><span class="iconDesc">fa-folder</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-folder-open'></i></span><br/><span class="iconDesc">fa-folder-open</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-bar-chart-o'></i></span><br/><span class="iconDesc">fa-bar-chart-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-key'></i></span><br/><span class="iconDesc">fa-key</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-heart-o'></i></span><br/><span class="iconDesc">fa-heart-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-sign-out'></i></span><br/><span class="iconDesc">fa-sign-out</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-thumb-tack'></i></span><br/><span class="iconDesc">fa-thumb-tack</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-sign-in'></i></span><br/><span class="iconDesc">fa-sign-in</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-phone'></i></span><br/><span class="iconDesc">fa-phone</span></div>
    </div>
    <div class="row">
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-unlock'></i></span><br/><span class="iconDesc">fa-unlock</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-credit-card'></i></span><br/><span class="iconDesc">fa-credit-card</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-rss'></i></span><br/><span class="iconDesc">fa-rss</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-hdd-o'></i></span><br/><span class="iconDesc">fa-hdd-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-bullhorn'></i></span><br/><span class="iconDesc">fa-bullhorn</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-bell'></i></span><br/><span class="iconDesc">fa-bell</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-globe'></i></span><br/><span class="iconDesc">fa-globe</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-wrench'></i></span><br/><span class="iconDesc">fa-wrench</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-filter'></i></span><br/><span class="iconDesc">fa-filter</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-briefcase'></i></span><br/><span class="iconDesc">fa-briefcase</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-cloud'></i></span><br/><span class="iconDesc">fa-cloud</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-flask'></i></span><br/><span class="iconDesc">fa-flask</span></div>
    </div>
    <div class="row">
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-scissors'></i></span><br/><span class="iconDesc">fa-scissors</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-paperclip'></i></span><br/><span class="iconDesc">fa-paperclip</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-floppy-o'></i></span><br/><span class="iconDesc">fa-floppy-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-table'></i></span><br/><span class="iconDesc">fa-table</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-magic'></i></span><br/><span class="iconDesc">fa-magic</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-truck'></i></span><br/><span class="iconDesc">fa-truck</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-money'></i></span><br/><span class="iconDesc">fa-money</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-columns'></i></span><br/><span class="iconDesc">fa-columns</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-envelope'></i></span><br/><span class="iconDesc">fa-envelope</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-gavel'></i></span><br/><span class="iconDesc">fa-gavel</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-tachometer'></i></span><br/><span class="iconDesc">fa-tachometer</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-bolt'></i></span><br/><span class="iconDesc">fa-bolt</span></div>
    </div>
    <div class="row">
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-sitemap'></i></span><br/><span class="iconDesc">fa-sitemap</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-umbrella'></i></span><br/><span class="iconDesc">fa-umbrella</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-lightbulb-o'></i></span><br/><span class="iconDesc">fa-lightbulb-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-stethoscope'></i></span><br/><span class="iconDesc">fa-stethoscope</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-suitcase'></i></span><br/><span class="iconDesc">fa-suitcase</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-coffee'></i></span><br/><span class="iconDesc">fa-coffee</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-cutlery'></i></span><br/><span class="iconDesc">fa-cutlery</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-building-o'></i></span><br/><span class="iconDesc">fa-building-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-medkit'></i></span><br/><span class="iconDesc">fa-medkit</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-beer'></i></span><br/><span class="iconDesc">fa-beer</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-h-square'></i></span><br/><span class="iconDesc">fa-square</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-desktop'></i></span><br/><span class="iconDesc">fa-desktop</span></div>
    </div>
    <div class="row">
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-laptop'></i></span><br/><span class="iconDesc">fa-laptop</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-tablet'></i></span><br/><span class="iconDesc">fa-tablet</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-mobile'></i></span><br/><span class="iconDesc">fa-mobile</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-circle-o'></i></span><br/><span class="iconDesc">fa-circle-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-spinner'></i></span><br/><span class="iconDesc">fa-spinner</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-circle'></i></span><br/><span class="iconDesc">fa-circle</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-smile-o'></i></span><br/><span class="iconDesc">fa-smile-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-frown-o'></i></span><br/><span class="iconDesc">fa-frown-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-meh-o'></i></span><br/><span class="iconDesc">fa-meh-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-gamepad'></i></span><br/><span class="iconDesc">fa-gamepad</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-keyboard-o'></i></span><br/><span class="iconDesc">fa-keyboard-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-terminal'></i></span><br/><span class="iconDesc">fa-terminal</span></div>
    </div>
    <div class="row">
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-location-arrow'></i></span><br/><span class="iconDesc">fa-location-arrow</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-microphone'></i></span><br/><span class="iconDesc">fa-microphone</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-microphone-slash'></i></span><br/><span class="iconDesc">fa-microphone-slash</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-shield'></i></span><br/><span class="iconDesc">fa-shield</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-calendar-o'></i></span><br/><span class="iconDesc">fa-calendar-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-fire-extinguisher'></i></span><br/><span class="iconDesc">fa-fire-extinguisher</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-rocket'></i></span><br/><span class="iconDesc">fa-rocket</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-anchor'></i></span><br/><span class="iconDesc">fa-anchor</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-unlock-alt'></i></span><br/><span class="iconDesc">fa-unlock-alt</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-compass'></i></span><br/><span class="iconDesc">fa-compass</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-apple'></i></span><br/><span class="iconDesc">fa-apple</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-windows'></i></span><br/><span class="iconDesc">fa-windows</span></div>
    </div>
    <div class="row">
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-android'></i></span><br/><span class="iconDesc">fa-android</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-linux'></i></span><br/><span class="iconDesc">fa-linux</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-dribbble'></i></span><br/><span class="iconDesc">fa-dribbble</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-trello'></i></span><br/><span class="iconDesc">fa-trello</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-female'></i></span><br/><span class="iconDesc">fa-female</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-male'></i></span><br/><span class="iconDesc">fa-male</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-gittip'></i></span><br/><span class="iconDesc">fa-sun-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-sun-o'></i></span><br/><span class="iconDesc">fa-glass</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-moon-o'></i></span><br/><span class="iconDesc">fa-moon-o</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-archive'></i></span><br/><span class="iconDesc">fa-archive</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-pagelines'></i></span><br/><span class="iconDesc">fa-pagelines</span></div>
        <div class="col-lg-1 divIconSel"><center><span class="iconSel"><i class='fa fa-wheelchair'></i></span><br/><span class="iconDesc">fa-wheelchair</span></div>
    </div>
</div>
<script>
    $('.divIconSel').on('click', function () {
        $('.divIconSel').removeClass('iconSelected');
        $(this).closest('.divIconSel').addClass('iconSelected');
    });
    $('.divIconSel').on('dblclick', function () {
        $('.divIconSel').removeClass('iconSelected');
        $(this).closest('.divIconSel').addClass('iconSelected');
        $('#mod_selectIcon').dialog("option", "buttons")['Valider'].apply($('#mod_selectIcon'));
    });
</script>
