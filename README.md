# ctrlpmp3
 
Ctrl-P MP3 is a web based music player with fuzzy search similar to popular text editors such as Visual Studio Code, Atom, and Sublime Text. It supports nearly all audio file types, audio metadata, playlists, and shuffling music. 

Ctrl-P MP3 can be deployed on any standard LAMP stack environment. If you deploy on a server, please see the extra steps under the Server Deployment section below.

# Local Deployment

Steps:
1. Download XAMPP https://www.apachefriends.org/index.html or your preferred LAMP setup
2. Clone this repo to your computer using git or download it as a zip using the green button above (Code > Download Zip)
3. Put the repo or extracted zip into your htdocs folder. On Windows and XAMPP, it would be C:/xampp/htdocs assuming you have a standard installation. You now have a folder C:/xampp/htdocs/ctrlpmp3 after this step.
4. Ensure Apache and MySQL are running. On XAMPP, you can start them with the XAMPP Control Panel
5. Open the following in your web browser http://localhost/phpmyadmin/
6. Click on the SQL button and run the SQL from ctrlpmp3/maria/setup_db.sql. Quick link: https://github.com/DaveM7788/ctrlpmp3/blob/main/maria/setup_db.sql. The easiest way is to copy and paste everything from the aforementioned file into phpMyAdmin and click Go
7. Now open http://localhost/ctrlpmp3 in your browser
8. Login with username: ctrlpuser and password: BestSongIsRenegade
9. (Optional) Go to Settings for ctrlpuser and change the default password. Quick link: http://localhost/ctrlpmp3/updateDetails.php
10. In your file manager, place all of your music files into ctrlpmp3/0_Upload_Music_Here. Note the ctrlpmp3 folder lives inside C:/xampp/htdocs or the analog for your specific installation
11. In ctrlpmp3, go to Sync Music and click the Sync Music. Quick link: http://localhost/ctrlpmp3/sync.php. It's recommended to hard refresh your browser for fuzzy match to work
12. Enjoy listening to your music!

# Server Deployment
Coming Soon

# References
This project makes use of the excellent fuzzy match library by Forrest Smith. See more info at https://www.forrestthewoods.com/blog/reverse_engineering_sublime_texts_fuzzy_match/.

This project uses the PHP library getID3 by James Heinrich.

This project was based off the following web media player by Reece Kenney. See more info at https://www.udemy.com/course/spotify-clone/.
