<?php

/**
 * Print latest watchdog events from DB.
 * Use to list latest error from watchdog table and print the severity and event type.
 * No worries to run this script since it is just read from database and print the result.
 * @category Drupal PHP Script.
 * @version 1.0
 * @author Saud [@samaphp]
 */

define('DRUPAL_ROOT', getcwd());

include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

// Prepare seveirty so we can print a readable word instead of only severity number.
$severity_levels = watchdog_severity_levels();
$logs = db_select('watchdog')
  ->fields('watchdog')
  ->orderBy('timestamp', 'DESC')
  ->range(0, 15)
  ->execute();
foreach ($logs as $log) {
  if (isset($severity_levels[$log->severity])) {
    print '(' . $severity_levels[$log->severity] . ') ';
  }
  print '[' . check_plain($log->type) . '] ';
  print check_plain(strtr($log->message, unserialize($log->variables)));
  print '<HR>';
}
