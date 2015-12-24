<?php
define('SOURCE_DIR', 'sources/*.*');
define('BUILD_DIR', 'build/');

function buildConceptSchool($school) {
    $content = <<<EOT
school_{$school['number']}
=> nrel_main_idtf:
	[{$school['name']}](* <- lang_ru;; *);

=> nrel_school_number:
	[{$school['number']}];

=> nrel_site:
	[<a href="{$school['site']}">{$school['site']}</a>]
	(*
	=> nrel_format:
		format_html;;
	*);

=> nrel_address:
	[{$school['address']}] (* <- lang_ru;; *);

=> nrel_phone:
	[{$school['phone']}] (* <- lang_ru;; *);;

concept_situation -> 
[*school_{$school['number']} <= nrel_terrain_object_location: ..position{$school['number']};;*] 
;;
school_{$school['number']}<- concept_mapped_point;;
school_{$school['number']}<- concept_terrain_object;;
school_{$school['number']}<- concept_mapped_object;;
..position{$school['number']}=> nrel_main_idtf: [{$school['longitude']} с.ш. {$school['latitude']} в.д.](*<-lang_ru;;*);;
..position{$school['number']}=> nrel_WGS_84_translation: ...
(*
-> ...
(*
<- struct_WGS_84;;
-> rrel_WGS_84_latitude: ... (*-> rrel_WGS_84_degree: [{$school['longitude']}];;*);;
-> rrel_WGS_84_longitude: ... (*-> rrel_WGS_84_degree: [{$school['latitude']}];;*);;
*);;
*);;
EOT;

    file_put_contents(BUILD_DIR . 'school_' . $school['number'] . '.scs', $content);
}

echo 'Listing files from `sources`';

foreach(glob(SOURCE_DIR) as $filePath) {
    $handle = @fopen($filePath, "r");

    if ($handle) {
        while (!feof($handle)) {
            $school = array();
            $school['name'] = trim(fgets($handle));
            $school['number'] = trim(fgets($handle));
            $school['longitude'] = trim(fgets($handle));
            $school['latitude'] = trim(fgets($handle));
            $school['address'] = trim(fgets($handle));
            $school['phone'] = trim(fgets($handle));
            $school['site'] = trim(fgets($handle));
            fgets($handle);

            buildConceptSchool($school);
        }

        fclose($handle);
    }
}