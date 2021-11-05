# Code Generation Script
## What is here
Recently updated for Python3, needs testing

codes-db.py is a script which will read from codes.db (an sqlite database) to return a code in various formats.  Currently you can output a bare code, or a "web ready" code, which will include a content-type suitable for using in an HTML include.  The only required argument is a gate-id.  

./codes-db.py -g 1 (would give you a code for the flag that matches gate-id 1 in the database)