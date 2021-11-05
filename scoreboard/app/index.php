<?php

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\TranslationServiceProvider;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application();
$app['debug'] = true;

// Register 'dummy' translation service for twig.
$app->register(new TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

// Register database service.
$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/../db/app.db',
    ),
));

// Register form creation service.
$app->register(new FormServiceProvider());

// Register the twig template engine.
$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/theme',
));

// Form to input codes.
$app->match('/scoreboard', function (Request $request) use ($app) {

    // Get the score list from the database.
    $sql = "SELECT user, score FROM scores ORDER BY score DESC LIMIT 20";
    $scores = $app['db']->fetchAll($sql);

    $data = array(
        'user' => '',
        'code' => '',
    );

    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('user')
        ->add('code')
        ->add('Enter', 'submit')
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        // Check to see if the code is valid.
        $code_prefix = explode('-', $data['code'])[0];
        $code_suffix = explode('-', $data['code'])[1];
        $sql = "SELECT code, score, reusable FROM codes WHERE code = ?";
        $code = $app['db']->fetchAssoc($sql, array($code_prefix));
        $rand = $app['db']->fetchColumn("SELECT random FROM codes_random WHERE random = ?", array($code_suffix), 0);
        if (!empty($code) && isset($rand) && (strlen($rand) > "4")) {
            $user_info = $app['db']->fetchAssoc("SELECT score, gates FROM scores WHERE user = ?", array( $data['user']));
            if (!empty($user_info)) {
                // Check to see if they've already used a code of this type.
                $user_gates = explode(',', $user_info['gates']);
                if (!in_array($code_prefix, $user_gates)) {
                    $sql = "UPDATE scores SET score = ?, gates = ? WHERE user = ?";
                    $app['db']->executeUpdate($sql, array($user_info['score'] + $code['score'], $user_info['gates'] . ',' . $code_prefix, $data['user']));
                    if ($code['reusable'] == "0") {
                        $app['db']->delete('codes_random', array('random' => $code_suffix));
                    }
                }
            } else {
                $sql = "INSERT INTO scores (user, score, gates) VALUES (?,?,?)";
                $app['db']->executeUpdate($sql, array($data['user'], $code['score'], $code_prefix));
                if ($code['reusable'] == "0") {
                    $app['db']->delete('codes_random', array('random' => $code_suffix));
                }
            }

        }

        return $app->redirect('/scoreboard');
    }

    return $app['twig']->render('board.twig', array('form' => $form->createView(), 'scores' => $scores));
});

// About page.
$app->get('/', function(Application $app) {
    return $app['twig']->render('about.twig');
});

// Rules page.
$app->get('/rules', function(Application $app) {
    return $app['twig']->render('rules.twig');
});

// Prizes page.
$app->get('/prizes', function(Application $app) {
    return $app['twig']->render('prizes.twig');
});

$app->run();
