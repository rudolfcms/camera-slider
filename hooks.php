<?php

use Rudolf\Component\Helpers\Pagination\Calc as Pagination;
use Rudolf\Component\Helpers\Pagination\Loop;
use Rudolf\Component\Images\Image;
use Rudolf\Component\Hooks;

include 'Model.php';

Hooks\Filter::add('head_stylesheets', function ($stylesheets) {
    $stylesheets[] = PLUGINS.'/camera-slider/bower_components/camera/css/camera.css';

    return $stylesheets;
});
Hooks\Filter::add('head_after', function ($after) {
    $after[] = '
    <script src="'.PLUGINS.'/camera-slider/bower_components/camera/scripts/jquery.min.js"></script>
    <script src="'.PLUGINS.'/camera-slider/bower_components/camera/scripts/jquery.mobile.customized.min.js"></script>
    <script src="'.PLUGINS.'/camera-slider/bower_components/camera/scripts/jquery.easing.1.3.js"></script>
    <script src="'.PLUGINS.'/camera-slider/bower_components/camera/scripts/camera.min.js"></script>
    <script>
        $(function(){
            $("#camera_wrap_1").camera({
                thumbnails: true
            });
        });
    </script>
    <style>
        .camera_caption a {
            color: #fff;
        }
    </style>';

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
            $item->album() ? $item->thumb() : DIR . $item->thumb(),
            $item->url(),
            $item->title()
        );
    }

    $html[] = '</div>';
    echo implode("\r\n", $html);
});
