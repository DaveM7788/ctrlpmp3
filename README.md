# ctrlpmp3
 
Ctrl-P MP3 is a web based music player with fuzzy search similar to popular text editors such as Visual Studio Code, Atom, and Sublime Text. It supports nearly all audio file types, audio metadata, playlists, and shuffling music. 

Ctrl-P MP3 can be deployed on any standard LAMP stack environment. If you deploy on a server, please see the extra steps under the Server Deployment section below.

# Local Deployment

Steps:
1. Download XAMPP https://www.apachefriends.org/index.html or your preferred LAMP setup
2. Clone this repo to your computer using git or download it as a zip using the green button above (Code > Download Zip)
3. Put the repo or extracted zip into your htdocs folder. On Windows and XAMPP, it would be C:/xampp/htdocs assuming you have a standard installation. You now have a folder C:/xampp/htdocs/ctrlpmp3 after this step
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
You can install Ctrl-P MP3 through most cloud providers or shared hosts. Below is a procedure for installing Ctrl-P MP3 on Ubuntu Server 20.04 on Digital Ocean. Forewarning, it can be a lengthy process

Steps:
1. Create or login into your Digital Ocean account
2. Select the green Create button on top and select droplet
3. Where it says choose an image, select Ubuntu 20.04 or the latest Ubuntu LTS
4. Choose a plan as needed. The basic 1 CPU, 25GB Disk, and 1000GB Transfer option should be enough
5. Choose the data center, ssh keys, passwords, and/or assigned project per your personal preference and then create the droplet
6. SSH into your droplet and use the following commands
```
$ ssh root@11.111.11.11
$ apt update
$ apt install apache2
$ ufw allow in "Apache Full"
```
7. Check that Apache exists by typing your server IP address into your web browser. Next, install mysql as follows
```
$ apt install mysql-server
$ mysql_secure_installation
```
8. Follow the prompts for mysql secure installation. Next login into mysql and alter the root user password. The 'password' on the 2nd line below is just a placeholder
```
$ mysql
mysql > ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';
mysql > FLUSH PRIVILEGES;
mysql > exit
```
9. Now we must install php with following commands
```
$ apt install php libapache2-mod-php php-mysql php-mbstring
```
10. Change directories and clone the ctrlpmp3 repo as follows
```
$ cd /var/www/html
$ git clone https://github.com/DaveM7788/ctrlpmp3
```
11. Change the blank password in config.php to whatever you used for your actual mysql password
```
$ cd /ctrlpmp3/includes
$ nano config.php
```
12. This will open config.php in a terminal based text editor. Use the arrow keys to navigate to the line shown below. Replace the blank "" with your password. Ctrl-O to save file and then Ctrl-X to quit
```php
$con = mysqli_connect("localhost", "root", "passwordgoeshere", "ctrlpmp3");
```
13. Now change directory to /var/www/html/ctrlpmp3/maria and set up the database as follows
```
$ cd /var/www/html/ctrlpmp3/maria
$ mysql -u root -p < setup_db.sql
$ mysql -u root -p
mysql > use ctrlpmp3;
mysql > exit
```
14. Now in your browser go to 11.111.11.11/ctrlpmp3 with your browser, replacing 11.111.11.11 with your droplet's IP address. You should see the ctrlpmp3 entry page
15. Login with username: ctrlpuser and password: BestSongIsRenegade
16. (Not Optional This Time) Go to Settings for ctrlpuser and change the default password
17. You can use sftp to upload music to your server as follows. Ensure you are no longer in SSH
```
$ sftp root@11.111.11.11
sftp > cd /var/www/html/ctrlpmp3/0_Upload_Music_Here
```
18. In the line below, Music is a local folder that contains music that you want to be copied to your server side Ctrl-P MP3 instance. We transfer everything inside of Music into the server directory /var/www/html/ctrlpmp3/0_Upload_Music_Here
```
sftp > lcd Music
sftp > put -r .
```
19. (Optional) rsync can be used instead of sftp and should be much faster for future uploads. rsync comes standard with most nix systems, but on Windows you will need to install Windows Subsystem for Linux or find some other method. In this example your terminal working directory is one level up from Music
```
$ rsync -azvh Music/ root@11.111.11.11:/var/www/html/ctrlpmp3/0_Upload_Music_Here
```
20. Adjust timeout for apache2 on your server for large music uploads. It is likely that you will have to do this. Find the line where it says Timeout and change it to a high number, 2300 for example
```
$ ssh root@11.111.11.11 
$ nano /etc/apache2/apache2.conf
```
```
Timeout 2300
```
```
$ service apache2 restart
```
21. You also need adjust the settings for php for large music uploads. Replace 7.4 with whatever php version you have
```
$ cd /etc/php/7.4/apache2
$ nano php.ini
```
22. Find the lines where it says max_execution_time and max_input_time and change both of them to a higher number
```
max_execution_time = 3000
max_input_time = 6000
```
```
$ service apache2 restart
```
23. To save album artwork and fuzzy match data we must adjust permissions of the relevant directories. www-data is the user for the apache2 web server
```
$ chown -R www-data:www-data /var/www/html/ctrlpmp3/assets/images/artwork
$ chown -R www-data:www-data /var/www/html/ctrlpmp3/assets/js
```
24. You can now Sync your music in your server's Ctrl-P MP3 instance by going to Sync Music like you normally would for a local instance of Ctrl-P MP3. Remember to Ctrl-Shift-R to hard refresh after syncing music
25. At this point everything should be working - listening to music, log in, fuzzy match etc. Below are further security settings that are recommended

# Security for Server Deployment
1. Disable directory browsing on apache2. After doing this, you will no longer be able to see all your music files by typing 11.111.11.11/ctrlpmp3/0_Upload_Music_Here
```
$ ssh root@11.111.11.11
$ cd /etc/apache2
$ nano apache2.conf
```
Find the lines as shown below (around line 160). And remove "Indexes"
```
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
</Directory>
```
so you now have
```
<Directory /var/www/>
        Options FollowSymLinks
        AllowOverride None
        Require all granted
</Directory>
```
```
$ service apache2 restart
```
2. Remove index.html from apache2. This file is not needed and will make it harder for others to determine what software your server is running
```
$ cd /var/www/html
$ rm index.html
```
3. Turn off server signatures for apache2. This is in line with point 2 and will make it harder to determine what software your server is running. Find ServerTokens and set it as Prod. Find ServerSignature and set it as Off
```
$ cd /etc/apache2/conf-enabled
$ nano security.conf
```
```
ServerTokens Prod
ServerSignature Off
```
```
$ service apache2 restart
```
4. Create a new user account that can use sudo instead of using the root account
```
$ adduser yourusername
$ usermod -aG sudo yourusername
```
After verifying you can ssh in using your new account, you can disable ssh logins for the root account. Ensure you are changing the sshd_config and not the ssh_config
```
$ sudo nano /etc/ssh/sshd_config
```
Disable root ssh login by changing the line as shown below and then restart ssh
```
PermitRootLogin no
```
```
$ sudo service ssh restart
```
5. Configure Apache to only allow traffic from your IP address(es). You can find your IP address here https://whatismyipaddress.com/
```
$ sudo nano /etc/apache2/apache2.conf
```
Find the line as shown below
```
<Directory /var/www/>
        Options FollowSymLinks
        AllowOverride None
        Require all granted
</Directory>
```
Change it to require your IP address. You can allow from more than one IP address as shown below
```
<Directory /var/www/>
        Require ip 11.111.11.11
        Require ip 12.111.11.11
</Directory>
```
```
$ sudo service apache2 restart
```
6. Enable HTTPS by following the guide linked below. This is of particular importance if you plan to access your server instance from public Wi-Fi
https://www.digitalocean.com/community/tutorials/how-to-create-a-self-signed-ssl-certificate-for-apache-in-ubuntu-18-04

# References
This project makes use of the fuzzy match library by Forrest Smith. See more info at https://www.forrestthewoods.com/blog/reverse_engineering_sublime_texts_fuzzy_match/

This project uses the PHP library getID3 by James Heinrich. See the readme and source for getID3 by clicking on it above

This project was based off the following web media player by Reece Kenney. See more info at https://www.udemy.com/course/spotify-clone/

More info on server set up can be found here. https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-ubuntu-18-04