<?php

use Rudolf\Component\Helpers\Pagination\Calc as Pagination;
use Rudolf\Component\Helpers\Pagination\Loop;
use Rudolf\Component\Images\Image;
use Rudolf\Component\Hooks;

include 'Model.php';

define("CAMERA_DIR", PLUGINS.'/camera-slider');

Hooks\Filter::add('head_stylesheets', function ($stylesheets) {
    $stylesheets[] = CAMERA_DIR.'/css/camera.min.css';

    return $stylesheets;
});
Hooks\Filter::add('head_after', function ($after) {
    $after[] = '
    <style>
        .camera_caption a {
            color: #fff;
        }
    </style>';

    return $after;
});
Hooks\Filter::add('foot_scripts', function ($scripts) {
    $scripts = [
        CAMERA_DIR.'/scripts/jquery.mobile.customized.min.js',
        CAMERA_DIR.'/scripts/jquery.easing.1.3.js',
        CAMERA_DIR.'/scripts/camera.js',
    ];
    return $scripts;
});
Hooks\Filter::add('foot_after', function ($after) {
    $after[] = '
    <script>
        $(function(){
            if ($("#camera_wrap_1").length) {
                $("#camera_wrap_1").camera({
                    thumbnails: true
                });
            }
        });
    </script>';

    return $after;
});

Hooks\Action::add('home_page_slider', function () {
    $articles = (new mklj\Rudolf\Plugin\CameraSlider\Model())->getItems(5);

    if (false === $articles) {
        return;
    }

    $this->loop = new Loop($articles, new Pagination(5),
        'Rudolf\\Modules\\Articles\\One\\Article'
    );

    $html[] = '<div class="camera_wrap camera_azure_skin" id="camera_wrap_1">';

    while ($this->loop->haveItems()) {
        $item = $this->loop->item();

        $html[] = sprintf('
            <div data-thumb="%1$s" data-src="%2$s">
                <div class="camera_caption fadeFromBottom">
                    <a href="%3$s">%4$s</a>
                </div>
            </div>',
            Image::resize($item->thumb(), 100, 75),
            Image::resize($item->thumb(), 1000, 500),
            $item->url(),
            $item->title()
        );
    }

    $html[] = '</div>';
    echo implode("\r\n", $html);
});
