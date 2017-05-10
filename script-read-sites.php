<?php
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

  // Read sites folder and exclude no need elements.
  $site_path = DRUPAL_ROOT . DIRECTORY_SEPARATOR . 'sites' . DIRECTORY_SEPARATOR;
  $excluded_dir = array('.', '..', 'all', 'default', 'README.txt', 'example.sites.php');
  $sites_list = scandir($site_path);

  $excluded_other_dir = array();
  foreach ($sites_list as $val) {
    if (!is_dir($site_path . $val)) {
      $excluded_other_dir[] = $val;
    }elseif(!file_exists($site_path . $val. DIRECTORY_SEPARATOR .'settings.php')){
      $excluded_other_dir[] = $val;
    }elseif( in_array($val, $excluded_dir) ){
      $excluded_other_dir[] = $val;
    }
  }

  // Exculding unwanted list of directories and files if any?
  if (count($excluded_dir)) {
    $sites_list = array_diff($sites_list, $excluded_other_dir);
  }

  // Order by name.
  $sites_list = array_unique(array_combine($sites_list, $sites_list));
  ksort($sites_list);

  // Print the result.
  print '<PRE>';
  print_r($sites_list);
