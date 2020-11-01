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
function copyToClipboard() {
    var text = document.querySelector('#secret_message');
    navigator.clipboard.writeText(text.value)
}