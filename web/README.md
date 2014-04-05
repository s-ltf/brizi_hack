brizi_web
=========


<h3>Setting up event process:</h3>

<b>DB:</b><br>

table_name
poll options
poll end date

Files:
update index.html with branding images
update index.html with EVENT_NAME (should update table name itself)
update admin.html with EVENT_NAME (should update table name itself)
update index.html with twitter hashtags and display name
update ilightbox/src/js/ilightbox.js with twitter pre-population tags
update overlay text, format, and links

Server:
create directory at ~/public_html on server.. push here
create symlink at ~/public_html for shorter name of event page link

Google Analytics:
Updates required per page.


<b>How to test:</b><br>

Voting works.
Vote results update.
Reseting poll works.
Gallery loads.
Adding image to gallery works.
Removing image from gallery works.
Twitter and Facebook links from gallery work.
Twitter widget is updating.
Live-stream loads, Sami can confirm it works.


<b>MYSQL Related:</b><br>

CREATE TABLE voting_event_name LIKE voting_template;
[Optional] INSERT INTO voting_event_name SELECT * FROM voting_template;
