<?php

namespace FilippoFinke\Controllers;

use FilippoFinke\Libs\Validators;

class Reasons
{
    public static function insert($request, $response)
    {
        $name = $request->getParam('name');
        $description = $request->getParam('description');
        if (Validators::isValidAlphabetAndAccents($name, 255)
        && Validators::isValidDescription($description)) {
            if (\FilippoFinke\Models\Reasons::insert($name, $description)) {
                return $response->withStatus(201);
            } else {
                return $response->withStatus(500);
            }
        }
        return $response->withStatus(400);
    }

    public static function update($request, $response)
    {
        $id = $request->getAttribute('id');
        $name = $request->getParam('name');
        $description = $request->getParam('description');
        if (Validators::isValidName($name)
        && Validators::isValidDescription($description)) {
            if (\FilippoFinke\Models\Reasons::update($id, $name, $description)) {
                return $response->withStatus(200);
            }
        }
        return $response->withStatus(400);
    }

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
