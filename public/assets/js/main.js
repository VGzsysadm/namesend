// Sidenav
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems, {});
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems);
    var elems = document.querySelectorAll('.dropdown-activation');
    var instances = M.Dropdown.init(elems);
});
// CopyClipboard
function copyToClipboard(copyText) {
    var text = document.getElementById(copyText);
    navigator.clipboard.writeText(text.value)
}
