<?php

/* List of installed additional extensions. If extensions are added to the list manually
	make sure they have unique and so far never used extension_ids as a keys,
	and $next_extension_id is also updated. More about format of this file yo will find in 
	FA extension system documentation.
*/

$next_extension_id = 5; // unique id for next installed extension

$installed_extensions = array (
  0 => 
  array (
    'name' => 'German Austrian COA - General',
    'package' => 'chart_de_AT-general',
    'version' => '2.3.0-3',
    'type' => 'chart',
    'active' => true,
    'path' => 'sql',
    'sql' => 'de_AT-general.sql',
  ),
  1 => 
  array (
    'name' => 'German Switzerland COA (4 digits accounts)',
    'package' => 'chart_de_CH-general',
    'version' => '2.3.0-3',
    'type' => 'chart',
    'active' => true,
    'path' => 'sql',
    'sql' => 'de_CH-4digit.sql',
  ),
  2 => 
  array (
    'name' => 'German general COA',
    'package' => 'chart_de_DE-general',
    'version' => '2.3.0-3',
    'type' => 'chart',
    'active' => true,
    'path' => 'sql',
    'sql' => 'de_DE-general.sql',
  ),
  3 => 
  array (
    'name' => 'British COA',
    'package' => 'chart_en_GB-general',
    'version' => '2.3.0-2',
    'type' => 'chart',
    'active' => true,
    'path' => 'sql',
    'sql' => 'en_GB-general.sql',
  ),
  4 => 
  array (
    'name' => 'Default American coa (5 digits)',
    'package' => 'chart_en_US-general',
    'version' => '2.3.0-2',
    'type' => 'chart',
    'active' => true,
    'path' => 'sql',
    'sql' => 'en_US-general.sql',
  ),
);
?>