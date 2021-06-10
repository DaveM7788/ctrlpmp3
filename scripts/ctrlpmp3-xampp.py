import webbrowser
import subprocess
import platform

def open_ctrlp(apache, maria):
    print("Starting Ctrl-P MP3 ....")
    subprocess.Popen(apache)
    subprocess.Popen(maria)
    webbrowser.open("http://localhost/ctrlpmp3")

if platform.system() == "Windows":
    apache = "C:/xampp/apache_start.bat"
    maria = "C:/xampp/mysql_start.bat"
    open_ctrlp(apache, maria)
elif platform.system() == "Darwin":
    print("OS X not implemented yet")
elif platform.system() == "Linux":
    print("Linux not implemented yet")
else:
    print("Platform is not supported. Exiting")