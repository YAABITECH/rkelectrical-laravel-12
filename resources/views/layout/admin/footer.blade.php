<br><br><br>
@push('endjs')
<script>
    function xfilter_submit()
    {
        const filterModel = document.querySelector('.xfilter_model');
        if (filterModel) {
            const fields = filterModel.querySelectorAll('.xfilter_field');
            let queryString = '';
            for (let i = 0; i < fields.length; i++) {
                const field = fields[i];
                if (field.value.trim() !== '') {
                    if(!(field.name=='sort' && field.value=='default')){
                        queryString += `&${field.name}=${encodeURIComponent(field.value.trim())}`;
                    }
                }
            }
            if (queryString) {
                queryString = '?' + queryString.slice(1);
            }
            const urlElement = filterModel.querySelector('.queryurl');
            const url = urlElement ? urlElement.value : '/';
            if (url) {
                window.location.href = url + queryString;
            }
        }
    }
    function xfilter_reset() {
        event.preventDefault();
        const filterModel = document.querySelector('.xfilter_model');
        if (filterModel) {
            const fields = filterModel.querySelectorAll('.xfilter_field');
            for (let i = 0; i < fields.length; i++) {
                fields[i].value = '';
            }
            filterModel.querySelector('[name="sort"]').selectedIndex = 0;
            xfilter_submit();
        }
    }
    function xfilter_page(page) {
        if(document.getElementById("page"))
        {
            document.getElementById("page").value = page;
            xfilter_submit()
        }
    }
</script> 
@endpush