# moodle-local_ws_logdata
Webservices to retrieve data from log store

## Purpose
The reason this plugin was developed was to provide an efficient way of accessing specific data from the Moodle logs
with the aim of inserting this data into a data warehouse so a comprehensive picture of learner activity can be gained.

## Usage

### Functions
**local_ws_logdata_userlogins** Get the login events.
/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=local_ws_logdata_userlogins&wstoken={token}&days=5

#### Required capabilities
* report/log:view

#### Optional parameters
* **days** integer. The number of days of data retrieve looking back from now. 
e.g. &days=7 will retrieve the past 7 days of data. Default is 5.
* **pagesize** integer. The maximum number of records to return. Default is 10000.
* **page** integer. The page number for the subset of records.

**Note:** days and pagesize values will be saved as defaults for future calls.
E.g. you can call &days=7&pagesize=1000 once to set the defaults and omit these parameters from future calls. 