<!DOCTYPE html>
<html lang="en">
@include('layout.admin.header')
<body data-bs-theme="light" class="@yield('body','')">
    @include('layout.admin.menu')
    @yield('content')
    @include('layout.admin.footer')
    <div class="toast-container position-fixed bottom-0 start-50 translate-middle-x p-3"></div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="{{URL::asset('/js/jquery-3.7.1.min.js')}}"></script>
    <script src="{{URL::asset('/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('/js/swfoot-v1-0.js')}}"></script>
    <!-- <script src="{{URL::asset('/js/slick.js')}}"></script> -->
    <!-- <script src="{{URL::asset('/js/rkelectrical-js-v1.js')}}"></script> -->
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
    @stack('endjs')
    <script>
    function xfilter_submit() {
        const filterModel = document.querySelector('.xfilter_model');
        if (filterModel) {
            const fields = filterModel.querySelectorAll('.xfilter_field');
            let queryString = '';
            for (let i = 0; i < fields.length; i++) {
                const field = fields[i];
                if (field.value.trim() !== '') {
                    if (!(field.name == 'sort' && field.value == 'default')) {
                        queryString += `&${field.name}=${encodeURIComponent(field.value.trim())}`;
                    }
                }
            }

            // Append the page value
            // const page = document.getElementById('page') ? document.getElementById('page').value : 1;
            // queryString += `&page=${page}`;

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
            var sortElement = filterModel.querySelector('[name="sort"]');
            if (sortElement) {
                sortElement.selectedIndex = 0;
            }
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
</body>
</html>
