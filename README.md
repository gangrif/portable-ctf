# Portable-CTF
## Introduction and Background
I'll spare you the long and involved story, but in 2015, I decided to build a CTF in a hat, and took it to a security conference.  This required that some code be developed in order to facilidata CTF code generation, and something to track participants scores in.  The code in this repository is that code. 

Much of the code here has not changed much since 2015, and could probably benefit from an actual developer having a look at it to identify things that need to be updated or improved.  If you think you are that developer, please reach out, I am open to collaboration! That's why I am putting the code out here. 

## What is here
### Scoreboard
This is a php web application that was originally written by a Co-worker of mine, Tim.  It was written to work on Red Hat's OpenShift Online, but is portable to any platform that runs PHP.  It utilizes a framework called Symphony, and at the time of initial import here probably needs some updating.  

More information on how this works in the scoreboard's readme.

### CodeGen
This is a python script, which probably needs some love as well, as it was written for python2, that will generate a code which _should_ work with the scoreboard.  

More information on how this works in the CodeGen readme.

## How it works
The scoreboard is meant to run on some manner of locked down web host.  I have included some information that will help you build it into a docker or podman container, and a pod.yaml that will make running it on podman that much easier. 

The scoreboard uses an sqlite database that you will need to populate with flag codes, and random codes.  These flag codes and random codes also need to be added to the sqlite databse that the codegen script uses to hand out codes. 

The codegen script will generate a code that combines the flag code with a random code, and gives it to the participant.  The participant can then enter this code on the scorebord, and it will log the points for the participant.  Codes are non-reusable unless configured as such. 

## What to expect
As stated, this code is currently out of date, but functional.  Hopefully making it public will lead to some development help!  

Thanks! 
-gangrif