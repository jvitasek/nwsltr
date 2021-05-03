import flatpickr from "flatpickr";
import { Collapse, Dropdown, Modal } from 'bootstrap'
window.modal = Modal

/* SVG */
import edit from '../images/edit.svg'; 
import eye from '../images/eye.svg'; 
import trash from '../images/trash.svg'; 
import caretDown from '../images/caretDown.svg'; 
import caretUp from '../images/caretUp.svg';  
import filePlus from '../images/filePlus.svg';

/* custom datepicker */
document.querySelectorAll('.custom-datepicker').forEach(customDatepicker => {
    flatpickr(customDatepicker.querySelector('input'), {
        enableTime: false,
        locale: 'en',
        dateFormat: 'Y-m-d'
    });
}) 

/* Remove */
// const modalRemove = new Modal(document.getElementById('removeModal'), {
//     backdrop: false
// })

// document.querySelectorAll('.btn-remove').forEach(btnRemove => {
//     btnRemove.addEventListener('click', function() {
//         let id = btnRemove.getAttribute('data-item-id')
//         let title = btnRemove.getAttribute('data-item-title')
        
//         modalRemove.show()

//         document.querySelectorAll('.remove-item-title').forEach(itemTitle => {
//             itemTitle.innerHTML = title
//         })

//         document.querySelectorAll('.remove-item-id').forEach(itemId => {
//             itemId.value = id
//         })
//     })
// })