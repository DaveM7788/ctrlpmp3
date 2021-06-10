import webbrowser
import subprocess

apache = "C:/xampp/apache_start.bat"
maria = "C:/xampp/mysql_start.bat"
subprocess.Popen(apache)
subprocess.Popen(maria)
webbrowser.open("http://localhost/ctrlpmp3")