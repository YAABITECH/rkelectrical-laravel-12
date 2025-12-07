M.AutoInit();
function close_side_nav() {
    var e = document.getElementById("nav-mobile");
    M.Sidenav.getInstance(e).close()
}
if(document.getElementById('filajx'))
{
    formelem=document.getElementById('filajx');
    loc = formelem.action;
    formelem.addEventListener('submit', function(ev) 
    {
        ev.preventDefault();
        beforesubmit();
        var elements = document.getElementsByClassName("formval");
        var formData = new FormData(formelem);
        for(var i=0; i<elements.length; i++)
        {
            if(elements[i].value=='' && elements[i].hasAttribute('required'))
            {
                allow=missingform(elements[i]);
                if(allow=="no"){ return; }
            }
        }
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function()
        {
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                var res = this.responseText;
                aftersubmit(res);
            }
        }
        xmlhttp.open("POST", loc, true); 
        xmlhttp.send(formData);
    });
}