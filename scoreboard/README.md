# Scorebord
## WARNING
This application is old and out of date, i tell you this so you know what youre getting into.  Once this has been reviewed and validated, this warning will go away. 

## What is here
/apache-config/
This contains an example apache drop-in configuration

/app/
this is the actual application, this goes in your web root

/supporting/
the empty database, and the composer config that was initually used to build the application are in here as well.  

The database should be populated and placed in (or mapped to) a directory above the web root called db/app.db  you can find this path in the index.html if you would like to change it.

/Dockerfile
If you are familiar with docker, you know what to do with this.  It can be used to build the application into a container image

/pod.yaml
This pod definition can be used with K8s or podman to build a pod to run this application in. 