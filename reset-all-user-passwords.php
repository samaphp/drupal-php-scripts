<?php

/**
 * BE CAREFUL WHEN USE THIS SCRIPT.
 * Reset all user passwords and send email to reset password.
 * Use to force all users to reset password and to if there is any password leak suspect.
 * No worries to run this script since it is just read from database and print the result.
 * @todo Show node details if the file saved as node field.
 * @todo Show user details if the file saved in user object field.
 * @todo Show user profile2 details if the file saved as profile2 field.
 * @todo Show node details if the file saved as comment field.
 * @category Drupal PHP Script.
 * @version 1.0.1
 * @author Saud [@samaphp]
 */

define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

// uncomment this line to allow this script to work..
exit;

$query = db_select('users', 'u');
$query->fields('u', array('uid'))->condition('uid', 1, '>'); // You can change this to 0 to change user 1 password also.
$result = $query->execute();
$usersCount = 0;
while($record = $result->fetchAssoc()) {
  $usersCount++;
  $thisUser = user_load($record['uid'], TRUE);

  // 1. Change password to new password.
  user_save($thisUser, array('pass' => '123456'));

  // 2. Send email to reset password.
  _user_mail_notify('password_reset', $thisUser);
}

print 'Total Users: '.$usersCount;
