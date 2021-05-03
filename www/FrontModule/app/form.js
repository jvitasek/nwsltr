import Choices from 'choices.js';
import { Collapse, Dropdown, Modal } from 'bootstrap'
import flatpickr from "flatpickr"; 

/* custom datepicker */
document.querySelectorAll('.custom-datepicker').forEach(customDatepicker => {
    flatpickr(customDatepicker.querySelector('input'), {
        enableTime: false,
        locale: 'en',
        dateFormat: 'Y-m-d'
    });
}) 

/* Choices.js */
document.querySelectorAll('select[multiple]').forEach(item => {
    new Choices(item, {
		removeItemButton: true,
        loadingText: 'Loading...',
        noResultsText: 'No results for the query',
        noChoicesText: 'No choices',
        itemSelectText: 'Click for options',
        addItemText: 'Press enter to add',
        maxItemText: 'Only ${maxItemCount} items can be added',
        searchPlaceholderValue: 'Search...'
    });
}) 