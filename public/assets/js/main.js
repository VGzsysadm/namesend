/*!
 * Author: VFoxtTroT)
 * Copyright 2019 Sysadm.es
 * MIT License (https://github.com/VGzsysadm)
 */
// Sidenav
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems, {});
});
// autoplay
const textMess = document.querySelector('#message_message');
const textCounterInstance = new M.CharacterCounter(textMess);
// CopyClipboard
function copyToClipboard() {
    var text = document.querySelector('#secret_message');
    navigator.clipboard.writeText(text.value)
}
// Modals
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems);
  });