# BRIZI - Above it all

This is the main repository and branch for brizi.

For you hackathon-ooligans, we recommend to start off with the starter_snippets.
In the directories there, you can find and use our controlPi.php and gallery.php
API.
From there, venture off into the admin/, mobile/, or web/ directories for some
applications we've made. Feel free to ask us about anything throughtout!


## To start coding:
Clone this repo to your local computer (and place in a path accessible by apache
or other webserver)
```
git checkout -b new_branch
```
Develop away, and let your imagination take flight!


## Directory Structure:
```
|- root
 |- gallery.php -- image fetching and deletion function
 |- voting.php -- voting functions
 |- admin/ -- files for media director dashboard
 |- images_branding/ -- branding content custom to each event
 |- images_ftp/ -- images retrieved from brizi
 |- images_snaps/ -- images captured by brizi (ftp the content of this folder to images_ftp on the online server)
 |- mobile/ -- files for mobile view
 |- scripts/ -- relevant scripts e.g taking snapshot of a livestream
 |- starter_snippets/ -- relevant scripts e.g taking snapshot of a livestream
  |- controls/ -- controls API that is running on the pi; you must run this code on the pi for the motors and camera to work
  |- image_gallery/ -- prettily put images in a gallery for you
  |- live_stream_player/ -- view the stream coming from the pi in the browser (make sure your IP address is configured)
  |- twitter_widget/ -- hype up your page using a twitter widget
 |- web/ -- files for web view
```


