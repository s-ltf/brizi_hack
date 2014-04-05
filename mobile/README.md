# Brizi mobile app and view

Starting point for brizi mobile app. 

Proposed app:  
Cordova based mobile application runs on mobile device, single element, iframe pointing at remote of type www.flybrizi.com/mobile/eventName      
Angular checks viewport size and redirects to either mobile or web view.  

Considerations:  
Livestream over js to native bridge may not work/lag if we decide to build features into/inside of cordova.  
Cordova does not currently support web sockets, if we decide to use socket.io and events to refresh gallery over an interval, this may be problematic.  

## Directory setup

```
-base
|-gulpfile.js -- task runner, compiles src files, watches for changes
|-bower.json -- lists front end dependancies -> run with 'bower install' -> default output is components_bower
|-package.json -- lists gulp dependencies and any node based deps -> run with 'sudo npm install'    
|-index.html -- generated index.html containing header and ng-view  
|-/assets -- contains assets for index.html, i.e css, js, templates -- generated from source and font & branding image  
|-/src -- contains coffescript, jade and stylus source files for app  
|-/brizi -- cordova generated directory containing cordova web view file -> i.e i ran "cordova create brizi", no platforms added or work done 
```  

## Quick Start
Run through webserver e.g. with apache 2, navigate to 127.0.0.1 with folder in Apache2 config  

## Developer documentation 
If you don't already have it, install nodeJS. You can find it online.  
'-g' flag stands for install globally, this documentation assumes you have nothing installed.  
Bower lists and installs our front-end requirements like bootstrap and angular.   
```
npm install -g bower && bower install
```
Source files are in /src, and are of the type .coffee, .styl and .jade, i.e coffescript, stylus & jade, representing javascript, css and html pre-processors respectively. To compile these files, with gulp dependencies installed i.e.by running
```
npm install
```
in the package.json directory, run
```
npm install -g gulp && gulp
```
in the directory containing gulpfile.js. This will compile these source files to specified output directories.  
To work with gulp, add more source files, specify specific output destinations, and get a clear idea of what it does, read gulpfile.js, it's easier to grasp than a gruntfile, they both basically serve the same purpose.  
The main app things are in tpls in assets, angular populates div(ng-view) with the appropriate one.   

## TODO :
+ Cordova add and compile for mobile platforms
+ Investigate and integrate custom twitter wall
+ Implement event based polling for images
+ Implement voting system
+ Fix how the routing active class is handled