<?php
namespace FilippoFinke\Controllers;

/**
 * Assets.php
 * Controller che si occupa di gestire le risorse dell'applicativo.
 *
 * @author Filippo Finke
 */
class Assets
{
    /**
     * Cartella per i file JavaScript.
     */
    private const JS_FOLDER = __DIR__ . '/../Assets/js/';

    /**
     * Cartella per i file CSS.
     */
    private const CSS_FOLDER = __DIR__ . '/../Assets/css/';

    /**
     * Cartella per i fonts.
     */
    private const FONTS_FOLDER = __DIR__ . '/../Assets/fonts/';

    /**
     * Cartella per le immagini.
     */
    private const IMAGES_FOLDER = __DIR__ . '/../Assets/img/';

    /**
     * Metodo che si occupa di ricavare le risorse JavaScript.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function js($request, $response)
    {
        $asset = $request->getAttribute('asset');
        return self::handle(self::JS_FOLDER.$asset, $response, 'text/javascript');
    }

    /**
     * Metodo che si occupa di ricavare le risorse CSS.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function css($request, $response)
    {
        $asset = $request->getAttribute('asset');
        return self::handle(self::CSS_FOLDER.$asset, $response, 'text/css');
    }

    /**
     * Metodo che si occupa di ricavare i fonts.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function fonts($request, $response)
    {
        $asset = $request->getAttribute('asset');
        return self::handle(self::FONTS_FOLDER.$asset, $response);
    }

    /**
     * Metodo che si occupa di ricavare le immagini.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function img($request, $response)
    {
        $asset = $request->getAttribute('asset');
        return self::handle(self::IMAGES_FOLDER.$asset, $response);
    }

    /**
     * Metodo generale per eseguire controlli sui file da ricavare.
     *
     * @param $file Il file da carica.
     * @param $response La risposta da ritornare.
     * @param $type Il tipo del file.
     * @return Response La risposta.
     */
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
