<?php

/**
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 *
 * All the  specific functions, needed to implement all the module
 * logic, should go to locallib.php.
 */

require_once(__DIR__ . '/lib/autoload.php');

defined('MOODLE_INTERNAL') || die();

//Global Client Variable
global $client;

//Fetch Camunda URL
$ini = parse_ini_file(__DIR__ . '/.ini');
$camunda_url = $ini['camunda_url'];
$camunda_api = $camunda_url . 'engine-rest/';

//Create Guzzle Client
$client = new GuzzleHttp\Client([
        'base_uri' => $camunda_api,
]);

//======================================================================
// PROCESS FUNCTIONS FOR CAMUNDA
//======================================================================
function get_all_camunda_users() {
    global $client;

    $res = $client->get('user');
    $body = $res->getBody();
    $data = json_decode($body, true);
    return ($data);
}

function start_process($key, $variables) {
    global $client;

    $process_url = 'process-definition/key/' . $key . '/start';
    $res = $client->post($process_url, [
            GuzzleHttp\RequestOptions::JSON =>
                    ['variables' => $variables]
    ]);
    $body = $res->getBody();
    $data = json_decode($body, true);
    return ($data);
}

function get_all_tasks() {
    global $client;

    $res = $client->get('task');
    $body = $res->getBody();
    $data = json_decode($body, true);
    return ($data);
}

function get_task_by_id($id) {
    global $client;

    $res = $client->get('task/' . $id);
    $body = $res->getBody();
    $data = json_decode($body, true);
    return ($data);
}

function get_all_task_variables_by_id($id) {
    global $client;

    $res = $client->get('task/' . $id . '/variables');
    $body = $res->getBody();
    $data = json_decode($body, true);
    return ($data);
}

function set_assignee_for_task_by_id($id) {
}

function complete_task($id, $variables) {
    global $client;

    $task_url = 'task/' . $id . '/complete';
    $res = $client->post($task_url, [
            GuzzleHttp\RequestOptions::JSON =>
                    ['variables' => $variables]
    ]);
    $body = $res->getBody();
    $data = json_decode($body, true);
    return ($data);
}

//======================================================================
// VARIABLE TYPE HELPERS FOR CAMUNDA
//======================================================================
require_once(__DIR__ . '/classes/camunda/camunda_var.php');

function camunda_string($value) {
    return new camunda_var($value, 'string');
}

//TODO: muss noch geparsed werden?
function camunda_int($value) {
    return new camunda_var($value, 'integer');
}

//TODO: muss noch geparsed werden?
function camunda_double($value) {
    return new camunda_var($value, 'double');
}

//TODO: muss noch geparsed werden?
function camunda_boolean($value) {
    return new camunda_var($value, 'boolean');
}

function camunda_date($iso_date_string) {
    return new camunda_var($iso_date_string, 'date');
}

// convert epoch timestamp to ISO format (12345546342 -> 2019-01-30T00:00:00.000+0100)
function epoch_to_iso_date($epoch_string) {
    return strftime('%Y-%m-%dT%H:%M:%SZ', $epoch_string);
}

function camunda_date_from_form($epoch_string) {
    $date = epoch_to_iso_date($epoch_string);
    return new camunda_var($date, 'date');
}
