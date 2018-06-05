// JavaScript Document

function getlayout(obj)
{

if(document.getElementById('selectedmedia').value.length < 1 || document.getElementById('selectedpage').value.length < 1)
{
alert("Media, page, must be selected.");
document.getElementById('selectedmedia').style.background = 'yellow';
document.getElementById('selectedpage').style.background = 'yellow';
}
else
{
var mediaselect = encodeURIComponent(document.getElementById('selectedmedia').value);
var pageselect = encodeURIComponent(document.getElementById('selectedpage').value);
//makerequest('getlayout.php?media='+mediaselect+'&page='+pageselect+'', display);
if(obj.getAttribute('onclick') == 'getlayout(this)')
{
var url = 'includes/getlayout.php?selectedmedia=' + mediaselect + '&selectedpage=' + pageselect;
obj.setAttribute('onclick', 'closelayout(this)');
obj.innerHTML = 'Close Layout';
var currentart = document.getElementById('currentarticlestable').parentNode;
currentart.style.display = 'none';
makerequest(url, displaylayout);
}
}
}
function closelayout(obj)
{
obj.setAttribute('onclick', 'getlayout(this)');
obj.innerHTML = 'Get Layout';
var currentart = document.getElementById('currentarticlestable').parentNode;
currentart.style.display = 'block';
var body = document.getElementsByTagName('body')[0];
if(document.getElementById('layoutwindow'))
{
var parentwindow = document.getElementById('layoutwindow');
var whitewall = document.getElementById('whitewall');
body.removeChild(whitewall);
}
}

function displaylayout(response)
{

var response = eval('(' + response + ')');
var data = response.DATA;
var templatename = data.TEMPLATE;
var pagelayout = data.PAGELAYOUT;
var errors = response.ERRORS;
var layoutdata = response.DATA.LAYOUT;
var pagewidth = data.WIDTH;
var pageheight = data.HEIGHT;

var body = document.getElementsByTagName('body')[0];


for(var i = 0; i<layoutdata.length; i++)
{
var pos = layoutdata[i].POSITION;
var x = layoutdata[i].X + 'px';
var y = layoutdata[i].Y + 'px';
var w = layoutdata[i].W + 'px';
var h = layoutdata[i].H + 'px';
var artdata = layoutdata[i].ART;
var ele = layoutdata[i].ELE;
var elelayout = layoutdata[i].ELELAYOUT

if(document.getElementById('layoutwindow'))
{
//get template window
var parentwindow = document.getElementById('layoutwindow');
}
else
{
//create template window
var parentwindow = document.createElement('div');
parentwindow.setAttribute('id', 'layoutwindow');
parentwindow.setAttribute('class', 'layoutwindow');

var whitewall = document.createElement('div');
whitewall.setAttribute('class', 'layoutwhitewall');
whitewall.setAttribute('id', 'whitewall');
body.appendChild(whitewall);
whitewall.appendChild(parentwindow);
}

var newdiv = document.createElement('div');
newdiv.setAttribute('class', 'layoutbox');
newdiv.setAttribute('id', pos +'layout');

newdiv.style.width = w;
newdiv.style.height = h;
newdiv.style.left = x;
newdiv.style.top = y;

var posdiv = document.createElement('div');
posdiv.setAttribute('class', 'posnum');
posdiv.style.left = w/2 + 'px';
if(elelayout)
{
var postext = document.createTextNode(pos + elelayout);
}
else
{
var postext = document.createTextNode(pos);
}
posdiv.appendChild(postext);
newdiv.appendChild(posdiv);

if(ele)
{
var elediv = document.createElement('div');
elediv.setAttribute('class', 'elelayout');
var eletext = document.createTextNode(ele);
elediv.appendChild(eletext);
newdiv.appendChild(elediv);
}
if(artdata.length)
{
var artdiv = document.createElement('div');
artdiv.setAttribute('class', 'artlayout');
for(var a=0; a<artdata.length; a++)
{
var artnum = artdata[a].ARTNUM;
var artname = artdata[a].ARTNAME;
var price = artdata[a].PRICE;
var additionalinfo = artdata[a].ADDITIONALINFO;
var artdatadiv = document.createElement('div');
var arttext = document.createTextNode(artnum + ' ' + price + ' ' + additionalinfo);
artdatadiv.appendChild(arttext);
artdiv.appendChild(artdatadiv);
}
newdiv.appendChild(artdiv);
}

parentwindow.appendChild(newdiv);
}
adjusty();
}

function adjusty()
{
if(document.getElementById('whitewall'))
{
var parent = document.getElementById('whitewall');
var obj1 = document.getElementById('1layout');
var objx = obj1.style.left.replace('px', '') - 5;
var objy = obj1.style.top.replace('px', '') - 10;
for(var i = 0; i < parent.getElementsByTagName('div').length; i++)
{
var box = parent.getElementsByTagName('div')[i];
if(box.className == 'layoutbox')
{
var y = box.style.top.replace('px', '');
var newy = y - objy;
box.style.top = newy+'px';

var x = box.style.left.replace('px', '');
var newx = x - objx;
box.style.left = newx+'px';
}
}
}
}


