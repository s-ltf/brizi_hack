#!/bin/bash
DATETIME=`date +%Y-%m-%d_%H-%M-%S`;
/usr/local/bin/ffmpeg -i rtmp://10.0.0.14:1935/BriziLive/myStream -ss 0 -s 640x480 -vframes 1 -vcodec mjpeg -f image2 ./images_snaps/snap_$DATETIME.jpeg 2>&1
echo "Finished in .sh file";