<?php 
namespace FilippoFinke\Controllers;

class Assets {

    private const JS_FOLDER = __DIR__ . '/../Assets/js/';
    private const CSS_FOLDER = __DIR__ . '/../Assets/css/';
    private const FONTS_FOLDER = __DIR__ . '/../Assets/fonts/';

    public static function js($request, $response) {
        $asset = basename($request->getAttribute('asset'));
        if(file_exists(self::JS_FOLDER.$asset)) {
            return $response
            ->withHeader("Content-Type","text/javascript")
            ->withBody(file_get_contents(self::JS_FOLDER.$asset))
            ->withStatus(200);
        } else {
            return $response->withStatus(404)->withText("Not found");
        }
    }

    public static function css($request, $response) {
        $asset = basename($request->getAttribute('asset'));
        if(file_exists(self::CSS_FOLDER.$asset)) {
            return $response
            ->withHeader("Content-Type","text/css")
            ->withBody(file_get_contents(self::CSS_FOLDER.$asset))
            ->withStatus(200);
        } else {
            return $response->withStatus(404)->withText("Not found");
        }
    }

    public static function fonts($request, $response) {
        $asset = $request->getAttribute('asset');
        if(file_exists(self::FONTS_FOLDER.$asset)) {
            $mime = mime_content_type(self::FONTS_FOLDER.$asset);
            return $response
            ->withHeader("Content-Type",$mime)
            ->withBody(file_get_contents(self::FONTS_FOLDER.$asset))
            ->withStatus(200);
        } else {
            return $response->withStatus(404)->withText("Not found");
        }
    }

}