<?php
namespace FilippoFinke\Controllers;

class Assets
{
    private const JS_FOLDER = __DIR__ . '/../Assets/js/';
    private const CSS_FOLDER = __DIR__ . '/../Assets/css/';
    private const FONTS_FOLDER = __DIR__ . '/../Assets/fonts/';
    private const IMAGES_FOLDER = __DIR__ . '/../Assets/img/';

    public static function js($request, $response)
    {
        $asset = $request->getAttribute('asset');
        return self::handle(self::JS_FOLDER.$asset, $response, 'text/javascript');
    }

    public static function css($request, $response)
    {
        $asset = $request->getAttribute('asset');
        return self::handle(self::CSS_FOLDER.$asset, $response, 'text/css');
    }

    public static function fonts($request, $response)
    {
        $asset = $request->getAttribute('asset');
        return self::handle(self::FONTS_FOLDER.$asset, $response);
    }

    public static function img($request, $response)
    {
        $asset = $request->getAttribute('asset');
        return self::handle(self::IMAGES_FOLDER.$asset, $response);
    }

    private static function handle($file, $response, $type = null)
    {
        if (file_exists($file)) {
            if (!$type) {
                $type = mime_content_type($file);
            }
            return $response
            ->withHeader("Content-Type", $type)
            ->withBody(file_get_contents($file))
            ->withStatus(200);
        } else {
            return $response->withStatus(404)->withText("Not found");
        }
    }
}
