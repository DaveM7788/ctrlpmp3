import mechanize
from datetime import datetime
# will likely have to install mechanize through pip

url = 'http://localhost/ctrlpmp3/register.php'
mech = mechanize.Browser()
mech.set_handle_robots(False)
mech.open(url)
starttime = datetime.now()

x = 0
numberofhits = 10
while x < numberofhits:
    mech.select_form(id="loginForm")
    mech["loginUsername"] = 'foo'
    mech["loginPassword"] = 'bar'
    res = mech.submit()
    content = res.read()
    print(content)
    x += 1

print(str(numberofhits) + " login requests took " + str(datetime.now() - starttime))