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

You can install Ctrl-P MP3 through most cloud providers or shared hosts. Below is a procedure for installing Ctrl-P MP3 on Debian on AWS. Forewarning, it can be a lengthy process

Steps:
1. Create or login into your AWS Lightsail account and create a BareOS Debian instance
2. Click the terminal icon of your instance to open the CLI or alternatively download your SSH private key and login with your SSH client of choice. If you download your private key, you need to use the -i flag
with ssh. Optionally, you can also add your key to your ssh config to no longer need the -i everytime you use ssh. To do that

```
$ cd /etc/ssh
$ sudo nano ssh_config
```
Add add the following entry like so
```
Host your_server_ip
        IdentityFile /path/yourprivekey.pem
```
After doing this, the -i flag will no longer be needed for ssh or sftp. The next step is to install a firewall and Apache2
```
$ ssh -i yourprivatekey.pem admin@11.111.11.11
$ sudo apt update
$ sudo apt install apache2
$ sudo apt install ufw
$ sudo ufw default deny incoming
$ sudo ufw default allow outgoing
$ sudo ufw allow ssh
$ sudo ufw allow http
$ sudo ufw allow https
$ sudo ufw enable
```
3. Check that Apache exists by typing your server IP address into your web browser. Next, install MariaDB as follows
```
$ sudo apt install mariadb-server
```
4. Run the command below and follow the prompts for a secure installation. It's recommended to change your db password when prompted to do so
```
$ sudo mysql_secure_installation
```
5. Now we must install php with following commands. The latest version of PHP tested is 8.2 but any version above 7.0 should work
```
$ sudo apt install php libapache2-mod-php php-mysql php-mbstring
```
6. Change directories and clone the ctrlpmp3 repo as follows
```
$ cd /var/www/html
$ sudo apt install git
$ sudo git clone https://github.com/DaveM7788/ctrlpmp3
```
7. Change the blank password in config.php to whatever you used for your actual mysql password
```
$ cd /ctrlpmp3/includes
$ nano config.php
```
8. This will open config.php in a terminal based text editor. Use the arrow keys to navigate to the line shown below. Replace the blank "" with your password. Ctrl-O to save file and then Ctrl-X to quit
```php
$con = mysqli_connect("localhost", "root", "passwordgoeshere", "ctrlpmp3");
```
9. Now change directory to /var/www/html/ctrlpmp3/maria and set up the database as follows
```
$ cd /var/www/html/ctrlpmp3/maria
$ mysql -u root -p < setup_db.sql
$ mysql -u root -p
mysql > use ctrlpmp3;
mysql > exit
```
10. Now in your browser go to 11.111.11.11/ctrlpmp3 with your browser, replacing 11.111.11.11 with your server's IP address. You should see the ctrlpmp3 entry page
11. Login with username: ctrlpuser and password: BestSongIsRenegade
12. (Not Optional This Time) Go to Settings for ctrlpuser and change the default password.
13. At this point we have verified Apache2 and MariaDB are installed correctly and you can at least login to the ctrlpmp3 server. Next we need to actually upload music. However, we need to make
same configuration changes before we can do that. First, adjust timeout for apache2 on your server for large music uploads. It is likely that you will have to do this. Find the line where it says 
Timeout and change it to a high number, 2300 for example
```
$ ssh -i yourprivatekey.pem admin@11.111.11.11 
$ nano /etc/apache2/apache2.conf
```
```
Timeout 2300
```
```
$ sudo systemctl restart apache2
```
14. You also need adjust the settings for php for large music uploads. Replace 8.2 with whatever php version you have
```
$ cd /etc/php/7.4/apache2
$ nano php.ini
```
15. Find the lines where it says max_execution_time and max_input_time and change both of them to a higher number
```
max_execution_time = 3000
max_input_time = 6000
```
```
$ sudo systemctl restart apache2
```
16. To save album artwork and fuzzy match data we must adjust permissions of the relevant directories. www-data is the user for the apache2 web server
```
$ sudo chown -R www-data:www-data /var/www/html/ctrlpmp3/assets/images/artwork
$ sudo chown -R www-data:www-data /var/www/html/ctrlpmp3/assets/js
```
17. Finally, we need to allow permissions to upload to the music folder for the admin user. (Assuming you are on Debian and AWS)
```
$  sudo chown -R $USER:$USER /var/www/html/ctrlpmp3/0_Upload_Music_Here
```
18. You can use sftp or rsync to upload music to your server as follows. For rsync skip ahead to step 20.
```
$ sftp -i yourprivatekey.pem admin@11.111.11.11
sftp > cd /var/www/html/ctrlpmp3/0_Upload_Music_Here
```
19. In the line below, Music is a local folder that contains music that you want to be copied to your server side Ctrl-P MP3 instance. We transfer everything inside of Music into the server directory /var/www/html/ctrlpmp3/0_Upload_Music_Here
```
sftp > lcd Music
sftp > put -r .
```
20. (Optional) rsync can be used instead of sftp and should be much faster for future uploads. rsync comes standard with most nix systems, but on Windows you will need to install Windows Subsystem for Linux or find some other method. In this example your terminal working directory is one level up from Music
```
$ sudo rsync -azvh Music/ admin@11.111.11.11:/var/www/html/ctrlpmp3/0_Upload_Music_Here
```

21. You can now Sync your music in your server's Ctrl-P MP3 instance by going to Sync Music like you normally would for a local instance of Ctrl-P MP3. Remember to Ctrl-Shift-R to hard refresh after syncing music
22. At this point everything should be working - listening to music, log in, fuzzy match etc. Below are further security settings that are recommended


# Security for Server Deployment

1. Disable directory browsing on apache2. After doing this, you will no longer be able to see all your music files by typing 11.111.11.11/ctrlpmp3/0_Upload_Music_Here
```
$ ssh admin@11.111.11.11
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
$ sudo systemctl restart apache2
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
$ sudo systemctl restart apache2
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
$ sudo systemctl restart apache2
```
6. Enable HTTPS by following the guide linked below. This is of particular importance if you plan to access your server instance from public Wi-Fi
https://www.digitalocean.com/community/tutorials/how-to-create-a-self-signed-ssl-certificate-for-apache-in-ubuntu-18-04

# References
This project makes use of the fuzzy match library by Forrest Smith. See more info at https://www.forrestthewoods.com/blog/reverse_engineering_sublime_texts_fuzzy_match/

This project uses the PHP library getID3 by James Heinrich. See the readme and source for getID3 by clicking on it above

This project was based off the following web media player by Reece Kenney. See more info at https://www.udemy.com/course/spotify-clone/

More info on server set up can be found here. https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-ubuntu-18-04