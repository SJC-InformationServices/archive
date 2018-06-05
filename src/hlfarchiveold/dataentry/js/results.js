// JavaScript Document
function createRequestObject(){
	var request_o; //declare the variable to hold the object.
	var browser = navigator.appName; //find the browser name
	if(browser == "Microsoft Internet Explorer"){
		/* Create the object using MSIE's method */
		request_o = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		/* Create the object using other browser's method */
		request_o = new XMLHttpRequest();
	}
	return request_o; //return the object
}

var http = createRequestObject(); 

function submitform(id)
{
if(id == 'selectedmedia')
{
var media = document.getElementById(id).value;
self.location='workindex.php?selectedmedia=' + media;
}
else if(id == 'selectedpage')
{
var media = document.getElementById('selectedmedia').value;
var page = document.getElementById(id).value;
self.location='workindex.php?selectedmedia=' + media + '&selectedpage=' + page;
}
else if(id == 'createmedia')
{
media = document.getElementById('createmedia').value;
mediatemplate = document.getElementById('mediatemplate').value;
season = document.getElementById('seas').value;
numpagesselected = document.getElementById('numpage').value;
http.open('get', 'includes/createmedia.php?media=' + media + '&mediatemplate=' + mediatemplate + '&seas=' + season + '&numpage=' + numpagesselected);
	http.onreadystatechange = handleProducts; 
	http.send(null);
	}

}


function handleProducts(){
	/* Make sure that the transaction has finished. The XMLHttpRequest object 
		has a property called readyState with several states:
		0: Uninitialized
		1: Loading
		2: Loaded
		3: Interactive
		4: Finished */
	if(http.readyState == 4){ 
		var response = http.responseText;
		alert(response);
		self.location='workindex.php';
	}
}
function submitarticles()
{
//alert(document.createarticles.price.value);
clearprice();
if(document.createarticles.position.value.length > 0 && document.createarticles.textlines.value.length > 0 && document.createarticles.page.value.length > 0 && document.createarticles.category.value.length > 0 && document.createarticles.elementlayout.value.length > 0 && document.createarticles.artselection.value.length > 0

&& (
 (document.createarticles.price.value >= 0.00 && document.createarticles.pricelb.value <= 0.00) ||
 (document.createarticles.price.value <= 0.00 && document.createarticles.pricelb.value >= 0.00)  
 ) 
 )
{
var valueArr = null;
str = '?';
for(var i = 0;i < document.createarticles.elements.length;i++)
{
switch(document.createarticles.elements[i].type)
       {
       case "hidden":
                            
                str += document.createarticles.elements[i].name +
                 "=" + encodeURIComponent(document.createarticles.elements[i].value) + "&";
                 break;
                 
           case "text":
                                 
                str += document.createarticles.elements[i].name +
                 "=" + encodeURIComponent(document.createarticles.elements[i].value) + "&";
                 break;
           case "textarea":
                                 
                str += document.createarticles.elements[i].name +
                 "=" + encodeURIComponent(document.createarticles.elements[i].value) + "&";
                 break;
           case "checkbox":
                 if(document.createarticles.elements[i].checked == true)
                 {
                 var submitvalue = '1';
                 }                
                 else
                 {
                 var submitvalue = '0';
                 }
                 
                str += document.createarticles.elements[i].name +
                 "=" + submitvalue + "&";
                 break;      
           case "select-one":
                str += document.createarticles.elements[i].name +
                "=" + encodeURIComponent(document.createarticles.elements[i].options[document.createarticles.elements[i].selectedIndex].value) + "&";
                break;
       }
//       alert('string = ' + str );
}
str = str.substr(0,(str.length - 1));
//alert ("str url = " + str);
http.open('get', 'includes/createarticle.php' + str); //call createarticle.php
http.onreadystatechange = createarticles; //call js function createarticles
http.send(null); 
}
else
{
alert('The Required fields are highlighted or you have both a price and a price LB entered.');
if(document.createarticles.position.value.length > 0 && document.createarticles.textlines.value.length > 0 && document.createarticles.page.value.length > 0 && document.createarticles.category.value.length > 0 && document.createarticles.elementlayout.value.length > 0 && document.createarticles.artselection.value.length > 0)
{
document.createarticles.price.style.background = '#ff9966';
document.createarticles.pricelb.style.background = '#ff9966';

}
else{
document.createarticles.page.style.background = 'yellow';
document.createarticles.position.style.background = 'yellow';
document.createarticles.textlines.style.background = 'yellow';
document.createarticles.category.style.background = 'yellow';
document.createarticles.elementlayout.style.background = 'yellow';
document.createarticles.artselection.style.background = 'yellow';
document.getElementById('selectedmedia').style.background = 'yellow';
}
//clearprice();
}
//alert("end of submitarticles js");
}
function createarticles(){
	/* Make sure that the transaction has finished. The XMLHttpRequest object 
		has a property called readyState with several states:
		0: Uninitialized
		1: Loading
		2: Loaded
		3: Interactive
		4: Finished */
	if(http.readyState == 4){ 
		var response = http.responseText;
		if(response == 'Success')
		{
		var form = document.getElementById('createarticles'); 
		var enteredpage = form.getElementsByTagName('input')['2'].value;
    var url = 'workindex.php?selectedmedia='+ document.getElementById('selectedmedia').value + '&selectedpage='+enteredpage;
    self.location=url;
    }
    else
    {
		document.getElementById('response').innerHTML = response;
		} 
	}
}


function artname(value, position)
{
var media = document.getElementById('selectedmedia').value;
if(document.createarticles.page.value.length < 1)
{
var page = document.getElementById('page').value;
}
else
{
var page = document.createarticles.page.value;
}
http.open('get', 'includes/ajaxartname.php?articlenumber=' + value + '&media=' +media + '&page=' + page + '&position=' + position + '&category=' + encodeURIComponent(document.createarticles.category.value) + '&textlines=' + encodeURIComponent(document.createarticles.textlines.value) + '&price=' + document.createarticles.price.value + '&pricelb=' + document.createarticles.pricelb.value + '&pricekg=' + document.createarticles.pricekg.value + '&elementlayout=' + document.createarticles.elementlayout.value + '&unitofsale=' + encodeURIComponent(document.createarticles.unitofsale.value) + '&measure=' + encodeURIComponent(document.createarticles.measure.value) + '&instructions=' + encodeURIComponent(document.createarticles.instructions.value) + '&artselection=' + encodeURIComponent(document.createarticles.artselection.value) + encodeURIComponent(document.createarticles.deletearticle.value) + encodeURIComponent(document.createarticles.articlename.value) + '&logo=' + encodeURIComponent(document.createarticles.logo.value));
http.onreadystatechange = artnameresponse; 
http.send(null);
}

//for onclick coming out of search window
function artsearch(media, page, value, position)
{
http.open('get', 'includes/ajaxartname.php?articlenumber=' + value + '&media=' +media + '&page=' + page + '&position=' + position + '&category=' + encodeURIComponent(document.createarticles.category.value) + '&textlines=' + encodeURIComponent(document.createarticles.textlines.value) + '&price=' + document.createarticles.price.value + '&pricelb=' + document.createarticles.pricelb.value + '&pricekg=' + document.createarticles.pricekg.value + '&elementlayout=' + document.createarticles.elementlayout.value + '&unitofsale=' + encodeURIComponent(document.createarticles.unitofsale.value) + '&measure=' + encodeURIComponent(document.createarticles.measure.value) + '&instructions=' + encodeURIComponent(document.createarticles.instructions.value) + '&artselection=' + encodeURIComponent(document.createarticles.artselection.value) + encodeURIComponent(document.createarticles.deletearticle.value) + encodeURIComponent(document.createarticles.articlename.value) + '&logo=' + encodeURIComponent(document.createarticles.logo.value));
http.onreadystatechange = artnameresponse; 
http.send(null);
}

function artnameresponse(){
	/* Make sure that the transaction has finished. The XMLHttpRequest object 
		has a property called readyState with several states:
		0: Uninitialized
		1: Loading
		2: Loaded
		3: Interactive
		4: Finished */
	if(http.readyState == 4){ 
	  
		var response = http.responseText;
//		alert ("response from ajaxartname.php = " + response);
		var data = eval('(' + response + ')'); 
    //*this receives the json array from ajaxartname.php mysql fetch and assigns the values to the form document name 'createarticles' *//
		document.createarticles.page.value = data.createarticles.page;
		if(data.createarticles.position == 0)
		{
    document.createarticles.position.value = "";
    }
    else
    {
    document.createarticles.position.value = data.createarticles.position;
    }
		
		document.createarticles.articlenumber.value = data.createarticles.articlenumber;	
    document.createarticles.articlename.value = data.createarticles.articlename;		
		document.createarticles.textlines.value = data.createarticles.textlines;
		document.createarticles.price.value = data.createarticles.price;
		document.createarticles.pricelb.value = data.createarticles.pricelb;
		document.createarticles.pricekg.value = data.createarticles.pricekg;
		
    document.createarticles.elementlayout.options[0].value = data.createarticles.elementlayout;
		document.createarticles.elementlayout.options[0].text = data.createarticles.elementlayout;
		document.createarticles.elementlayout.options.selectedIndex = 0;
		
      
		document.createarticles.unitofsale.value = data.createarticles.unitofsale;
		document.createarticles.measure.value = data.createarticles.measure;
		document.createarticles.instructions.value = data.createarticles.instructions;
		document.createarticles.logo.value = data.createarticles.logo;
		document.createarticles.category.options[0].value = data.createarticles.category;
		document.createarticles.category.options[0].text = data.createarticles.category;
		document.createarticles.category.options.selectedIndex = 0;
		document.createarticles.artselection.options[0].value = data.createarticles.artselection;
		document.createarticles.artselection.options[0].text = data.createarticles.artselection;
		document.createarticles.artselection.options.selectedIndex = 0;
		if(data.createarticles.deletearticle == 1)
		{
    document.createarticles.deletearticle.checked = 'true';
    }
		else
		{
    document.createarticles.deletearticle.checked = '';
    }

//alert(data.createarticles.deletearticle);
//alert(document.createarticles.category.options[0].value);
		}

}


function checkprice(id) 
{
var price1 = document.getElementById(id).value;
var temp = new Array();
temp = price1.split('.');
var text1 = temp['0'];
var text = temp['1'];
var string = text+'';
var nan = isNaN(price1);

if (string.length>2 && price1.indexOf('.') !=-1 || nan == true)
{
var newprice = window.prompt("Bad Price Please Fix");
document.getElementById(id).value = newprice;
var tf = checkprice(id);
}

}


function checkpricelb(id) 
{
//alert("in checkpricelb");
var price1 = document.getElementById(id).value;
var temp = new Array();
temp = price1.split('.');
var text1 = temp['0'];
var text = temp['1'];
var string = text+'';
var nan = isNaN(price1);

if (string.length>2 && price1.indexOf('.') !=-1 || nan == true)
{
var newprice = window.prompt("Bad Price Please Fix");
document.getElementById(id).value = newprice;
var tf = checkpricelb(id);
}
else
{
document.createarticles.pricekg.value = (document.createarticles.pricelb.value * 2.204622622).toFixed(2);
}
}



function upperartname(artid)
{
var artname = document.getElementById(artid).value;
document.getElementById('createarticleelementname').value=artname.toLowerCase().replace(/^(.)|\s(.)/g, 
          function($1) {
           return $1.toUpperCase(); 
           }
           )
           ;

}
/*function sbp()
{
var articlenumber = document.createarticles.articlenumber.value;
var searsposition = document.createarticles.searspos.value;
var searsposition = searsposition.toLowerCase().replace(/^(.)|\s(.)/g, 
          function($1) {
           return $1.toUpperCase(); 
           }
           )
           ;

var media = document.createarticles.media.value;
var page = document.createarticles.page.value;

var dept = articlenumber.substring(0,2);
var item = articlenumber.substring(2,articlenumber.length);

var ro84 = 'R' + dept + '84' + ' ' + media.substring(0,(media.length) -2) + ' ' + searsposition + ' ' + page;
var calcorder = dept + '8 4' + item.substring(0,2) + ' ' + item.substring(2,item.length);
document.createarticles.sbp.options[1] = new Option(calcorder,calcorder);
document.createarticles.sbp.options[2] = new Option(ro84,ro84);
} */



function clearprice()
{
if(document.createarticles.price.value == 0)
{
document.createarticles.price.value = '0.00';
}
if(document.createarticles.pricelb.value == 0)
{
document.createarticles.pricelb.value = '0.00';
}
if(document.createarticles.pricekg.value == 0)
{
document.createarticles.pricekg.value = '0.00';
} 
}

function editarticles(rowid)
{
var table = document.getElementById('currentarticlestable');
var tablebody = table.getElementsByTagName("tbody")[0];
var row = tablebody.getElementsByTagName("tr")[rowid];
if(row.title == 'closed')
{
for(var i = 0;i < row.getElementsByTagName("td").length;i++)
{
mycel = row.getElementsByTagName("td")[i].innerHTML;
celtitle = row.getElementsByTagName("td")[i].title;
if(celtitle == 'Price' || celtitle == 'Price LB')
{
var price = 'id="' + celtitle + rowid + '" onChange="checkprice(this.id)"';
}

row.getElementsByTagName("td")[i].innerHTML = '<input name="'+ celtitle +'" type="text" value="'+ mycel + '"' + price + '>';
}

var newRow   = table.insertRow(rowid + 1);
var newCell  = newRow.insertCell(0);
var string = '<button onClick=updatearticle(' + row.id + ')>Save Change</button>';
newCell.innerHTML = string;
row.title = 'open';
}

}

function updatearticle(rowid)
{
var row = document.getElementById(rowid);
var media = document.getElementById('selectedmedia').value;
var user = document.getElementById('currentuser').value;
if(row.title == 'open')
{
var string = 'id=' + rowid + '&media=' + media + '&lastuser=' + user;
for(var i = 0;i < row.getElementsByTagName("td").length;i++)
{
var cel = row.getElementsByTagName("td")[i];
string += '&' + cel.getElementsByTagName("input")[0].name + '=' + cel.getElementsByTagName("input")[0].value;
cel.innerHTML = '' + cel.getElementsByTagName("input")[0].value + '';
}
document.getElementById('currentarticlestable').deleteRow(row.rowIndex + 1);
row.title = 'closed';
}
http.open('post', 'includes/updatearticles.php?' + string);
http.onreadystatechange = finalupdatearticles; 
http.send(null);
}

function finalupdatearticles()
{
	if(http.readyState == 4){ 
		var response = http.responseText;
		if(response == 'Success')
		{
    
    }
    else
    {
		document.getElementById('response').innerHTML = response;
		}
	}
}

function markforexport()
{
var media = document.getElementById('selectedmedia').value;
var page = document.getElementById('selectedpage').value;
var user = document.getElementById('currentuser').value;
http.open('post', 'includes/markforexport.php?media=' + media + '&page=' + page + '&user=' + user);
http.onreadystatechange = markedforexportresponse; 
http.send(null);
}
function markedforexportresponse()
{
if(http.readyState == 4){ 
		var response = http.responseText;
		if(response == 'Update failed')
		{
		document.getElementById('response').innerHTML = response;
    }
    else
    {
    self.location='workindex.php';
    }
	}
}

function positionnan(value)
{
var nan = isNaN(value);
if (nan == true)
{
var newprice = window.prompt("Position must be a number!");
document.createarticles.position.value = newprice;
var tf = positionnan(newprice);
}

}
