# ci_barcode is barcode generator for codeigniter 3 
##(tested on 3.1.8)


## How to install for new project:
-paste and merge the entire application folder to a new codeigniter project


## How to install for existing project:
-paste file : application/helpers/barcode_helper.php
-paste entire directory: application/third_party/Barcode
- edit application/config/autoload.php, add new helper barcode in autoload helper array below: 
-- $autoload['helper'] = array('barcode');

## test output:
-localhost/path_to_project/index.php/barcode?code=test
