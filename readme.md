##Ladybug (previously metamin) - DBpedia Validation Errors Power Tool

ABOUT
The application focuses on giving tbe users a quick summary of the current state about validation tests and provide an easy way to tackle validation issues with few clicks. The users (power users of DBpedia) are able to view aggregate information based on the errors and their metadata (e.g. how many countries have a prime minister that is dead?). The errors metadata will be editable through the application, so that the users can classify the errors and make it easier for others to navigate (todo). The application can be configured to work with other datasets and validation schemes.

INSTALLATION
To install the application make a clone of its repository. Afterwards, use composer to download the required libraries, by running composer install on the root folder.

CONFIGURATION
You can configure the default endpoints of the application into app/config/sparqlmodel.php