<?php

/**
 * Check existence of database files.
 * Use to list all files that stored in database but is not exists in files folder.
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

$query = db_select('file_managed', 'fm');
$query->fields('fm', array('fid', 'uri'));
$result = $query->execute();
$filesCount = 0;
$missedFilesCount = 0;
print '<h2>List of missed files</h2>';
$filesArray = array();
$fileEntities = array();
while($record = $result->fetchAssoc()) {
  $filesCount++;

  // Check if file is not exists.
  if (file_destination($record['uri'], FILE_EXISTS_ERROR)) {
    // This file is not exist.
    $missedFilesCount++;
    $entity_type = '';

    $fileQuery = db_select('file_usage', 'fu');
    $fileQuery->fields('fu', array('type', 'id'))
    ->condition('fid', $record['fid'], '=');
    $fileResult = $fileQuery->execute()->fetchAssoc();
    if(isset($fileResult['type'])){
      switch ($fileResult['type']) {
        case 'field_collection_item':
          // Load field collection.
          $fieldQuery = db_select('field_collection_item', 'fci');
          $fieldQuery->fields('fci', array('item_id', 'field_name'))
          ->condition('item_id', $fileResult['id'], '=');
          $fieldResult = $fieldQuery->execute()->fetchAssoc();

          $fieldQuery2 = db_select('field_data_'.$fieldResult['field_name'], 'fd');
          $fieldQuery2->fields('fd', array('entity_type', 'bundle', 'entity_id'))
          ->condition($fieldResult['field_name'].'_value', $fieldResult['item_id'], '=');
          $fieldResult2 = $fieldQuery2->execute()->fetchAssoc();

          $entity_type = $fieldResult2['entity_type'].' - '.$fieldResult2['bundle'].' - '.$fieldResult2['entity_id'].' ['.$fieldResult['field_name'].']';
          break;

        case 'node':
          // Still need to get the user and field name.
          $entity_type = $fileResult['type'].' - '.$fileResult['id'];
        break;

        case 'profile2':
          // Still need to get the user and field name.
          $entity_type = $fileResult['type'].' - '.$fileResult['id'];
        break;

        case 'user':
          // Still need to get the user and field name.
          $entity_type = $fileResult['type'].' - '.$fileResult['id'];
        break;

        case 'comment':
          // Still need to get the user and field name.
          $entity_type = $fileResult['type'].' - '.$fileResult['id'];
        break;

        default:
          $entity_type = 'UNKNOWN';
          break;
      }
    }else{
      if(count(file_usage_list(file_load($record['fid']))) > 0){
        $entity_type = 'Usage: '.print_r(file_usage_list(file_load($record['fid'])), TRUE);
      }
    }

    $filesArray[] = drupal_realpath($record['uri']).' '.l('Link', file_create_url($record['uri'])).' ('.$entity_type.') [usage: '.count(file_usage_list(file_load($record['fid']))).']';

  }

}

print 'Total Files: '.$filesCount;
print '<br />';
print 'Total Missed Files: '.$missedFilesCount;

print '<HR><PRE>';

print_r($filesArray);
