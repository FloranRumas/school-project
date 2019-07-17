<?php
    /**
     * @$httpMethods type de requete GET ou POST
     * @$route l'url sur laquelle on va écouter une requête cliente
     * @$callback la fonction qui va renvoyer les paramètres si ils existent
     * @exit pour quitter la fonction une fois le traitement de l'URL terminée
     */
    function route($httpMethods, $route, $callback, $exit = true)
    {
        static $path = null;
        //Permet de récupérer l'URL principale
        if ($path === null) {
            $path = parse_url($_SERVER['REQUEST_URI'])['path'];
            $scriptName = dirname(dirname($_SERVER['SCRIPT_NAME']));
            $scriptName = str_replace('\\', '/', $scriptName);
            $len = strlen($scriptName);
            if ($len > 0 && $scriptName !== '/') {
                $path = substr($path, $len);
            }
        }
        if (!in_array($_SERVER['REQUEST_METHOD'], (array) $httpMethods)) {
            return;
        }
        $matches = null;
        //Pour chaque paramètre on utilise une regex et si il existe on les renvoies à la fonction de callback
        $regex = '/' . str_replace('/', '\/', $route) . '/';
        if (!preg_match_all($regex, $path, $matches)) {
            return;
        }
        if (empty($matches)) {
            $callback();
        } else {
            $params = array();
            foreach ($matches as $k => $v) {
                if (!is_numeric($k) && !isset($v[1])) {
                    $params[$k] = $v[0];
                }
            }
            $callback($params);
        }
        if ($exit) {
            exit;
        }
    }
?>