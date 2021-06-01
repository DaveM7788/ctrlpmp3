# ctrlpmp3
 
Ctrl-P MP3 is a web based music player with fuzzy search similar to popular text editors such as Visual Studio Code, Atom, and Sublime Text. It supports nearly all audio file types, audio metadata, playlists, and shuffling music. 

Currently, you can fuzzy search by song title. Future versions should include fuzzy search by artist, album, and genre.

Ctrl-P MP3 can be deployed on any standard LAMP stack environment. If you deploy on a server, please see the extra recommended security steps under the Server Deployment section below.

# Local Deployment
This video shows how you can deploy Ctrl-P MP3 through XAMPP on your personal laptop or desktop.

Steps:
1. Download XAMPP https://www.apachefriends.org/index.html or your preferred LAMP setup
2. Download this repository as a zip (Code > Download Zip) or just clone it as any normal repo
3. Extract the files into your htdocs folder. On Windows and XAMPP, it would be C:/xampp/htdocs assuming you have a standard installation
4. Ensure Apache and MySQL are running. On XAMPP, you can start them with the XAMPP Control Panel
5. Open the following in your web browser http://localhost/phpmyadmin/
6. Click on the SQL button and run the SQL from ctrlpmp3/maria/setup_db.sql
7. Now open http://localhost/ctrlpmp3 in your browser
8. Login with username: ctrlpuser and password: BestSongIsRenegade
9. (Optional) Go to Settings for ctrlpuser and change the default password. Quick link: http://localhost/ctrlpmp3/updateDetails.php?
10. In your file manager, place all of your music files into ctrlpmp3/0_Upload_Music_Here
11. In ctrlpmp3, go to Sync Music and click the Sync Music. Quick link: http://localhost/ctrlpmp3/sync.php?. It's recommended to hard refresh your browser for Ctrl-P to work
12. Enjoy listening to your music!

# Server Deployment
This video shows how you can deploy Ctrl-P MP3 on an Ubuntu server VPS in less than 5 minutes. It also includes the extra recommended security steps described above.

Link goes here

In summary:
1. DO this
2. Do that
3. Blah

# References
This project makes use of the excellent fuzzy match library by Forrest Smith. See more info at https://www.forrestthewoods.com/blog/reverse_engineering_sublime_texts_fuzzy_match/.

This project uses the PHP library getID3 by James Heinrich.

This project was based off the following web media player by Reece Kenney. See more info at https://www.udemy.com/course/spotify-clone/.