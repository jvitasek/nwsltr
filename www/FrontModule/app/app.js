import '../lib/noty';

/* CMS */
var Nette = require('../lib/nette-forms');
window['Noty'] = window['noty'] = require('noty');
window['Naja'] = window['naja'] = require('../lib/naja');
document.addEventListener('DOMContentLoaded', naja.initialize.bind(naja));
Nette.initOnLoad();

/* sidebar */
const sidebar = document.querySelector('.sidebar')
document.querySelectorAll('.sidebar-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if(sidebar.classList.contains('active')) {
            sidebar.classList.remove('active')
        }
        else {
            sidebar.classList.add('active')
        }
    })
})

/* SVG */
import paperPlane from '../images/paperPlane.svg';
import layerPlus from '../images/layerPlus.svg';
import adown from '../images/adown.svg';
import avatar from '../images/avatar.svg';
import filePlus from '../images/filePlus.svg';
import group from '../images/group.svg';
import home from '../images/home.svg';
import send from '../images/send.svg'; 
import users from '../images/users.svg';
import recipients from '../images/recipients.svg';
import account from '../images/account.svg';
import mail from '../images/mail.svg';
import menu from '../images/menu.svg';
import close from '../images/close.svg';