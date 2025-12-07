  function ch_ordnum()
  {
    var courseid=document.getElementById("courseid").value;
    var ordnum=document.getElementById("ordnum").value;
    var currordnum=document.getElementById("currordnum").value;
    var formData = new FormData();
    formData.append('courseid', courseid);
    formData.append('ordnum', ordnum);
    formData.append('currordnum', currordnum);
    document.getElementById("prvnxtdiv").style.display='none';
    document.getElementById("clvid-ifr").style.display='none';
    document.getElementById("clvid-box").style.display='block';
    document.getElementById("clvid-box").innerHTML='';
    document.getElementById("clvid-box").className='clvid-def';
    document.getElementById("chtitdiv").innerHTML = '<div class="clvid-stk title"></div>';
    document.getElementById("chcontdiv").innerHTML = '<div class="row margin-zero"><div class="col s3 l2" style="padding:0px !important;"><div class="clvid-stk full"></div></div><div class="col offset-s6 offset-l8 s3 l2" style="padding:0px !important;"><div class="clvid-stk full"></div></div></div><div class="clvid-stk full"></div><div class="clvid-stk full"></div><div class="clvid-stk full"></div>';
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
      if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
      {
        var res = this.responseText;
        resarr=res.split('|M|');
        var outmain=resarr[0];
        if(outmain=='ques_show')
        {
          document.getElementById("ansquesdiv").innerHTML=resarr[1];
          var ansquesmod = M.Modal.getInstance(document.getElementById("ansquesmod"));
				  ansquesmod.open();
        } else
        {
          var ordnum=resarr[6];
          var chapterid=resarr[7];
          document.getElementById("chapfilediv").innerHTML='';
          document.getElementById("chaptaskdiv").innerHTML ='';
          document.getElementById("ordnum").value=ordnum;
          document.getElementById("currordnum").value=ordnum;
          document.getElementById("chapterid").value=chapterid;
          if(outmain=='video' || outmain=='notanswer' || outmain=='limit' || outmain=='demo' || outmain=='buynow' || outmain=='login' || outmain=='waiting' || outmain=='novideo')
          {
            setTimeout(() => {
              document.getElementById("chtitdiv").innerHTML = resarr[3];
              document.getElementById("chcontdiv").innerHTML = resarr[4];
              if(outmain=='demo' || outmain=='video')
              {
                document.getElementById("clvid-ifr").innerHTML = '<div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/'+resarr[1]+'&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe></div>';
                document.getElementById("clvid-box").style.display='none';
                document.getElementById("clvid-box").className='';
                document.getElementById("clvid-ifr").style.display='block';
                if(document.getElementById("chxlimg_"+ordnum))
                {
                  document.getElementById("chxlimg_"+ordnum).src='/images/icon/check_circle.png';
                }
                if(resarr[5].length>0)
                {
                  document.getElementById("chapfilediv").innerHTML = '<a href="/course/source-file/'+resarr[5]+'" download><img src="/course/image/download-source-file.jpg" alt="Download source file" style="width:100%; max-width:250px"></a>';
                }
                if(resarr[8].length>0)
                {
                  if(resarr[9].length>0)
                  {
                    taskimg=resarr[9];
                  }
                  document.getElementById("chaptaskdiv").innerHTML = '<div class="card purple lighten-5 padding-20 center"><p class="font-size-1-2 purple-text text-darken-4 margin-zero bold">Upload your task</p><p>'+resarr[8]+'</p><p><img src="/course/task-image/'+taskimg+'" alt="" class="materialboxed" style="width:100%; max-width:600px; margin:auto; padding:10px"></p><p class="padding-10"><a href="" class="btn purple darken-4 waves-effect waves-light semi-bold" onclick="tskupload(); return false;">Upload Task</a></p><p class="grey-text text-darken-2">Compress your task files and folders into a single zip file using the WinRar application and upload the zip file here.</p></div>';
                }
              } else if(outmain=='login')
              {
                document.getElementById("clvid-box").className='';
                document.getElementById("clvid-box").innerHTML = '<div class="clvid-inv"><div class="clvid-inv2"></div><div class="clvid-inv3"><img src="/images/icon/lock_outline-white.png" class="icon-middle" style="width:40px"><br><p><a href="/user/logout" class="btn blue darken-4 semi-bold waves-effect waves-light">Login to Continue</a></p></div></div>';
              } else if(outmain=='buynow')
              {
                document.getElementById("clvid-box").className='';
                document.getElementById("clvid-box").innerHTML = '<div class="clvid-inv"><div class="clvid-inv2"></div><div class="clvid-inv3"><img src="/images/icon/lock_outline-white.png" class="icon-middle" style="width:40px"><br><p><a href="/course/website-development/register/" class="btn pink darken-4 semi-bold waves-effect waves-light">Buy Now</a></p></div></div>';
              } else if(outmain=='limit')
              {
                document.getElementById("clvid-box").className='';
                document.getElementById("clvid-box").innerHTML = '<div class="clvid-inv"><div class="clvid-inv2"></div><div class="clvid-inv3"><img src="/images/icon/lock_outline-white.png" class="icon-middle" style="width:40px"><br><p><a href="/course/website-development/register/" class="btn pink darken-4 semi-bold waves-effect waves-light">Make Remaining Payment</a></p></div></div>';
              } else if(outmain=='notanswer')
              {
                document.getElementById("clvid-box").className='';
                document.getElementById("clvid-box").innerHTML = '<div class="clvid-inv"><div class="clvid-inv2"></div><div class="clvid-inv3"><img src="/images/icon/lock_outline-white.png" class="icon-middle" style="width:50px"><br><p class="padding-left-10 padding-right-10">Answer previous chapter questions to watch the video.<p></div></div>';
              } else if(outmain=='waiting')
              {
                document.getElementById("clvid-box").className='';
                document.getElementById("clvid-box").innerHTML = '<div class="clvid-inv"><div class="clvid-inv2"></div><div class="clvid-inv3"><img src="/images/icon/access_time-white.png" class="icon-middle" style="width:50px"><br><p>2 videos are enabled per day. Please wait.</p></div></div>';
              } else if(outmain=='novideo')
              {
                document.getElementById("clvid-box").innerHTML = '';
                document.getElementById("clvid-box").style.display='none';
              }
              ordchange();
            }, 2000);
          } else if(res!='')
          {
            M.toast({html: res, classes: 'rounded pink darken-4 semi-bold'});
          } else
          {
            M.toast({html: 'Please refresh and try again. If the error persists, please let us know.', classes: 'rounded pink darken-4 semi-bold'});
          }
        }
      }
    }
    xmlhttp.open("POST", "/course/dynamic/chapter-ordnum", true); 
    xmlhttp.send(formData);
  }
  function dir_ordnum(num)
  {
    var ordnum=Number(document.getElementById("ordnum").value);
    if(document.getElementById("chkxl_"+ordnum))
    {
      document.getElementById("chkxl_"+ordnum).style.backgroundColor= '#ffffff';
    }
    document.getElementById("ordnum").value=num;
    var mdqry = window.matchMedia("(max-width: 600px)");
    if (mdqry.matches)
    {
      close_side_nav();
    }
    ch_ordnum();
  }
  function btn_ordnum(nxt)
  {
    var ordnum=Number(document.getElementById("ordnum").value);
    var totnum=Number(document.getElementById("totnum").value);
    var last_chapter=Number(document.getElementById("last_chapter").value);
    document.getElementById("chkxl_"+ordnum).style.backgroundColor= '#ffffff';
    if(nxt=='n')
    {
      if(ordnum<totnum)
      {
        nextnum=ordnum+1;
        document.getElementById("ordnum").value=nextnum;
        ch_ordnum();
      }
    } else if(nxt=='b')
    {
      if(ordnum>1)
      {
        nextnum=ordnum-1;
        document.getElementById("ordnum").value=nextnum;
        ch_ordnum();
      }
    }
  }
  function ordchange()
  {
    var ordnum=Number(document.getElementById("ordnum").value);
    var totnum=Number(document.getElementById("totnum").value);
    document.getElementById("chkxl_"+ordnum).style.backgroundColor= '#eeeeee';
    if(ordnum>=totnum)
    {
      document.getElementById("nxtbtn").style.display='none';
    } else
    {
      document.getElementById("prvnxtdiv").style.display='block';
      document.getElementById("nxtbtn").style.display='block';
    }
    if(ordnum<=1)
    {
      document.getElementById("befbtn").style.display='none';
    } else
    {
      document.getElementById("prvnxtdiv").style.display='block';
      document.getElementById("befbtn").style.display='block';
    }
  }
  function crsansq(q,a)
  {
    M.Toast.dismissAll();
    document.getElementById("ansqerr").innerHTML='';
    var ansqid=document.getElementById("ansqid"+q).value;
    document.getElementById("ansqa"+q).value=a;
    var formData = new FormData(); 
    formData.append('ansqid', ansqid);
    formData.append('ansqaval', a);
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
      if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
      {
        var res = this.responseText;
        if(res=='G')
        {
          M.toast({html: 'Well done! Right answer', classes: 'rounded teal darken-4 semi-bold'});
        } else if(res=='')
        {
          M.toast({html: 'Something went wrong. Please let us know. Thank yoụ', classes: 'rounded pink darken-4 semi-bold'});
        } else
        {
			    resarr = res.split("|M|");
          if(resarr[0]=='R')
          {
            M.toast({html: 'Sorry! Wrong answer', classes: 'rounded pink darken-4 semi-bold'});
            document.getElementById("ansqerr").innerHTML='<p class="pink darken-4 white-text" style="padding:10px; max-width:300px; width:100%; margin:auto; border-radius:5px">Correct Answer is: <span class="bold font-size-1-2">'+resarr[1]+'</span></p>';
          } else
          {
            M.toast({html: res, classes: 'rounded pink darken-4 semi-bold'});
          }
        }
        setTimeout(() => {
          document.getElementById("ansqerr").innerHTML='';
          var ansqtot=Number(document.getElementById("ansqtot").value);
          if(q<ansqtot && q>0){
            document.getElementById("ansqdiv"+q).style.display='none';
            document.getElementById("ansqpg"+q).className = "waves-effect";
            q=Number(q);
            q++;
            document.getElementById("ansqdiv"+q).style.display='block';
            document.getElementById("ansqpg"+q).className = "active purple darken-4 waves-effect";
            if(document.getElementById("ansqxnum"))
            {
              document.getElementById("ansqxnum").innerHTML=q;
            }
          } else if(q==ansqtot)
          {
            subansq();
          }
        }, 1500);
      }
    }
    xmlhttp.open("POST", "/course/dynamic/ansqeach", true); 
    xmlhttp.send(formData); 
  }
  function crsansd(q)
  {
    var ansqtot=document.getElementById("ansqtot").value;
    for (var i = 1; i <= ansqtot; i++) {
      document.getElementById("ansqdiv"+i).style.display='none';
      document.getElementById("ansqpg"+i).className = "waves-effect";
    }
    document.getElementById("ansqdiv"+q).style.display='block';
    document.getElementById("ansqpg"+q).className = "active purple darken-4 waves-effect";
  }
  function subansq()
  {
    M.Toast.dismissAll();
    var currordnum=document.getElementById("currordnum").value;
    var courseid=document.getElementById("courseid").value;
		var formData = new FormData(); 
    formData.append('currordnum', currordnum);
    formData.append('courseid', courseid);
    if(document.getElementById("ansqtot"))
    {
      var ansqtot=Number(document.getElementById("ansqtot").value);
      if(ansqtot>=1)
      {
        for (var i = 1; i <= ansqtot; i++) {
          var ansqa=document.getElementById("ansqa"+i).value;
          formData.append('ansqa'+i, ansqa);
        }
      }
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
      if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
      {
        var res = this.responseText;
			  resarr = res.split("|M|");
        if(resarr[0]=='OK')
        {
          document.getElementById("last_chapter").value=resarr[1];
          var ansquesmod = M.Modal.getInstance(document.getElementById("ansquesmod"));
				  ansquesmod.close();
          ch_ordnum();
        } else if(resarr[0]!='')
        {
          M.toast({html: res, classes: 'rounded pink darken-4 semi-bold'});
        } else
        {
          M.toast({html: 'Something went wrong. Please inform us. Thank you.', classes: 'rounded pink darken-4 semi-bold'});
        }
      }
    }
    xmlhttp.open("POST", "/course/dynamic/ansqans", true); 
    xmlhttp.send(formData); 
  }
  function tskupload()
  {
    var tskajxmod = M.Modal.getInstance(document.getElementById("tskajxmod"));
		tskajxmod.open();
  }
  formelem=document.getElementById('tskajx');
    loc = formelem.action;
    formelem.addEventListener('submit', function(ev) {
        ev.preventDefault();
        document.getElementById("tskajxld").innerHTML='Uploading...';
		    var formData = new FormData(); 
        var tsknotes=document.getElementById("tsknotes").value;
        var chapterid=document.getElementById("chapterid").value;
        var tskfile = document.getElementById("tskfile");
        formData.append('notes', tsknotes);
        formData.append('chapterid', chapterid);
        formData.append('tskfile', tskfile.files[0]);
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function()
        {
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                var res = this.responseText;
                if(res=='OK')
                {
                  M.toast({html: 'Task uploaded successfully', classes: 'rounded teal darken-4 semi-bold'});
                  var tskajxmod = M.Modal.getInstance(document.getElementById("tskajxmod"));
		              tskajxmod.close();
                } else if(res=='login')
                {
                  M.toast({html: 'Please login first to upload your task.', classes: 'rounded pink darken-4 semi-bold'});
                } else
                {
                  M.toast({html: 'Something went wrong. Please inform us. Thank you.', classes: 'rounded pink darken-4 semi-bold'});
                }
            }
        }
        xmlhttp.open("POST", loc, true); 
        xmlhttp.send(formData);
    });
function chatopen()
{
  if(document.getElementById("shlvchat").style.display=="block")
  {
    document.getElementById("shlvchat").style.display='none';
  } else
  {
    document.getElementById("shlvchat").style.display='block';
  }
}