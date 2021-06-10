# Useful Scripts for Ctrl-P MP3
This folder contains some python scripts that aid in operating or testing Ctrl-P MP3

# Quick Open Ctrl-P MP3
See the script, ctrlpmp3-xampp.py. This script will start apache and mariadb and then open Ctrl-P MP3 in the default browser. This will make starting Ctrl-P MP3 almost as easy as clicking a normal desktop application. You can copy it to your desktop for easy access. You must have Python and XAMPP installed for this to work
```
$ python ctrlpmp3-xampp.py
```

# Login Page Throttle
Ensure you have python and the mechanize package installed. Verify that you can't make hundreds of form posts quickly by adjusting the variable numberofhits inside of loginthrottle.py. login-handler.php controls how much logins are throttled. Default is .3 seconds
```
$ python3 -m pip install mechanize
```
```
$ python3 loginthrottle.py
```