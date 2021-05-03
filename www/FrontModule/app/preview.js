/* devices */
const wrapper = document.querySelector('.preview-wrapper')
document.querySelectorAll('.devices-item').forEach(deviceBtn => {
    deviceBtn.addEventListener('click', function() {
        let device = deviceBtn.getAttribute('data-device')

        if(device === 'mobile') {
            wrapper.classList.add('preview-wrapper--mobile')
            wrapper.classList.remove('preview-wrapper--tablet')
        }
        else if(device === 'tablet') {
            wrapper.classList.add('preview-wrapper--tablet')
            wrapper.classList.remove('preview-wrapper--mobile')
        }
        else if(device === 'desktop') {
            wrapper.classList.remove('preview-wrapper--tablet')
            wrapper.classList.remove('preview-wrapper--mobile')
        }
    })
})

/* SVG */
import code from '../images/code.svg'; 
import devices from '../images/devices.svg'; 
import desktop from '../images/desktop.svg'; 
import tablet from '../images/tablet.svg'; 
import mobile from '../images/mobile.svg'; 