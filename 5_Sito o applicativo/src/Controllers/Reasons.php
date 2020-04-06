<?php

namespace FilippoFinke\Controllers;

use FilippoFinke\Libs\Validators;

/**
 * Reasons.php
 * Controller che si occupa di gestire tutti i percorsi relativi ai motivi.
 *
 * @author Filippo Finke
 */
class Reasons
{
    /**
     * Metodo utilizzato per inserire una motivazione.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function insert($request, $response)
    {
        $name = $request->getParam('name');
        $description = $request->getParam('description');
        if (Validators::isValidDescription($name)
        && Validators::isValidDescription($description)) {
            if (\FilippoFinke\Models\Reasons::insert($name, $description)) {
                return $response->withStatus(201);
            } else {
                return $response->withStatus(500);
            }
        }
        return $response->withStatus(400);
    }

    /**
     * Metodo utilizzato per aggiornare una motivazione.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function update($request, $response)
    {
        $id = $request->getAttribute('id');
        $name = $request->getParam('name');
        $description = $request->getParam('description');
        if (Validators::isValidDescription($name)
        && Validators::isValidDescription($description)) {
            if (\FilippoFinke\Models\Reasons::update($id, $name, $description)) {
                return $response->withStatus(200);
            }
        }
        return $response->withStatus(400);
    }

    /**
     * Metodo utilizzato per eliminare una motivazione.
     *
     * @param $request La richiesta.
     * @param $response La risposta.
     * @return Response La risposta.
     */
    public static function delete($request, $response)
    {
        $id = $request->getAttribute('id');
        if (\FilippoFinke\Models\Reasons::delete($id)) {
            return $response->withStatus(200);
        } else {
            return $response->withStatus(400);
        }
    }
}
