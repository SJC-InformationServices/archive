// JavaScript Document

function makerequest(url, handleresponse)
{
var http = createRequestObject();
http.open('post', url,true);
http.onreadystatechange = function(){
  if ( http.readyState == 4 ) {
    if ( http.status == 200 ) {
     handleresponse(http.responseText);

    } else {
      alert(error);
    }
  }
};
http.send(null);
}

function runsearch()
{
if(document.getElementById('selectedmedia').value.length < 1 || document.getElementById('selectedpage').value.length < 1 || document.getElementById('searchstring').value.length < 1)

{

document.getElementById('selectedpage').style.background = 'yellow';
var searchstring = encodeURIComponent(document.getElementById('searchstring').value);
var selectedmedia = encodeURIComponent(document.getElementById('selectedmedia').value);
//var selectedpage = encodeURIComponent(document.getElementById('selectedpage').value);
var selectedpage = 1;
var url = 'includes/articlesearch.php?searchstring=' + searchstring + '&selectedmedia= ' + selectedmedia + '& selectedpage= ' + selectedpage;
makerequest(url,  searchresponse);

}
else 
{
var searchstring = encodeURIComponent(document.getElementById('searchstring').value);
var selectedmedia = encodeURIComponent(document.getElementById('selectedmedia').value);
var selectedpage = encodeURIComponent(document.getElementById('selectedpage').value);
var url = 'includes/articlesearch.php?searchstring=' + searchstring + '&selectedmedia= ' + selectedmedia + '& selectedpage= ' + selectedpage;
makerequest(url,  searchresponse);
}
}

/*function runsearch()
{
//alert("params are  " + selectedmedia + selectedpage);
var searchstring = encodeURIComponent(document.getElementById('searchstring').value);
if(document.getElementById('selectedmedia').value.length < 1)
{
var selectedmedia = "";
}
else 
{
var selectedmedia = encodeURIComponent(document.getElementById('selectedmedia').value);
}
if(document.getElementById('selectedpage').value.length < 1)
{
var selectedpage = "";
}
else 
{
var selectedpage = encodeURIComponent(document.getElementById('selectedpage').value);
}
var url = 'includes/articlesearch.php?searchstring=' + searchstring + '&selectedmedia= ' + selectedmedia + '& selectedpage= ' + selectedpage;
makerequest(url,  searchresponse);
} */

function searchresponse(response)
{
if(document.getElementById('searchwindow'))
{
 document.getElementById('searchwindow').parentNode.innerHTML=response;
 document.getElementById('dhtml_goodies_id1').style.display='Block';
}
else
{
  customFunctionCreateWindow(response, '750', '325', '500', '440');
}
}
