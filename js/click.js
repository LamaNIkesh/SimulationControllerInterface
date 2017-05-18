$(document).ready(function() {
    $(document).on('click', '.nav li', function (e) {
        $(this).parent().addClass('active').siblings().removeClass('active');
    });
});
