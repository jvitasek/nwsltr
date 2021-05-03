document.querySelectorAll('.button').forEach(button => {
    const bounding = button.getBoundingClientRect()

    button.addEventListener('mousemove', e => { 
        let dy = (e.clientY - bounding.top - bounding.height / 2) / -1
        let dx = (e.clientX - bounding.left - bounding.width / 2)  / 10

        dy = dy > 10 ? 10 : (dy < -10 ? -10 : dy);
        dx = dx > 4 ? 4 : (dx < -4 ? -4 : dx);

        button.style.setProperty('--rx', dy);
        button.style.setProperty('--ry', dx);

    });

    button.addEventListener('mouseleave', e => {
        button.style.setProperty('--rx', 0)
        button.style.setProperty('--ry', 0)
    });

    button.addEventListener('click', e => {
        button.classList.add('success');
        button.querySelectorAll('.particle-inactive').forEach(particle => {
            particle.classList.add('particle')
        })
        if(document.querySelector('.form-group')) {
            document.querySelector('.form-group').classList.add('hide')
        }
        document.querySelector('.login p').classList.add('hide')
        document.querySelector('.login h1').innerHTML = 'Děkujeme.'
        if(document.querySelector('*[type="submit"]')) {
            document.querySelector('*[type="submit"]').style.display = 'none'
        }
    });
});