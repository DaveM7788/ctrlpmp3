# Testing Ctrl-P MP3
Testing is usually done through various python scripts or curl commands

# Login Page Throttle
Ensure you have python and the mechanize package installed. Verify that you can't make hundreds of form posts quickly by adjusting the variable numberofhits. login-handler.php controls how much logins are throttled
```
$ python3 -m pip install mechanize
```
```
$ python3 loginthrottle.py
```