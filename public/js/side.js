const el = document.getElementById("ytsm");
if (el) {
    el.addEventListener("click", function()
    {
        if(this.className == "ytsn d-xl-none")
        {
            this.className = "ytsa d-xl-none";
            document.getElementById("ytmenu").style.left = 0;
        } else
        {
            this.className = "ytsn d-xl-none";
            document.getElementById("ytmenu").style.left = "-300px";
        }
    });
}