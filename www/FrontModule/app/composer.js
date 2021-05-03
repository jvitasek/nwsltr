import { Collapse, Dropdown, Modal } from 'bootstrap'
import Vue from 'vue';
import { store } from './store/store'
import Toasted from 'vue-toasted';

Vue.use(Toasted, {
    theme: 'toasted-primary', 
    duration: 5000
})

Vue.component("Composer", require("./Composer.vue").default);

const app = new Vue({
    el: '#app',
    store
});

/* SVG */
import info from '../images/info.svg'; 
import calendar from '../images/calendar.svg'; 
import heading from '../images/heading.svg'; 
import paragraph from '../images/paragraph.svg'; 
import list from '../images/list.svg'; 
import plus from '../images/plus.svg'; 
import edit from '../images/edit.svg'; 
import help from '../images/help.svg'; 
import image from '../images/image.svg';
import quote from '../images/quote.svg'; 
import trash from '../images/trash.svg'; 
import video from '../images/video.svg'; 
import atop from '../images/atop.svg'; 
import warn from '../images/warn.svg'; 
import link from '../images/link.svg'; 
import spacer from '../images/spacer.svg'; 
import divider from '../images/divider.svg'; 
import code from '../images/code.svg'; 
import devices from '../images/devices.svg'; 
import desktop from '../images/desktop.svg'; 
import tablet from '../images/tablet.svg'; 
import mobile from '../images/mobile.svg'; 